<?php
/** Native AD integration. Optional configuration never changes local/OIDC defaults. */
require_once __DIR__ . '/encryption.php';
require_once __DIR__ . '/ad/Directory.php';

function adNormalizeEmail(string $email): string {
    $email = strtolower(trim($email));
    if (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('Invalid email');
    return $email;
}
function adQuery(PDO $db, string $sql, array $args = []): PDOStatement {
    $q = $db->prepare($sql); $q->execute($args); return $q;
}
function adConfig(PDO $db): array {
    $row = $db->query('SELECT * FROM ad_config WHERE id=1')->fetch(PDO::FETCH_ASSOC);
    if (!$row) throw new RuntimeException('AD migration required');
    return $row;
}
function adAudit(PDO $db, string $event, ?int $profile = null, array $details = []): void {
    adQuery($db, 'INSERT INTO ad_audit (profile_id, actor_analyst_id, event, details) VALUES (?,?,?,?)',
        [$profile, $_SESSION['analyst_id'] ?? null, $event, json_encode($details, JSON_THROW_ON_ERROR)]);
}
function adRateLimit(PDO $db, string $scope, string $key, int $limit, int $seconds): void {
    $bucket = hash('sha256', $scope . ':' . $key);
    adQuery($db, 'INSERT INTO ad_rate_limits (bucket, attempts, expires_at) VALUES (?,1,DATE_ADD(UTC_TIMESTAMP(), INTERVAL ? SECOND))
        ON DUPLICATE KEY UPDATE attempts=IF(expires_at<=UTC_TIMESTAMP(),1,attempts+1), expires_at=IF(expires_at<=UTC_TIMESTAMP(),VALUES(expires_at),expires_at)', [$bucket, $seconds]);
    $attempts = adQuery($db, 'SELECT attempts FROM ad_rate_limits WHERE bucket=?', [$bucket])->fetchColumn();
    if ($attempts > $limit) throw new RuntimeException('Please wait before trying again');
}
function adProviders(PDO $db): array {
    $rows = $db->query("SELECT p.*, c.authentication_enabled, c.trusted_linking, c.config_json, c.bind_password FROM auth_providers p
        JOIN ad_provider_config c ON c.provider_id=p.id WHERE p.protocol='ad' ORDER BY p.sort_order,p.id")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        $r['config'] = json_decode($r['config_json'], true, 512, JSON_THROW_ON_ERROR);
        $r['bind_password'] = decryptValue($r['bind_password']);
    }
    return $rows;
}
function adValidateProvider(array $c): array {
    $c += ['secondary_host'=>'', 'port'=>636, 'transport'=>'ldaps', 'timeout'=>5, 'ca_file'=>'',
        'email_attribute'=>'mail', 'name_attribute'=>'displayName', 'nested_groups'=>true];
    foreach (['host','secondary_host'] as $key) {
        if ($key === 'secondary_host' && $c[$key] === '') continue;
        if (!is_string($c[$key]) || !preg_match('/^(?=.{1,253}$)[a-zA-Z0-9](?:[a-zA-Z0-9.-]*[a-zA-Z0-9])?$/D', $c[$key])) throw new InvalidArgumentException('Invalid domain controller hostname');
    }
    if (!in_array($c['transport'], ['ldaps','starttls'], true)) throw new InvalidArgumentException('Encrypted LDAP required');
    foreach (['email_attribute','name_attribute'] as $key) {
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9-]*$/D', $c[$key])) throw new InvalidArgumentException('Invalid LDAP attribute');
    }
    foreach (['user_base','group_base','bind_dn'] as $key) {
        if (!is_string($c[$key] ?? null) || trim($c[$key]) === '' || str_contains($c[$key], "\0")) throw new InvalidArgumentException('Directory search bases and read-only bind identity are required');
    }
    $c['port'] = (int)$c['port']; $c['timeout'] = (int)$c['timeout'];
    if ($c['port'] < 1 || $c['port'] > 65535 || $c['timeout'] < 1 || $c['timeout'] > 15) throw new InvalidArgumentException('Invalid LDAP port or timeout');
    if ($c['ca_file'] !== '' && (!str_starts_with($c['ca_file'], '/') || !is_readable($c['ca_file']))) throw new InvalidArgumentException('CA file must be a readable mounted absolute path');
    return $c;
}
function adCandidates(array $providers, array $cfg, ?int $selected): array {
    $providers = array_values(array_filter($providers, static fn($p) => $p['enabled'] && $p['authentication_enabled']));
    if (!$cfg['multi_domain']) {
        return array_values(array_filter($providers, static fn($p) => (int)$p['id'] === (int)$cfg['default_provider_id']));
    }
    if ($selected) return array_values(array_filter($providers, static fn($p) => (int)$p['id'] === $selected));
    // Only administrators' explicitly trusted providers participate in automatic fallback.
    return array_slice(array_values(array_filter($providers, static fn($p) => (int)$p['id'] === (int)$cfg['default_provider_id'] || $p['trusted_linking'])),0,2);
}
function adAuthorizationRefreshDue(array $cfg, array $profile, bool $sensitive, int $now): bool {
    if (!$cfg['authority_provider_id']) return false;
    $age = $profile['authorization_checked_at'] ? $now-strtotime($profile['authorization_checked_at'].' UTC') : PHP_INT_MAX;
    return (int)$profile['authorization_revision'] !== (int)$cfg['revision'] || $profile['authorization_status']==='unavailable'
        || $age >= ($sensitive ? min(60,(int)$cfg['refresh_seconds']) : (int)$cfg['refresh_seconds']);
}

function adProfile(PDO $db, int $id): array {
    $p = adQuery($db, 'SELECT p.*,u.email,u.display_name,u.preferred_name FROM ad_profiles p JOIN users u ON u.id=p.user_id WHERE p.id=?', [$id])->fetch(PDO::FETCH_ASSOC);
    if (!$p) throw new RuntimeException('Profile unavailable');
    return $p;
}
function adResolveIdentity(PDO $db, array $provider, array $identity): array {
    // A GUID already linked remains authoritative even after a rename. Email changes need review.
    $id = adQuery($db, 'SELECT profile_id FROM ad_identities WHERE provider_id=? AND object_guid=?', [$provider['id'], $identity['guid']])->fetchColumn();
    if ($id) {
        $profile = adProfile($db, (int)$id);
        if (adNormalizeEmail($profile['email']) !== $identity['email']) throw new RuntimeException('Identity change requires administrator review');
        return $profile;
    }
    $rows = adQuery($db, 'SELECT p.id FROM ad_profiles p JOIN users u ON u.id=p.user_id WHERE LOWER(u.email)=?', [$identity['email']])->fetchAll(PDO::FETCH_COLUMN);
    if (count($rows) !== 1) throw new RuntimeException('Verify your corporate email or request identity review');
    $profile = adProfile($db, (int)$rows[0]);
    $links = adQuery($db, 'SELECT i.*,c.trusted_linking FROM ad_identities i JOIN ad_provider_config c ON c.provider_id=i.provider_id WHERE i.profile_id=?', [$profile['id']])->fetchAll(PDO::FETCH_ASSOC);
    if ($links && !adConfig($db)['multi_domain']) throw new RuntimeException('Cross-domain linking is disabled; administrator review required');
    foreach ($links as $link) {
        if ((int)$link['provider_id'] === (int)$provider['id'] || !$provider['trusted_linking'] || !$link['trusted_linking']) {
            throw new RuntimeException('Identity linking requires administrator review');
        }
    }
    adQuery($db, 'INSERT INTO ad_identities (provider_id,object_guid,profile_id,directory_email) VALUES (?,?,?,?)',
        [$provider['id'], $identity['guid'], $profile['id'], $identity['email']]);
    adAudit($db, 'identity_linked', (int)$profile['id'], ['provider_id'=>(int)$provider['id'], 'guid'=>$identity['guid']]);
    return $profile;
}
function adMappedGroups(array $mappings, array $guids): array {
    $matches = array_values(array_filter($mappings, static fn($m) => in_array($m['group_guid'], $guids, true)));
    if (!$matches) return [];
    $priority = max(array_column($matches, 'priority'));
    return array_values(array_unique(array_map(static fn($m) => (int)$m['access_group_id'],
        array_filter($matches, static fn($m) => $m['priority'] == $priority))));
}
function adRefresh(PDO $db, array $profile, AdDirectory $directory): array {
    $cfg = adConfig($db);
    if (!$cfg['authority_provider_id']) {
        adQuery($db, "UPDATE ad_profiles SET authorization_status='local',authorization_revision=? WHERE id=?", [$cfg['revision'],$profile['id']]);
        return adProfile($db, (int)$profile['id']);
    }
    try {
        $providers = array_values(array_filter(adProviders($db), static fn($p) => $p['enabled'] && (int)$p['id'] === (int)$cfg['authority_provider_id']));
        if (count($providers) !== 1) throw new RuntimeException('Authority unavailable');
        $provider = $providers[0];
        $identity = $directory->find($provider, adNormalizeEmail($profile['email']));
        if ($identity && ($identity['disabled'] || $identity['locked'])) throw new RuntimeException('Authorization account inactive');
        // Pin the authority identity after first successful lookup: email reassignment cannot inherit grants.
        if ($identity && $profile['authority_guid'] && (int)$profile['authority_provider_id'] === (int)$provider['id'] && $profile['authority_guid'] !== $identity['guid']) {
            throw new RuntimeException('Authorization identity requires review');
        }
        $guids = $identity ? $directory->groups($provider, $identity) : [];
        $mappings = adQuery($db, 'SELECT m.* FROM ad_role_mappings m JOIN access_groups g ON g.id=m.access_group_id AND g.is_active=1 WHERE m.provider_id=?', [$provider['id']])->fetchAll(PDO::FETCH_ASSOC);
        $groups = adMappedGroups($mappings, $guids);
        $db->beginTransaction();
        // Serialize refreshes for the same employee. Configuration changes cannot race with granting roles.
        adQuery($db, 'SELECT id FROM ad_profiles WHERE id=? FOR UPDATE', [$profile['id']]);
        $current = adQuery($db, 'SELECT revision FROM ad_config WHERE id=1 FOR UPDATE')->fetchColumn();
        if ((int)$current !== (int)$cfg['revision']) throw new RuntimeException('Authorization configuration changed; retry');
        if ($groups && !$profile['analyst_id']) {
            $existing = adQuery($db, 'SELECT id FROM analysts WHERE LOWER(email)=?', [$profile['email']])->fetchColumn();
            if ($existing) throw new RuntimeException('Existing analyst identity requires administrator review');
            adQuery($db, 'INSERT INTO analysts (username,password_hash,full_name,email,is_active) VALUES (?,?,?,?,1)',
                ['ad-' . $profile['id'], password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT), mb_substr($profile['display_name'],0,100), $profile['email']]);
            $profile['analyst_id'] = (int)$db->lastInsertId();
            adQuery($db, 'UPDATE ad_profiles SET analyst_id=?,managed_analyst=1 WHERE id=?', [$profile['analyst_id'],$profile['id']]);
        }
        if ($profile['analyst_id']) {
            adQuery($db, "DELETE FROM analyst_access_groups WHERE analyst_id=? AND source='ad'", [$profile['analyst_id']]);
            foreach ($groups as $group) adQuery($db, "INSERT INTO analyst_access_groups (analyst_id,access_group_id,source) VALUES (?,?,'ad')", [$profile['analyst_id'],$group]);
        }
        adQuery($db, "UPDATE ad_profiles SET authorization_status='current',authorization_checked_at=UTC_TIMESTAMP(),authorization_revision=?,authority_guid=COALESCE(?,authority_guid),authority_provider_id=?,authorization_groups=? WHERE id=?",
            [$cfg['revision'],$identity['guid'] ?? null,$provider['id'],json_encode($guids),$profile['id']]);
        adAudit($db, 'authorization_refreshed', (int)$profile['id'], ['provider_id'=>(int)$provider['id'],'access_groups'=>$groups]);
        $db->commit();
        return adProfile($db, (int)$profile['id']);
    } catch (Throwable $e) {
        if ($db->inTransaction()) $db->rollBack();
        adQuery($db, "UPDATE ad_profiles SET authorization_status='unavailable' WHERE id=?", [$profile['id']]);
        adAudit($db, 'authorization_failed', (int)$profile['id']);
        throw new RuntimeException('Authorization unavailable; contact an administrator', 0, $e);
    }
}
/** Reuse existing module assignments, but never give an auto-created AD analyst unrestricted access. */
function adModules(PDO $db, array $profile): ?array {
    if (!$profile['analyst_id']) return [];
    $analyst = adQuery($db, 'SELECT is_active FROM analysts WHERE id=?', [$profile['analyst_id']])->fetchColumn();
    if (!$analyst) return [];
    $modules = adQuery($db, 'SELECT module_key FROM analyst_modules WHERE analyst_id=? UNION SELECT gm.module_key FROM analyst_access_groups a JOIN access_groups g ON g.id=a.access_group_id AND g.is_active=1 JOIN access_group_modules gm ON gm.access_group_id=g.id WHERE a.analyst_id=?',
        [$profile['analyst_id'],$profile['analyst_id']])->fetchAll(PDO::FETCH_COLUMN);
    return $modules ? array_values(array_unique($modules)) : ($profile['managed_analyst'] ? [] : null);
}
function adSetSession(PDO $db, array $profile, int $providerId): void {
    // Rebuild the existing session identities; never retain an unrelated analyst/OIDC login.
    $_SESSION = [];
    session_regenerate_id(true);
    $_SESSION['ad_profile_id'] = (int)$profile['id'];
    $_SESSION['ad_provider_id'] = $providerId;
    $_SESSION['ss_user_id'] = (int)$profile['user_id'];
    $_SESSION['ss_user_email'] = $profile['email'];
    $_SESSION['ss_user_name'] = $profile['preferred_name'] ?: $profile['display_name'];
    $modules = adModules($db, $profile);
    if ($profile['analyst_id'] && ($modules === null || count($modules))) {
        $a = adQuery($db, 'SELECT * FROM analysts WHERE id=?', [$profile['analyst_id']])->fetch(PDO::FETCH_ASSOC);
        $_SESSION['analyst_id'] = (int)$a['id']; $_SESSION['analyst_username'] = $a['username'];
        $_SESSION['analyst_email'] = $a['email']; $_SESSION['analyst_name'] = $a['full_name'];
        $_SESSION['allowed_modules'] = $modules;
    }
}
function adLogin(PDO $db, string $email, string $password, ?int $selected = null, ?AdDirectory $directory = null): array {
    $cfg = adConfig($db);
    if (!$cfg['enabled']) throw new RuntimeException('AD sign-in unavailable');
    $email = adNormalizeEmail($email);
    adRateLimit($db, 'login-ip', $_SERVER['REMOTE_ADDR'] ?? 'unknown', 20, 300);
    adRateLimit($db, 'login-email', $email, 5, 300);
    if ($password === '' || strlen($password) > 4096) throw new RuntimeException('Sign-in failed');
    $directory ??= new AdLdapDirectory();
    $attempts = 0;
    foreach (adCandidates(adProviders($db), $cfg, $selected) as $provider) {
        $identity = $directory->find($provider, $email);
        if (!$identity) continue;
        if ($identity['disabled'] || $identity['locked'] || $identity['expired']) throw new RuntimeException('Sign-in failed');
        if (++$attempts > (int)$cfg['max_attempts']) break;
        if (!$directory->authenticate($provider, $identity, $password)) continue;
        $db->beginTransaction();
        try {
            // Lock the employee before cross-provider linking to prevent conflicting concurrent links.
            adQuery($db, 'SELECT id FROM users WHERE LOWER(email)=? FOR UPDATE', [$email]);
            $profile = adResolveIdentity($db, $provider, $identity);
            adQuery($db, 'UPDATE users SET display_name=? WHERE id=?', [mb_substr($identity['name'],0,255),$profile['user_id']]);
            adQuery($db, 'UPDATE ad_identities SET last_login_at=UTC_TIMESTAMP() WHERE provider_id=? AND object_guid=?', [$provider['id'],$identity['guid']]);
            $db->commit();
        } catch (Throwable $e) { if ($db->inTransaction()) $db->rollBack(); throw $e; }
        $profile = adRefresh($db, adProfile($db,(int)$profile['id']), $directory);
        adSetSession($db,$profile,(int)$provider['id']);
        adAudit($db,'login_success',(int)$profile['id'],['provider_id'=>(int)$provider['id']]);
        return ['success'=>true,'redirect'=>BASE_URL . (isset($_SESSION['analyst_id']) ? '' : 'self-service/')];
    }
    adAudit($db,'login_failed');
    throw new RuntimeException('Sign-in failed');
}

function adRequestVerification(PDO $db, string $email, ?callable $send = null): void {
    $cfg = adConfig($db);
    if (!$cfg['enabled']) throw new RuntimeException('AD registration unavailable');
    adRateLimit($db,'verification-ip',$_SERVER['REMOTE_ADDR'] ?? 'unknown',10,900);
    $email = adNormalizeEmail($email);
    adRateLimit($db,'verification-email',$email,3,900);
    $allowed = json_decode($cfg['allowed_email_domains'],true,512,JSON_THROW_ON_ERROR);
    if (!in_array(substr(strrchr($email,'@'),1),$allowed,true)) return;
    // Existing accounts require administrative reconciliation, never claim them by email alone.
    if (adQuery($db,'SELECT id FROM users WHERE LOWER(email)=?',[$email])->fetchColumn()
        || adQuery($db,'SELECT id FROM analysts WHERE LOWER(email)=?',[$email])->fetchColumn()) return;
    $token = bin2hex(random_bytes(32));
    $db->beginTransaction();
    try {
        adQuery($db,'UPDATE ad_verification_tokens SET used_at=UTC_TIMESTAMP() WHERE email=? AND used_at IS NULL',[$email]);
        adQuery($db,'INSERT INTO ad_verification_tokens (token_hash,email,expires_at) VALUES (?,?,DATE_ADD(UTC_TIMESTAMP(),INTERVAL ? MINUTE))',
            [hash('sha256',$token),$email,$cfg['verification_minutes']]);
        $db->commit();
    } catch (Throwable $e) { $db->rollBack(); throw $e; }
    $url = rtrim($cfg['public_url'],'/') . '/ad-sign-in.php?token=' . $token;
    try {
        if ($send) $send($email,$url);
        else {
            require_once __DIR__.'/mail/SmtpMailer.php';
            $mailbox = adQuery($db,'SELECT * FROM target_mailboxes WHERE id=? AND is_active=1',[$cfg['smtp_mailbox_id']])->fetch(PDO::FETCH_ASSOC);
            if (!$mailbox) throw new RuntimeException('Registration SMTP mailbox unavailable');
            $mailbox = decryptMailboxRow($mailbox);
            if (!in_array($mailbox['smtp_encryption'] ?? '',['ssl','tls'],true)) throw new RuntimeException('Encrypted SMTP required');
            SmtpMailer::send($mailbox,$email,'Verify your FreeITSM corporate email','<p>Verify your email to continue with AD sign-in:</p><p><a href="'.htmlspecialchars($url,ENT_QUOTES).'">Verify email</a></p><p>This single-use link expires in '.(int)$cfg['verification_minutes'].' minutes. Verification alone does not sign you in.</p>');
        }
    } catch (Throwable $e) {
        adQuery($db,'UPDATE ad_verification_tokens SET used_at=UTC_TIMESTAMP() WHERE token_hash=?',[hash('sha256',$token)]);
        adAudit($db,'verification_delivery_failed');
        throw new RuntimeException('Verification delivery unavailable');
    }
}
function adVerifyEmail(PDO $db, string $token): void {
    if (!adConfig($db)['enabled'] || !preg_match('/^[a-f0-9]{64}$/D',$token)) throw new RuntimeException('Verification link invalid or expired');
    $db->beginTransaction();
    try {
        $r = adQuery($db,'SELECT * FROM ad_verification_tokens WHERE token_hash=? AND used_at IS NULL AND expires_at>UTC_TIMESTAMP() FOR UPDATE',[hash('sha256',$token)])->fetch(PDO::FETCH_ASSOC);
        if (!$r) throw new RuntimeException('Verification link invalid or expired');
        if (adQuery($db,'SELECT id FROM users WHERE LOWER(email)=?',[$r['email']])->fetchColumn()
            || adQuery($db,'SELECT id FROM analysts WHERE LOWER(email)=?',[$r['email']])->fetchColumn()) throw new RuntimeException('Account requires administrator review');
        adQuery($db,'INSERT INTO users (email,display_name) VALUES (?,?)',[$r['email'],$r['email']]);
        $uid = (int)$db->lastInsertId();
        adQuery($db,'INSERT INTO ad_profiles (user_id,managed_user,verified_at) VALUES (?,1,UTC_TIMESTAMP())',[$uid]);
        adAudit($db,'email_verified',(int)$db->lastInsertId());
        adQuery($db,'UPDATE ad_verification_tokens SET used_at=UTC_TIMESTAMP() WHERE token_hash=?',[$r['token_hash']]);
        $db->commit();
    } catch (Throwable $e) { if ($db->inTransaction()) $db->rollBack(); throw $e; }
}
/** Restrict AD administration to explicit system grants or existing unrestricted local admins. */
function adRequireAdmin(PDO $db): void {
    $id = (int)($_SESSION['analyst_id'] ?? 0);
    if (!$id || !adQuery($db,'SELECT id FROM analysts WHERE id=? AND is_active=1',[$id])->fetchColumn()) throw new RuntimeException('Administrator required');
    $p = adQuery($db,'SELECT p.*,u.email,u.display_name,u.preferred_name FROM ad_profiles p JOIN users u ON u.id=p.user_id WHERE analyst_id=?',[$id])->fetch(PDO::FETCH_ASSOC);
    $modules = $p ? adModules($db,$p) : getAnalystEffectiveModules($db,$id);
    // Legacy module helper adds system implicitly: check explicit assignments for restricted users.
    if ($modules !== null && !adQuery($db,"SELECT module_key FROM analyst_modules WHERE analyst_id=? AND module_key='system'
        UNION SELECT gm.module_key FROM analyst_access_groups a JOIN access_groups g ON g.id=a.access_group_id AND g.is_active=1 JOIN access_group_modules gm ON gm.access_group_id=g.id WHERE a.analyst_id=? AND gm.module_key='system'",[$id,$id])->fetchColumn()) throw new RuntimeException('Administrator required');
}
function adCsrf(): string {
    if (empty($_SESSION['ad_csrf'])) $_SESSION['ad_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['ad_csrf'];
}
function adCheckCsrf(string $token): void {
    if (empty($_SESSION['ad_csrf']) || !hash_equals($_SESSION['ad_csrf'],$token)) throw new RuntimeException('Security token invalid; reload the page');
}

function adManagedAccount(PDO $db, string $kind, int $id): bool {
    if (!in_array($kind,['analyst','user'],true)) throw new InvalidArgumentException('Invalid account kind');
    try {
        return (bool)adQuery($db,'SELECT id FROM ad_profiles WHERE '.$kind.'_id=? AND managed_'.$kind.'=1',[$id])->fetchColumn();
    } catch (PDOException $e) {
        if (($e->errorInfo[1] ?? null) === 1146) return false; // Pre-migration installations retain existing login.
        throw $e;
    }
}

/** Protect the chosen break-glass account in existing analyst administration paths. */
function adProtectEmergency(PDO $db, int $id, bool $unsafe): void {
    if (!$unsafe || !$id) return;
    try { $cfg=adConfig($db); }
    catch (PDOException $e) { if (($e->errorInfo[1] ?? null)===1146) return; throw $e; }
    if ((int)$cfg['emergency_analyst_id']===$id) throw new RuntimeException('Choose another protected local emergency administrator before removing local administrative access');
}

function adSignInEnabled(): bool {
    try { return (bool)adConfig(connectToDatabase())['enabled']; }
    catch (Throwable $e) { return false; }
}
