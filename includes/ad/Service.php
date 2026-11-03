<?php
require_once __DIR__ . '/../encryption.php';
require_once __DIR__ . '/Directory.php';

/** Uses existing users, analysts and access-group/module permissions. */
final class AdService {
    public function __construct(public readonly PDO $db, private readonly AdDirectory $directory = new LdapAdDirectory()) {}
    public static function email(string $email): string {
        $email = strtolower(trim($email));
        if (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) throw new AdFailure('invalid_email');
        return $email;
    }
    public function query(string $sql, array $args = []): PDOStatement {
        $s = $this->db->prepare($sql); $s->execute($args); return $s;
    }
    public function settings(): array {
        return $this->query('SELECT * FROM ad_settings WHERE id = 1')->fetch(PDO::FETCH_ASSOC);
    }
    public function profile(int $id, bool $lock = false): array {
        $p = $this->query('SELECT * FROM ad_profiles WHERE id = ?' . ($lock ? ' FOR UPDATE' : ''), [$id])->fetch(PDO::FETCH_ASSOC);
        if (!$p || $p['disabled']) throw new AdFailure('profile_unavailable');
        return $p;
    }
    public function providers(): array {
        $rows = $this->query('SELECT * FROM ad_providers ORDER BY priority, id')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) $r['config'] = json_decode($r['config_json'], true, 512, JSON_THROW_ON_ERROR);
        return $rows;
    }
    public function audit(string $event, ?int $profileId, array $details = []): void {
        $this->query('INSERT INTO ad_audit (profile_id, actor_analyst_id, event, details_json) VALUES (?, ?, ?, ?)',
            [$profileId, $_SESSION['analyst_id'] ?? null, $event, json_encode($details, JSON_THROW_ON_ERROR)]);
    }
    /** Atomic fixed-window limits shared across sessions/workers. Never reset after success. */
    public function limit(string $scope, string $identity, int $maximum, int $seconds): void {
        $bucket = hash('sha256', $scope . ':' . $identity); $now = time();
        $this->query('INSERT INTO ad_rate_limits (bucket, window_start, attempts) VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE attempts = IF(window_start <= ?, 1, attempts + 1), window_start = IF(window_start <= ?, ?, window_start)',
            [$bucket, $now, $now - $seconds, $now - $seconds, $now]);
        if ((int)$this->query('SELECT attempts FROM ad_rate_limits WHERE bucket = ?', [$bucket])->fetchColumn() > $maximum) throw new AdFailure('rate_limited');
    }
    public function candidates(array $cfg, array $providers, ?int $previous = null, ?int $selected = null): array {
        $providers = array_values(array_filter($providers, fn($p) => $p['enabled'] && $p['trusted'] && $p['authentication_enabled']));
        if (!$cfg['multi_domain']) {
            $providers = array_values(array_filter($providers, fn($p) => (int)$p['id'] === (int)$cfg['default_provider_id']));
        }
        if ($selected) $providers = array_values(array_filter($providers, fn($p) => (int)$p['id'] === $selected));
        usort($providers, fn($a, $b) => [((int)$a['id'] === $previous ? 0 : ((int)$a['id'] === (int)$cfg['default_provider_id'] ? 1 : 2)), $a['priority'], $a['id']]
            <=> [((int)$b['id'] === $previous ? 0 : ((int)$b['id'] === (int)$cfg['default_provider_id'] ? 1 : 2)), $b['priority'], $b['id']]);
        return $providers;
    }
    private function assertAccount(array $a, string $email): void {
        if (self::email($a['email']) !== $email || !preg_match('/^[a-f0-9]{32}$/D', $a['guid'])) throw new AdFailure('ambiguous_identity');
        if ($a['disabled'] || $a['locked'] || $a['expired']) throw new AdFailure('account_unavailable');
    }
    private function assertLink(int $profileId, int $providerId, array $a): void {
        $links = $this->query('SELECT * FROM ad_identities WHERE (provider_id = ? AND object_guid = ?) OR (profile_id = ? AND provider_id = ?)', [$providerId, $a['guid'], $profileId, $providerId])->fetchAll(PDO::FETCH_ASSOC);
        foreach ($links as $link) {
            if ((int)$link['profile_id'] !== $profileId || $link['object_guid'] !== $a['guid'] || $link['directory_email'] !== $a['email']) throw new AdFailure('identity_review_required');
        }
    }
    private function link(int $profileId, int $providerId, array $a, bool $login = false): void {
        $this->assertLink($profileId, $providerId, $a);
        $this->query('INSERT IGNORE INTO ad_identities (provider_id, object_guid, profile_id, directory_email) VALUES (?, ?, ?, ?)', [$providerId, $a['guid'], $profileId, $a['email']]);
        if ($login) $this->query('UPDATE ad_identities SET last_login_at = UTC_TIMESTAMP() WHERE provider_id = ? AND object_guid = ?', [$providerId, $a['guid']]);
    }
    /** Resolve all trusted mirrored identities before binding, so an outage or ambiguity cannot partially link a profile. */
    public function login(string $email, string $password, ?int $selected = null): array {
        $email = self::email($email); $cfg = $this->settings();
        if (!$cfg['enabled'] || $password === '' || strlen($password) > 4096) throw new AdFailure('invalid_credentials');
        $this->limit('login_ip', $_SERVER['REMOTE_ADDR'] ?? 'cli', 30, 900);
        $this->limit('login_email', $email, 5, 900);
        $p = $this->query('SELECT * FROM ad_profiles WHERE email = ?', [$email])->fetch(PDO::FETCH_ASSOC);
        if (!$p || !$p['verified_at'] || $p['disabled']) throw new AdFailure('invalid_credentials');
        $providers = $this->candidates($cfg, $this->providers(), $p['last_provider_id'] ? (int)$p['last_provider_id'] : null);
        $resolved = [];
        foreach ($providers as $provider) {
            $account = $this->directory->find($provider, $email);
            if (!$account) continue;
            $this->assertAccount($account, $email);
            $this->assertLink((int)$p['id'], (int)$provider['id'], $account);
            $resolved[] = [$provider, $account];
        }
        $budget = $cfg['multi_domain'] && $cfg['allow_fallback'] ? min(2, (int)$cfg['max_attempts']) : 1;
        // Validate all existing identity anchors before optional selection narrows
        // password binds; an operator cannot bypass a GUID conflict by choosing a domain.
        $existingLinks = $this->query('SELECT * FROM ad_identities WHERE profile_id=?', [$p['id']])->fetchAll(PDO::FETCH_ASSOC);
        foreach ($existingLinks as $link) {
            if (!in_array((int)$link['provider_id'], array_map(fn($v) => (int)$v[0]['id'], $resolved), true)
                && in_array((int)$link['provider_id'], array_map(fn($v) => (int)$v['id'], $providers), true)) throw new AdFailure('identity_review_required');
        }
        if (count(array_unique(array_map(fn($v) => strtolower($v[1]['username']), $resolved))) > 1) throw new AdFailure('ambiguous_identity');
        $bindCandidates = $selected ? array_values(array_filter($resolved, fn($v) => (int)$v[0]['id'] === $selected)) : $resolved;
        $authenticated = null;
        foreach (array_slice($bindCandidates, 0, $budget) as [$provider, $account]) {
            if ($this->directory->authenticate($provider, $account, $password)) { $authenticated = [$provider, $account]; break; }
        }
        if (!$authenticated) { $this->audit('authentication_failed', (int)$p['id']); throw new AdFailure('invalid_credentials'); }
        try {
            $this->db->beginTransaction();
            $currentCfg = $this->query('SELECT * FROM ad_settings WHERE id=1 FOR SHARE')->fetch(PDO::FETCH_ASSOC);
            if (!$currentCfg['enabled'] || (int)$currentCfg['revision'] !== (int)$cfg['revision']) throw new AdFailure('configuration_changed_retry');
            $p = $this->profile((int)$p['id'], true);
            // Freeze both mirror GUIDs on first proof, not only the password-winning account.
            foreach ($resolved as [$provider, $account]) $this->link((int)$p['id'], (int)$provider['id'], $account, (int)$provider['id'] === (int)$authenticated[0]['id']);
            if (!$p['user_id']) {
                if ($this->query('SELECT id FROM users WHERE LOWER(email) = ?', [$email])->fetchColumn() || $this->query('SELECT id FROM analysts WHERE LOWER(email) = ?', [$email])->fetchColumn()) throw new AdFailure('identity_review_required');
                $this->query('INSERT INTO users (email, display_name, created_at) VALUES (?, ?, UTC_TIMESTAMP())', [$email, $authenticated[1]['name'] ?: $email]);
                $p['user_id'] = (int)$this->db->lastInsertId();
                $this->query('UPDATE ad_profiles SET user_id = ? WHERE id = ?', [$p['user_id'], $p['id']]);
            }
            $this->query('UPDATE users SET display_name = ? WHERE id = ?', [$authenticated[1]['name'] ?: $email, $p['user_id']]);
            $this->query('UPDATE ad_profiles SET last_provider_id = ? WHERE id = ?', [$authenticated[0]['id'], $p['id']]);
            $this->db->commit();
        } catch (Throwable $e) { if ($this->db->inTransaction()) $this->db->rollBack(); throw $e; }
        $this->refresh((int)$p['id']);
        $this->audit('authentication_succeeded', (int)$p['id'], ['provider_id' => $authenticated[0]['id']]);
        return $this->profile((int)$p['id']);
    }
    public function refresh(int $id): array {
        $p = $this->profile($id); $cfg = $this->settings();
        try {
            if (!$cfg['enabled']) throw new AdFailure('ad_disabled');
            $authority = null;
            foreach ($this->providers() as $provider) if ((int)$provider['id'] === (int)$cfg['authority_id'] && $provider['enabled'] && $provider['trusted']) $authority = $provider;
            if (!$authority) throw new AdFailure('authority_unavailable');
            $account = $this->directory->find($authority, $p['email']);
            $groups = [];
            if ($account) {
                $this->assertAccount($account, $p['email']);
                $this->assertLink($id, (int)$authority['id'], $account);
                $groups = $this->directory->groups($authority, $account);
            }
            $mappings = $this->query('SELECT m.*, ag.group_key, ag.display_name FROM ad_group_mappings m JOIN access_groups ag ON ag.id = m.access_group_id AND ag.is_active = 1 WHERE m.provider_id = ? ORDER BY m.precedence, m.id', [$authority['id']])->fetchAll(PDO::FETCH_ASSOC);
            $guids = array_column($groups, 'guid');
            $matched = array_values(array_filter($mappings, fn($m) => in_array($m['group_guid'], $guids, true) && $m['group_key'] !== 'guest'));
            $modules = [];
            foreach ($matched as $m) $modules = array_merge($modules, $this->query('SELECT module_key FROM access_group_modules WHERE access_group_id = ?', [$m['access_group_id']])->fetchAll(PDO::FETCH_COLUMN));
            $modules = array_values(array_unique($modules));
            // An empty access group NEVER means unrestricted analyst access.
            $state = ['authority_id' => (int)$authority['id'], 'authority_name' => $authority['name'], 'account' => $account['username'] ?? null,
                'groups' => $groups, 'matched' => $matched, 'modules' => $modules, 'role' => $modules && $matched ? $matched[0]['display_name'] : 'Guest'];
            $this->db->beginTransaction();
            $currentCfg = $this->query('SELECT revision FROM ad_settings WHERE id=1 FOR SHARE')->fetchColumn();
            if ((int)$currentCfg !== (int)$cfg['revision']) throw new AdFailure('configuration_changed_retry');
            $p = $this->profile($id, true);
            if ($account) $this->link($id, (int)$authority['id'], $account);
            if ($modules && !$p['analyst_id'] && $p['user_id']) {
                $this->query('INSERT INTO analysts (username, password_hash, full_name, email, is_active, created_datetime) VALUES (?, ?, ?, ?, 1, UTC_TIMESTAMP())',
                    ['ad-' . $id . '-' . bin2hex(random_bytes(4)), password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT), $p['email'], $p['email']]);
                $p['analyst_id'] = (int)$this->db->lastInsertId();
                $this->query('UPDATE ad_profiles SET analyst_id = ? WHERE id = ?', [$p['analyst_id'], $id]);
            }
            if ($p['analyst_id']) {
                $this->query("DELETE FROM analyst_access_groups WHERE analyst_id = ? AND source = 'ad'", [$p['analyst_id']]);
                foreach ($matched as $m) $this->query("INSERT IGNORE INTO analyst_access_groups (analyst_id, access_group_id, source) VALUES (?, ?, 'ad')", [$p['analyst_id'], $m['access_group_id']]);
            }
            $this->query("UPDATE ad_profiles SET authorization_status = 'current', authorization_json = ?, refreshed_at = UTC_TIMESTAMP(), revision = ? WHERE id = ?", [json_encode($state), $cfg['revision'], $id]);
            $this->audit('authorization_refreshed', $id, $state);
            $this->db->commit();
            return $this->profile($id);
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            $reason = $e instanceof AdFailure ? $e->reason : 'authorization_unavailable';
            // Preserve persisted last-known grants, but the guard will deny their use.
            $this->query("UPDATE ad_profiles SET authorization_status = ? WHERE id = ?", [$reason, $id]);
            $this->audit('authorization_failed', $id, ['reason' => $reason]);
            throw new AdFailure($reason);
        }
    }
    public function sessionProfile(int $id, bool $sensitive = false, bool $recovery = false): array {
        $cfg = $this->settings(); $profile = $this->profile($id);
        $ttl = (int)($sensitive ? $cfg['sensitive_seconds'] : $cfg['refresh_seconds']);
        if (!$recovery && (!$cfg['enabled'] || $profile['authorization_status'] !== 'current'
            || (int)$profile['revision'] !== (int)$cfg['revision']
            || strtotime(($profile['refreshed_at'] ?? '1970-01-01') . ' UTC') + $ttl <= time())) {
            $this->limit('session_refresh', (string)$id, 6, 60);
            $profile = $this->refresh($id);
        }
        $this->applySession($profile);
        return $profile;
    }
    public function applySession(array $p, bool $new = false): void {
        if ($new) { $_SESSION = []; session_regenerate_id(true); }
        if ($p['disabled'] || !$p['verified_at'] || !$p['user_id']) throw new AdFailure('profile_unavailable');
        $_SESSION['ad_profile_id'] = (int)$p['id'];
        $state = json_decode($p['authorization_json'] ?? '{}', true);
        $modules = $p['authorization_status'] === 'current' ? ($state['modules'] ?? []) : [];
        unset($_SESSION['analyst_id'], $_SESSION['analyst_username'], $_SESSION['analyst_name'], $_SESSION['analyst_email'], $_SESSION['allowed_modules']);
        if ($p['user_id']) {
            $user = $this->query('SELECT * FROM users WHERE id = ?', [$p['user_id']])->fetch(PDO::FETCH_ASSOC);
            if (!$user) throw new AdFailure('profile_unavailable');
            $_SESSION['ss_user_id'] = (int)$user['id']; $_SESSION['ss_user_email'] = $user['email'];
            $_SESSION['ss_user_name'] = $user['preferred_name'] ?: $user['display_name'] ?: $user['email'];
        }
        if ($modules && $p['analyst_id']) {
            $a = $this->query('SELECT * FROM analysts WHERE id = ? AND is_active = 1', [$p['analyst_id']])->fetch(PDO::FETCH_ASSOC);
            if (!$a) throw new AdFailure('profile_unavailable');
            $_SESSION['analyst_id'] = (int)$a['id']; $_SESSION['analyst_username'] = $a['username'];
            $_SESSION['analyst_name'] = $a['full_name']; $_SESSION['analyst_email'] = $a['email']; $_SESSION['allowed_modules'] = $modules;
        }
    }
    /** Verification alone creates no requester, analyst or authenticated session. */
    public function requestVerification(string $input, callable $send): void {
        $this->limit('registration_ip', $_SERVER['REMOTE_ADDR'] ?? 'cli', 10, 900);
        try { $email = self::email($input); } catch (AdFailure $e) { return; }
        $this->limit('registration_email', $email, 3, 900);
        $cfg = $this->settings();
        $domains = json_decode($cfg['allowed_domains'], true);
        if (!$cfg['enabled'] || !in_array(explode('@', $email)[1], $domains, true)) return;
        if ($this->query('SELECT id FROM users WHERE LOWER(email) = ?', [$email])->fetchColumn() || $this->query('SELECT id FROM analysts WHERE LOWER(email) = ?', [$email])->fetchColumn() || $this->query('SELECT id FROM ad_profiles WHERE email = ? AND verified_at IS NOT NULL', [$email])->fetchColumn()) return;
        $token = bin2hex(random_bytes(32));
        $expires = gmdate('Y-m-d H:i:s', time() + (int)$cfg['verification_minutes'] * 60);
        $this->query('INSERT INTO ad_verifications (token_hash, email, expires_at) VALUES (?, ?, ?)', [hash('sha256', $token), $email, $expires]);
        try { $send($email, rtrim($cfg['base_url'], '/') . '/ad-login.php?verify=' . $token, $cfg); }
        catch (Throwable $e) { $this->query('DELETE FROM ad_verifications WHERE token_hash = ?', [hash('sha256', $token)]); $this->audit('verification_delivery_failed', null); }
    }
    public function verify(string $token): void {
        if (!preg_match('/^[a-f0-9]{64}$/D', $token)) throw new AdFailure('verification_invalid');
        try {
            $this->db->beginTransaction();
            $r = $this->query('SELECT * FROM ad_verifications WHERE token_hash = ? AND consumed_at IS NULL AND expires_at > UTC_TIMESTAMP() FOR UPDATE', [hash('sha256', $token)])->fetch(PDO::FETCH_ASSOC);
            if (!$r) throw new AdFailure('verification_invalid');
            $this->query('UPDATE ad_verifications SET consumed_at = UTC_TIMESTAMP() WHERE email = ? AND consumed_at IS NULL', [$r['email']]);
            if ($this->query('SELECT id FROM users WHERE LOWER(email) = ?', [$r['email']])->fetchColumn() || $this->query('SELECT id FROM analysts WHERE LOWER(email) = ?', [$r['email']])->fetchColumn()) throw new AdFailure('identity_review_required');
            $this->query('INSERT INTO ad_profiles (email, verified_at) VALUES (?, UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE verified_at = COALESCE(verified_at, UTC_TIMESTAMP())', [$r['email']]);
            $this->db->commit();
        } catch (Throwable $e) { if ($this->db->inTransaction()) $this->db->rollBack(); throw $e; }
    }
}
