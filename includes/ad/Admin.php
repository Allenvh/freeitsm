<?php
function adSaveConfig(PDO $db, array $input): void {
    $enabled = !empty($input['enabled']); $multi = !empty($input['multi_domain']);
    $default = !empty($input['default_provider_id']) ? (int)$input['default_provider_id'] : null;
    $authority = !empty($input['authority_provider_id']) ? (int)$input['authority_provider_id'] : null;
    $emergency = (int)($input['emergency_analyst_id'] ?? 1);
    $refresh = (int)($input['refresh_seconds'] ?? 900);
    $attempts = (int)($input['max_attempts'] ?? 1);
    $minutes = (int)($input['verification_minutes'] ?? 20);
    if ($refresh < 60 || $refresh > 3600 || $attempts < 1 || $attempts > 2 || $minutes < 15 || $minutes > 30) throw new InvalidArgumentException('Invalid refresh, attempt limit, or verification expiration');
    $domains = array_values(array_unique(array_map(static fn($d)=>strtolower(trim($d)), $input['allowed_email_domains'] ?? [])));
    foreach ($domains as $d) if (!preg_match('/^[a-z0-9](?:[a-z0-9.-]*[a-z0-9])?\.[a-z]{2,}$/D',$d)) throw new InvalidArgumentException('Invalid allowed email domain');
    $url = rtrim((string)($input['public_url'] ?? ''),'/');
    $parts=parse_url($url);
    if ($url !== '' && (!filter_var($url,FILTER_VALIDATE_URL) || ($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['query']) || isset($parts['fragment']))) throw new InvalidArgumentException('Public URL must be a fixed HTTPS deployment URL');
    $db->beginTransaction();
    try {
        $old=adQuery($db,'SELECT * FROM ad_config WHERE id=1 FOR UPDATE')->fetch(PDO::FETCH_ASSOC);
        $a=adQuery($db,'SELECT * FROM analysts WHERE id=? AND is_active=1 FOR UPDATE',[$emergency])->fetch(PDO::FETCH_ASSOC);
        if (!$a || $a['auth_provider_id'] || adQuery($db,'SELECT id FROM ad_profiles WHERE analyst_id=?',[$emergency])->fetchColumn()
            || getAnalystEffectiveModules($db,$emergency) !== null) throw new RuntimeException('Keep an active unrestricted local emergency administrator outside AD');
        $providers=adProviders($db);
        $valid=static function(?int $id, bool $auth) use ($providers): bool {
            foreach ($providers as $p) if ((int)$p['id']===$id && $p['enabled'] && (!$auth || $p['authentication_enabled'])) return true;
            return false;
        };
        if ($enabled && (!$default || !$valid($default,true))) throw new RuntimeException('Select an enabled default AD authentication provider');
        if ($authority && !$valid($authority,false)) throw new RuntimeException('Select an enabled authorization provider or leave it unset');
        // Revoke only AD-derived assignments on mode/authority transitions. Existing manual grants survive.
        if (!$enabled || (int)$old['authority_provider_id'] !== (int)$authority) {
            $db->exec("DELETE FROM analyst_access_groups WHERE source='ad'");
            $db->exec("UPDATE ad_profiles SET authorization_status='local',authorization_checked_at=NULL,authorization_revision=0,authority_guid=NULL,authority_provider_id=NULL,authorization_groups=NULL");
        }
        adQuery($db,'UPDATE ad_config SET enabled=?,multi_domain=?,default_provider_id=?,authority_provider_id=?,emergency_analyst_id=?,refresh_seconds=?,max_attempts=?,verification_minutes=?,allowed_email_domains=?,public_url=?,smtp_mailbox_id=?,revision=revision+1 WHERE id=1',
            [(int)$enabled,(int)$multi,$default,$authority,$emergency,$refresh,$multi?$attempts:1,$minutes,json_encode($domains),$url,!empty($input['smtp_mailbox_id'])?(int)$input['smtp_mailbox_id']:null]);
        adAudit($db,'configuration_changed',null,['enabled'=>$enabled,'multi_domain'=>$multi,'authority_provider_id'=>$authority]);
        $db->commit();
    } catch (Throwable $e) { if ($db->inTransaction()) $db->rollBack(); throw $e; }
}
function adSaveProvider(PDO $db, array $in): int {
    $c=adValidateProvider($in['config'] ?? []);
    $id=(int)($in['id'] ?? 0); $name=trim((string)($in['display_name'] ?? ''));
    if ($name==='' || strlen($name)>100) throw new InvalidArgumentException('Provider name required (maximum 100 characters)');
    $db->beginTransaction();
    try {
        $cfg=adQuery($db,'SELECT * FROM ad_config WHERE id=1 FOR UPDATE')->fetch(PDO::FETCH_ASSOC);
        if ($id) {
            $old=adQuery($db,"SELECT p.id,c.bind_password FROM auth_providers p JOIN ad_provider_config c ON c.provider_id=p.id WHERE p.id=? AND p.protocol='ad' FOR UPDATE",[$id])->fetch(PDO::FETCH_ASSOC);
            if (!$old) throw new RuntimeException('AD provider not found');
            if ((int)$cfg['authority_provider_id']===$id && empty($in['enabled'])) throw new RuntimeException('Clear the authorization authority before disabling its provider');
            if ($cfg['enabled'] && (int)$cfg['default_provider_id']===$id && (empty($in['enabled']) || empty($in['authentication_enabled']))) throw new RuntimeException('Change the default provider before disabling it');
            adQuery($db,'UPDATE auth_providers SET display_name=?,enabled=?,sort_order=? WHERE id=?',[$name,(int)!empty($in['enabled']),(int)($in['sort_order'] ?? 0),$id]);
        } else {
            adQuery($db,"INSERT INTO auth_providers (display_name,protocol,issuer_url,client_id,enabled,sort_order) VALUES (?,'ad','','',?,?)",[$name,(int)!empty($in['enabled']),(int)($in['sort_order'] ?? 0)]);
            $id=(int)$db->lastInsertId();
        }
        $password=(string)($in['bind_password'] ?? '');
        if (str_starts_with($password,ENCRYPTION_PREFIX)) throw new InvalidArgumentException('Service password cannot use the reserved encryption prefix');
        $encrypted=$password!=='' ? encryptValue($password) : ($old['bind_password'] ?? null);
        if (!$encrypted) throw new RuntimeException('Read-only search service password required');
        adQuery($db,'INSERT INTO ad_provider_config (provider_id,authentication_enabled,trusted_linking,config_json,bind_password) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE authentication_enabled=VALUES(authentication_enabled),trusted_linking=VALUES(trusted_linking),config_json=VALUES(config_json),bind_password=VALUES(bind_password)',
            [$id,(int)!empty($in['authentication_enabled']),(int)!empty($in['trusted_linking']),json_encode($c),$encrypted]);
        $db->exec('UPDATE ad_config SET revision=revision+1 WHERE id=1');
        adAudit($db,'provider_saved',null,['provider_id'=>$id]);
        $db->commit(); return $id;
    } catch (Throwable $e) { if ($db->inTransaction()) $db->rollBack(); throw $e; }
}
/** Administrator-controlled reconciliation of existing records; no merging by email alone. */
function adLinkExisting(PDO $db, array $in, AdDirectory $directory): void {
    $uid=(int)($in['user_id'] ?? 0); $aid=!empty($in['analyst_id'])?(int)$in['analyst_id']:null;
    $pid=(int)($in['provider_id'] ?? 0); $guid=strtolower((string)($in['guid'] ?? ''));
    if (!preg_match('/^[a-f0-9]{32}$/D',$guid)) throw new InvalidArgumentException('Use the 32-character raw objectGUID hex shown by the directory lookup');
    $db->beginTransaction();
    try {
        $cfg=adQuery($db,'SELECT * FROM ad_config WHERE id=1 FOR UPDATE')->fetch(PDO::FETCH_ASSOC);
        if ($aid && $aid===(int)$cfg['emergency_analyst_id']) throw new RuntimeException('Emergency administrator cannot be linked to AD');
        $user=adQuery($db,'SELECT * FROM users WHERE id=? FOR UPDATE',[$uid])->fetch(PDO::FETCH_ASSOC);
        if (!$user) throw new RuntimeException('Existing employee user required');
        if ($aid) {
            $analyst=adQuery($db,'SELECT * FROM analysts WHERE id=? AND is_active=1',[$aid])->fetch(PDO::FETCH_ASSOC);
            if (!$analyst || adNormalizeEmail($analyst['email'])!==adNormalizeEmail($user['email'])) throw new RuntimeException('Analyst email mismatch');
        }
        $provider=null;
        foreach (adProviders($db) as $p) if ((int)$p['id']===$pid && $p['enabled']) $provider=$p;
        if (!$provider) throw new RuntimeException('Enabled trusted provider required');
        $identity=$directory->find($provider,adNormalizeEmail($user['email']));
        if (!$identity || $identity['guid']!==$guid || $identity['disabled']) throw new RuntimeException('Directory identity mismatch');
        $profile=adQuery($db,'SELECT * FROM ad_profiles WHERE user_id=?',[$uid])->fetch(PDO::FETCH_ASSOC);
        if (!$profile) {
            adQuery($db,'INSERT INTO ad_profiles (user_id,analyst_id,verified_at) VALUES (?,?,UTC_TIMESTAMP())',[$uid,$aid]);
            $profile=['id'=>(int)$db->lastInsertId()];
        } elseif ($aid) {
            if ($profile['analyst_id'] && (int)$profile['analyst_id']!==$aid) throw new RuntimeException('Profile already has a different analyst');
            adQuery($db,'UPDATE ad_profiles SET analyst_id=? WHERE id=?',[$aid,$profile['id']]);
        }
        $previous=adQuery($db,'SELECT object_guid FROM ad_identities WHERE profile_id=? AND provider_id=? FOR UPDATE',[$profile['id'],$pid])->fetchColumn();
        if ($previous) {
            if ($previous!==$guid && !hash_equals($previous,strtolower((string)($in['previous_guid'] ?? '')))) throw new RuntimeException('Replacement requires explicit confirmation of the previous linked GUID');
            adQuery($db,'UPDATE ad_identities SET object_guid=?,directory_email=? WHERE profile_id=? AND provider_id=?',[$guid,adNormalizeEmail($user['email']),$profile['id'],$pid]);
            if ($previous!==$guid) {
                adQuery($db,'UPDATE ad_profiles SET authority_guid=NULL,authorization_revision=0,authorization_checked_at=NULL WHERE id=? AND authority_provider_id=?',[$profile['id'],$pid]);
                adQuery($db,"DELETE FROM analyst_access_groups WHERE analyst_id=(SELECT analyst_id FROM ad_profiles WHERE id=?) AND source='ad'",[$profile['id']]);
                adAudit($db,'identity_replaced',(int)$profile['id'],['provider_id'=>$pid,'previous_guid'=>$previous,'new_guid'=>$guid]);
            }
        } else {
            adQuery($db,'INSERT INTO ad_identities (provider_id,object_guid,profile_id,directory_email) VALUES (?,?,?,?)',[$pid,$guid,$profile['id'],adNormalizeEmail($user['email'])]);
        }
        adAudit($db,'identity_reviewed',(int)$profile['id'],['provider_id'=>$pid,'guid'=>$guid,'user_id'=>$uid,'analyst_id'=>$aid]);
        $db->commit();
    } catch (Throwable $e) { if ($db->inTransaction()) $db->rollBack(); throw $e; }
}
