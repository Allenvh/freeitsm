<?php
/** A directory adapter is injectable for tests; production always uses verified LDAP TLS. */
interface AdDirectory {
    public function find(array $provider, string $email): ?array;
    public function authenticate(array $provider, array $identity, string $password): bool;
    public function groups(array $provider, array $identity): array;
}

/** Only AD's invalid-credential status allows a bounded attempt against another domain. */
function adInvalidPasswordDiagnostic(string $diagnostic): bool {
    return preg_match('/\bdata\s+52e\b/i', $diagnostic) === 1;
}

final class AdLdapDirectory implements AdDirectory {
    private function connection(array $p, bool $service = true) {
        if (!extension_loaded('ldap')) throw new RuntimeException('LDAP extension unavailable');
        $c = $p['config'];
        foreach (array_filter([$c['host'], $c['secondary_host'] ?? '']) as $host) {
            $ldap = ldap_connect(($c['transport'] === 'ldaps' ? 'ldaps' : 'ldap') . '://' . $host . ':' . $c['port']);
            if (!$ldap) continue;
            $options = [LDAP_OPT_PROTOCOL_VERSION=>3, LDAP_OPT_REFERRALS=>0,
                LDAP_OPT_NETWORK_TIMEOUT=>$c['timeout'], LDAP_OPT_TIMEOUT=>$c['timeout'],
                LDAP_OPT_X_TLS_REQUIRE_CERT=>LDAP_OPT_X_TLS_DEMAND];
            if (!empty($c['ca_file'])) $options[LDAP_OPT_X_TLS_CACERTFILE]=$c['ca_file'];
            if (defined('LDAP_OPT_X_TLS_NEWCTX')) $options[LDAP_OPT_X_TLS_NEWCTX]=0;
            foreach ($options as $option=>$value) {
                if (!ldap_set_option($ldap,$option,$value)) {
                    ldap_unbind($ldap);
                    throw new RuntimeException('Cannot apply secure LDAP configuration');
                }
            }
            if ($c['transport'] === 'starttls' && !@ldap_start_tls($ldap)) {
                ldap_unbind($ldap);
                continue;
            }
            if ($service && !@ldap_bind($ldap, $c['bind_dn'], $p['bind_password'])) {
                ldap_unbind($ldap);
                continue;
            }
            return $ldap;
        }
        throw new RuntimeException('Directory connection unavailable');
    }

    private function search($ldap, string $base, string $filter, array $attributes): array {
        $r = @ldap_search($ldap, $base, $filter, $attributes, 0, 1000, 10);
        if (!$r) throw new RuntimeException('Directory search failed');
        // Reject truncated/partial results rather than interpreting them as absence.
        if (!ldap_parse_result($ldap, $r, $code) || $code !== 0) throw new RuntimeException('Incomplete directory search');
        $rows = ldap_get_entries($ldap, $r);
        if ($rows === false) throw new RuntimeException('Invalid directory response');
        $out = [];
        for ($i = 0; $i < $rows['count']; $i++) $out[] = $rows[$i];
        return $out;
    }

    public function find(array $p, string $email): ?array {
        $c = $p['config']; $ldap = $this->connection($p);
        try {
            $rows = $this->search($ldap, $c['user_base'], '(&(objectCategory=person)(objectClass=user)(' . $c['email_attribute'] . '=' . ldap_escape($email, '', LDAP_ESCAPE_FILTER) . '))',
                ['objectGUID', $c['email_attribute'], $c['name_attribute'], 'userAccountControl', 'lockoutTime', 'msDS-User-Account-Control-Computed', 'pwdLastSet', 'objectSid', 'primaryGroupID']);
            if (count($rows) > 1) throw new RuntimeException('Ambiguous directory identity');
            if (!$rows) return null;
            $r = $rows[0];
            if (strlen($r['objectguid'][0] ?? '') !== 16) throw new RuntimeException('Missing immutable directory identity');
            $emails = $r[strtolower($c['email_attribute'])] ?? [];
            if (($emails['count'] ?? 0) !== 1 || adNormalizeEmail($emails[0]) !== $email) throw new RuntimeException('Ambiguous directory email');
            $flags = (int)($r['useraccountcontrol'][0] ?? 2);
            $computed = (int)($r['msds-user-account-control-computed'][0] ?? 0);
            return ['guid' => bin2hex($r['objectguid'][0]), 'email' => $email, 'dn' => $r['dn'],
                'name' => $r[strtolower($c['name_attribute'])][0] ?? $email,
                'primary_sid' => isset($r['objectsid'][0], $r['primarygroupid'][0]) ? bin2hex(substr($r['objectsid'][0],0,-4) . pack('V',(int)$r['primarygroupid'][0])) : null,
                'disabled' => (bool)($flags & 2), 'locked' => (bool)($computed & 16),
                'expired' => (bool)($computed & 8388608) || (isset($r['pwdlastset'][0]) && $r['pwdlastset'][0] === '0')];
        } finally { ldap_unbind($ldap); }
    }

    public function authenticate(array $p, array $identity, string $password): bool {
        if ($password === '' || $identity['disabled'] || $identity['locked'] || $identity['expired']) return false;
        $ldap = $this->connection($p, false);
        try {
            if (@ldap_bind($ldap, $identity['dn'], $password)) return true;
            // Only invalid credentials permits controlled fallback. Outages do not.
            ldap_get_option($ldap, LDAP_OPT_DIAGNOSTIC_MESSAGE, $diagnostic);
            if (ldap_errno($ldap) === 49 && adInvalidPasswordDiagnostic((string)$diagnostic)) return false;
            throw new RuntimeException('Directory rejected sign-in or bind unavailable');
        } finally { ldap_unbind($ldap); }
    }

    public function groups(array $p, array $identity): array {
        $ldap = $this->connection($p); $c = $p['config'];
        try {
            $rule = !empty($c['nested_groups']) ? ':1.2.840.113556.1.4.1941:' : '';
            $members = '(member' . $rule . '=' . ldap_escape($identity['dn'], '', LDAP_ESCAPE_FILTER) . ')';
            // AD does not expose primary-group membership through member/memberOf.
            if (!empty($identity['primary_sid'])) {
                $sid = ldap_escape(hex2bin($identity['primary_sid']), '', LDAP_ESCAPE_FILTER);
                $primary = $this->search($ldap, $c['group_base'], '(&(objectCategory=group)(objectSid=' . $sid . '))', ['distinguishedName']);
                if (count($primary) !== 1) throw new RuntimeException('Primary group lookup incomplete');
                $members .= '(objectSid=' . $sid . ')';
                if (!empty($c['nested_groups'])) $members .= '(member' . $rule . '=' . ldap_escape($primary[0]['dn'], '', LDAP_ESCAPE_FILTER) . ')';
            }
            $filter = '(&(objectCategory=group)(groupType:1.2.840.113556.1.4.803:=2147483648)(|' . $members . '))';
            return array_map(static function ($r) {
                if (strlen($r['objectguid'][0] ?? '') !== 16) throw new RuntimeException('Missing group identity');
                return bin2hex($r['objectguid'][0]);
            }, $this->search($ldap, $c['group_base'], $filter, ['objectGUID']));
        } finally { ldap_unbind($ldap); }
    }
}
