<?php
require_once __DIR__ . '/bootstrap.php';
final class AdAdmin {
    public function __construct(private AdService $s) {}
    private function integer(array $data, string $key, int $min, int $max, int $default): int {
        $v = filter_var($data[$key] ?? $default, FILTER_VALIDATE_INT);
        if ($v === false || $v < $min || $v > $max) throw new AdFailure('invalid_' . $key);
        return $v;
    }
    public function saveProvider(array $d): void {
        $id = (int)($d['id'] ?? 0); $old = null;
        foreach ($this->s->providers() as $p) if ((int)$p['id'] === $id) $old = $p;
        if ($id && !$old) throw new AdFailure('provider_missing');
        $name = trim($d['name'] ?? '');
        if ($name === '' || strlen($name) > 120) throw new AdFailure('invalid_name');
        $c = [];
        foreach (['domain_dns', 'domain_suffix', 'primary', 'secondary'] as $key) {
            $c[$key] = strtolower(trim($d[$key] ?? ''));
            if ($c[$key] === '' && $key !== 'secondary') throw new AdFailure('invalid_' . $key);
            if ($c[$key] !== '' && !filter_var($c[$key], FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) throw new AdFailure('invalid_' . $key);
        }
        $c['transport'] = $d['transport'] ?? 'ldaps';
        if (!in_array($c['transport'], ['ldaps', 'starttls'], true)) throw new AdFailure('encrypted_ldap_required');
        $c['port'] = $this->integer($d, 'port', 1, 65535, $c['transport'] === 'ldaps' ? 636 : 389);
        $c['timeout'] = $this->integer($d, 'timeout', 1, 15, 5);
        foreach (['base_dn', 'user_base', 'group_base', 'bind_identity', 'ca_file'] as $key) $c[$key] = trim($d[$key] ?? '');
        if (!$c['base_dn'] || !$c['bind_identity']) throw new AdFailure('directory_configuration_required');
        if ($c['ca_file'] && (!str_starts_with($c['ca_file'], '/') || !is_readable($c['ca_file']))) throw new AdFailure('tls_ca_unreadable');
        foreach (['username_attribute' => 'sAMAccountName', 'email_attribute' => 'mail', 'display_attribute' => 'displayName'] as $key => $default) {
            $c[$key] = trim($d[$key] ?? $default);
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9-]*$/D', $c[$key])) throw new AdFailure('invalid_attribute');
        }
        $c['nested_groups'] = !empty($d['nested_groups']);
        $password = (string)($d['service_password'] ?? '');
        $encrypted = $password !== '' ? encryptValue($password) : ($old['password_encrypted'] ?? '');
        if (!$encrypted || !str_starts_with($encrypted, ENCRYPTION_PREFIX)) throw new AdFailure('encrypted_service_password_required');
        // Established identity namespaces cannot be pointed at an unrelated directory.
        if ($old && $this->s->query('SELECT 1 FROM ad_identities WHERE provider_id = ? LIMIT 1', [$id])->fetchColumn()) {
            foreach (['domain_dns', 'domain_suffix', 'base_dn', 'email_attribute'] as $key) if ($c[$key] !== $old['config'][$key]) throw new AdFailure('create_new_provider_for_identity_namespace_change');
        }
        $enabled = !empty($d['enabled']); $trusted = !empty($d['trusted']); $auth = !empty($d['authentication_enabled']);
        $cfg = $this->s->settings();
        if ($cfg['enabled'] && in_array($id, [(int)$cfg['authority_id'], (int)$cfg['default_provider_id']], true) && (!$enabled || !$trusted || ((int)$cfg['default_provider_id'] === $id && !$auth))) throw new AdFailure('reassign_active_authority_or_default_first');
        $values = [$name, (int)$enabled, (int)$trusted, (int)$auth, $this->integer($d, 'priority', 0, 1000, 100), json_encode($c), $encrypted];
        if ($id) $this->s->query('UPDATE ad_providers SET name=?, enabled=?, trusted=?, authentication_enabled=?, priority=?, config_json=?, password_encrypted=? WHERE id=?', [...$values, $id]);
        else { $this->s->query('INSERT INTO ad_providers (name, enabled, trusted, authentication_enabled, priority, config_json, password_encrypted) VALUES (?, ?, ?, ?, ?, ?, ?)', $values); $id = (int)$this->s->db->lastInsertId(); }
        $this->s->query('UPDATE ad_settings SET revision=revision+1 WHERE id=1');
        $this->s->audit('provider_saved', null, ['provider_id' => $id]);
    }
    public function saveSettings(array $d): void {
        $enabled = !empty($d['enabled']); $multi = !empty($d['multi_domain']);
        $authority = (int)($d['authority_id'] ?? 0); $default = (int)($d['default_provider_id'] ?? 0);
        $emergency = (int)($d['emergency_analyst_id'] ?? 0);
        $domains = array_values(array_unique(array_filter(array_map(fn($v) => strtolower(trim($v)), explode(',', $d['allowed_domains'] ?? '')))));
        foreach ($domains as $domain) if (!filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) || !str_contains($domain, '.')) throw new AdFailure('invalid_email_domain');
        $base = rtrim(trim($d['base_url'] ?? ''), '/');
        if ($base !== '' && (!filter_var($base, FILTER_VALIDATE_URL) || parse_url($base, PHP_URL_SCHEME) !== 'https' || parse_url($base, PHP_URL_QUERY) || parse_url($base, PHP_URL_FRAGMENT) || parse_url($base, PHP_URL_USER))) throw new AdFailure('https_public_base_url_required');
        $mailbox = (int)($d['mailbox_id'] ?? 0);
        if ($enabled) {
            $providers = array_column($this->s->providers(), null, 'id');
            foreach ([$authority, $default] as $id) if (empty($providers[$id]['enabled']) || empty($providers[$id]['trusted'])) throw new AdFailure('select_enabled_trusted_providers');
            if (!$providers[$default]['authentication_enabled']) throw new AdFailure('default_must_authenticate');
            if (!$multi && $authority !== $default) throw new AdFailure('single_domain_uses_same_authority');
            if (!$domains || !$base || !$mailbox) throw new AdFailure('registration_configuration_required');
            require_once __DIR__ . '/../mail/SmtpMailer.php';
            $m = $this->s->query('SELECT * FROM target_mailboxes WHERE id = ?', [$mailbox])->fetch(PDO::FETCH_ASSOC);
            if (!$m) throw new AdFailure('smtp_mailbox_required');
            SmtpMailer::assertSendReady(decryptMailboxRow($m));
            if (!in_array($m['smtp_encryption'] ?? '', ['tls', 'ssl'], true)) throw new AdFailure('encrypted_smtp_required');
            $a = $this->s->query('SELECT * FROM analysts WHERE id = ? AND is_active = 1 AND auth_provider_id IS NULL', [$emergency])->fetch(PDO::FETCH_ASSOC);
            if ($a && !empty($a['locked_until']) && strtotime($a['locked_until'] . ' UTC') > time()) throw new AdFailure('emergency_administrator_locked');
            if (!$a || !password_verify((string)($d['emergency_password'] ?? ''), $a['password_hash'])) throw new AdFailure('verify_emergency_local_administrator_password');
            if ($this->s->query('SELECT 1 FROM ad_profiles WHERE analyst_id = ?', [$emergency])->fetchColumn()) throw new AdFailure('emergency_must_remain_local');
            // The existing unrestricted module convention identifies a local emergency administrator.
            if (getAnalystAllowedModules($this->s->db, $emergency) !== null) throw new AdFailure('emergency_must_have_full_access');
        }
        $this->s->query('UPDATE ad_settings SET enabled=?, multi_domain=?, allow_fallback=?, max_attempts=?, authority_id=?, default_provider_id=?, allowed_domains=?, base_url=?, mailbox_id=?, verification_minutes=?, refresh_seconds=?, sensitive_seconds=?, emergency_analyst_id=?, revision=revision+1 WHERE id=1',
            [(int)$enabled, (int)$multi, (int)($multi && !empty($d['allow_fallback'])), $multi ? $this->integer($d, 'max_attempts', 1, 2, 1) : 1,
                $authority ?: null, $default ?: null, json_encode($domains), $base, $mailbox ?: null, $this->integer($d, 'verification_minutes', 5, 30, 20),
                $this->integer($d, 'refresh_seconds', 60, 3600, 900), $this->integer($d, 'sensitive_seconds', 0, 300, 60), $emergency ?: null]);
        $this->s->audit('settings_saved', null, ['enabled' => $enabled, 'multi_domain' => $multi, 'authority_id' => $authority]);
    }
    public function mapping(array $d): void {
        $provider = (int)$d['provider_id']; $guid = strtolower(trim($d['group_guid'] ?? ''));
        if ($provider !== (int)$this->s->settings()['authority_id']) throw new AdFailure('mappings_require_authority');
        if (!preg_match('/^[a-f0-9]{32}$/D', $guid)) throw new AdFailure('group_guid_requires_32_hex_digits');
        $group = (int)($d['access_group_id'] ?? 0);
        $this->s->query('INSERT INTO ad_group_mappings (provider_id, group_guid, group_name, access_group_id, precedence) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE group_name=VALUES(group_name), access_group_id=VALUES(access_group_id), precedence=VALUES(precedence)',
            [$provider, $guid, substr(trim($d['group_name'] ?? ''), 0, 255), $group, $this->integer($d, 'precedence', 0, 1000, 100)]);
        $this->s->query('UPDATE ad_settings SET revision=revision+1 WHERE id=1');
        $this->s->audit('group_mapping_saved', null, ['provider_id' => $provider, 'group_guid' => $guid, 'access_group_id' => $group]);
    }
    /** Explicit review is required to adopt legacy identities; never automatic email merging. */
    public function reconcile(array $d): void {
        $id = (int)$d['profile_id']; $email = AdService::email($d['email'] ?? '');
        $uid = (int)($d['user_id'] ?? 0); $aid = (int)($d['analyst_id'] ?? 0);
        $cfg = $this->s->settings();
        if ($aid && $aid === (int)$cfg['emergency_analyst_id']) throw new AdFailure('emergency_must_remain_local');
        if (trim($d['review_reason'] ?? '') === '') throw new AdFailure('review_reason_required');
        $this->s->db->beginTransaction();
        try {
            $p = $this->s->profile($id, true);
            if (($p['user_id'] && (int)$p['user_id'] !== $uid) || ($p['analyst_id'] && (int)$p['analyst_id'] !== $aid)) throw new AdFailure('existing_internal_ids_are_immutable');
            foreach (['users' => $uid, 'analysts' => $aid] as $table => $internal) {
                if (!$internal) continue;
                $r = $this->s->query("SELECT email, auth_provider_id FROM $table WHERE id=? FOR UPDATE", [$internal])->fetch(PDO::FETCH_ASSOC);
                if (!$r || $r['auth_provider_id']) throw new AdFailure('existing_oidc_assignment_must_be_reviewed_separately');
                $matches = $this->s->query("SELECT id FROM $table WHERE LOWER(email)=? AND id<>?", [$email, $internal])->fetchAll();
                if ($matches) throw new AdFailure('duplicate_identity');
            }
            // Reconcile only against all established immutable identities, never replace GUIDs.
            $links = $this->s->query('SELECT * FROM ad_identities WHERE profile_id=?', [$id])->fetchAll(PDO::FETCH_ASSOC);
            foreach ($links as $link) {
                $provider = array_column($this->s->providers(), null, 'id')[$link['provider_id']] ?? null;
                if (!$provider || !$provider['enabled'] || !$provider['trusted']) throw new AdFailure('linked_provider_unavailable');
                $account = (new LdapAdDirectory())->find($provider, $email);
                if (!$account || $account['guid'] !== $link['object_guid']) throw new AdFailure('immutable_identity_mismatch');
            }
            $this->s->query('UPDATE ad_profiles SET email=?, user_id=?, analyst_id=?, verified_at=UTC_TIMESTAMP(), authorization_status=\'reviewed\', revision=0 WHERE id=?', [$email, $uid ?: null, $aid ?: null, $id]);
            $this->s->query('UPDATE ad_identities SET directory_email=? WHERE profile_id=?', [$email, $id]);
            if ($uid) $this->s->query('UPDATE users SET email=? WHERE id=?', [$email, $uid]);
            if ($aid) $this->s->query('UPDATE analysts SET email=? WHERE id=?', [$email, $aid]);
            $this->s->audit('identity_reconciled', $id, ['user_id' => $uid, 'analyst_id' => $aid, 'reason' => substr($d['review_reason'], 0, 500)]);
            $this->s->db->commit();
        } catch (Throwable $e) { if ($this->s->db->inTransaction()) $this->s->db->rollBack(); throw $e; }
    }
}
