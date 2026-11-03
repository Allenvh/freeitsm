<?php
/** Encrypted LDAP only. Never follows referrals or falls back to plaintext. */
interface AdDirectory {
    public function find(array $provider, string $email): ?array;
    public function authenticate(array $provider, array $account, string $password): bool;
    public function groups(array $provider, array $account): array;
}
class AdFailure extends RuntimeException {
    public function __construct(public readonly string $reason) { parent::__construct($reason); }
}
final class LdapAdDirectory implements AdDirectory {
    private function option($ldap, int $option, mixed $value): void {
        if (!ldap_set_option($ldap, $option, $value)) throw new AdFailure('ldap_option_failed');
    }
    private function connection(array $p) {
        if (!extension_loaded('ldap')) throw new AdFailure('ldap_extension_missing');
        $c = $p['config'];
        $last = 'directory_unavailable';
        foreach (array_filter([$c['primary'], $c['secondary'] ?? '']) as $host) {
            $ldap = null;
            try {
                // LDAP TLS options are connection-local. No process-wide CA changes.
                $ldap = ldap_connect(($c['transport'] === 'ldaps' ? 'ldaps://' : 'ldap://') . $host . ':' . $c['port']);
                if (!$ldap) throw new AdFailure('directory_unavailable');
                $this->option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                $this->option($ldap, LDAP_OPT_REFERRALS, 0);
                $this->option($ldap, LDAP_OPT_NETWORK_TIMEOUT, $c['timeout']);
                $this->option($ldap, LDAP_OPT_TIMELIMIT, $c['timeout']);
                $this->option($ldap, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_DEMAND);
                if (!empty($c['ca_file'])) {
                    if (!is_readable($c['ca_file'])) throw new AdFailure('tls_ca_unreadable');
                    $this->option($ldap, LDAP_OPT_X_TLS_CACERTFILE, $c['ca_file']);
                }
                if (defined('LDAP_OPT_X_TLS_NEWCTX')) $this->option($ldap, LDAP_OPT_X_TLS_NEWCTX, 0);
                if ($c['transport'] === 'starttls' && !@ldap_start_tls($ldap)) throw new AdFailure('tls_failure');
                if (!@ldap_bind($ldap, $c['bind_identity'], decryptValue($p['password_encrypted']))) {
                    $code = ldap_errno($ldap);
                    throw new AdFailure($code === 49 ? 'service_bind_failed' : ($code === -1 ? 'tls_or_connection_failure' : 'directory_unavailable'));
                }
                return $ldap;
            } catch (Throwable $e) {
                if ($ldap) @ldap_unbind($ldap);
                $last = $e instanceof AdFailure ? $e->reason : 'directory_unavailable';
                // Retrying the same service password after invalid credentials amplifies lockouts.
                if ($last === 'service_bind_failed') break;
            }
        }
        throw new AdFailure($last);
    }
    private function search($ldap, string $base, string $filter, array $attributes, int $limit = 0): array {
        $result = @ldap_search($ldap, $base, $filter, $attributes, 0, $limit, 0);
        if (!$result) throw new AdFailure('search_failed');
        ldap_parse_result($ldap, $result, $code);
        if ($code !== 0) throw new AdFailure('partial_directory_result');
        $entries = ldap_get_entries($ldap, $result);
        $out = [];
        for ($i = 0; $i < $entries['count']; $i++) $out[] = $entries[$i];
        return $out;
    }
    public function find(array $p, string $email): ?array {
        $ldap = $this->connection($p); $c = $p['config'];
        try {
            $rows = $this->search($ldap, $c['user_base'] ?: $c['base_dn'],
                '(&(objectCategory=person)(objectClass=user)(' . $c['email_attribute'] . '=' . ldap_escape($email, '', LDAP_ESCAPE_FILTER) . '))',
                ['objectGUID', $c['email_attribute'], $c['username_attribute'], $c['display_attribute'], 'userAccountControl', 'lockoutTime', 'msDS-User-Account-Control-Computed', 'pwdLastSet', 'accountExpires'], 2);
            if (count($rows) > 1) throw new AdFailure('ambiguous_identity');
            if (!$rows) return null;
            $r = $rows[0];
            $value = fn($key) => $r[strtolower($key)][0] ?? '';
            if (($r[strtolower($c['email_attribute'])]['count'] ?? 0) !== 1 || AdService::email($value($c['email_attribute'])) !== $email || strlen($value('objectGUID')) !== 16) throw new AdFailure('ambiguous_identity');
            if ($value($c['username_attribute']) === '' || $value('userAccountControl') === '') throw new AdFailure('partial_directory_result');
            $flags = (int)$value('userAccountControl') | (int)$value('msDS-User-Account-Control-Computed');
            $expiry = (int)$value('accountExpires');
            $disabled = ($flags & 2) !== 0 || ($expiry > 0 && $expiry < PHP_INT_MAX && $expiry / 10000000 - 11644473600 < time());
            return ['guid' => bin2hex($value('objectGUID')), 'email' => $email, 'dn' => $r['dn'], 'username' => $value($c['username_attribute']), 'name' => $value($c['display_attribute']), 'disabled' => $disabled,
                'locked' => ($flags & 16) !== 0, 'expired' => ($flags & 8388608) !== 0 || $value('pwdLastSet') === '0'];
        } finally { ldap_unbind($ldap); }
    }
    public function authenticate(array $p, array $account, string $password): bool {
        if ($password === '' || $account['disabled'] || $account['locked'] || $account['expired']) return false;
        $ldap = $this->connection($p);
        try {
            // One user bind only: never retry credentials on another DC following rejection.
            if (@ldap_bind($ldap, $account['dn'], $password)) return true;
            $code = ldap_errno($ldap);
            if ($code !== 49) throw new AdFailure('user_bind_unavailable');
            ldap_get_option($ldap, LDAP_OPT_DIAGNOSTIC_MESSAGE, $diagnostic);
            if (preg_match('/data\s+(532|533|701|773|775)\b/i', (string)$diagnostic)) throw new AdFailure('account_unavailable');
            return false;
        } finally { ldap_unbind($ldap); }
    }
    public function groups(array $p, array $account): array {
        $ldap = $this->connection($p); $c = $p['config'];
        try {
            $membership = $c['nested_groups'] ? 'member:1.2.840.113556.1.4.1941:' : 'member';
            $rows = $this->search($ldap, $c['group_base'] ?: $c['base_dn'],
                '(&(objectCategory=group)(groupType:1.2.840.113556.1.4.803:=2147483648)(' . $membership . '=' . ldap_escape($account['dn'], '', LDAP_ESCAPE_FILTER) . '))', ['objectGUID', 'cn']);
            $groups = [];
            foreach ($rows as $r) {
                if (strlen($r['objectguid'][0] ?? '') !== 16) throw new AdFailure('partial_group_resolution');
                $groups[] = ['guid' => bin2hex($r['objectguid'][0]), 'name' => $r['cn'][0] ?? ''];
            }
            return $groups;
        } finally { ldap_unbind($ldap); }
    }
}
