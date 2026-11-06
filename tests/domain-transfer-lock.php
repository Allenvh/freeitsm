<?php
/* 🔴 NEVER OVER THE WEB - see tests/README.md. This one touches no database, but
   every test file refuses the web the same way so none is ever the exception. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Domains: what a registry's statuses say about the transfer lock (GH #188).
 *
 * ON only on a transfer-prohibited status; OFF only with evidence the registry
 * would have shown one (a generic TLD, or another prohibition in the reply);
 * otherwise NOT KNOWN. The .uk "not applicable" rule and DENIC's "connect" are
 * unchanged. No network, no database: domainDeriveFlags() on made-up replies.
 *
 * Run:  php tests/domain-transfer-lock.php
 */

require_once __DIR__ . '/../includes/domains/lookup.php';

$pass = 0; $fail = 0;
function is_(string $what, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { $pass++; printf("  PASS  %-66s %s\n", $what, var_export($got, true)); }
    else { $fail++; printf("  FAIL  %-66s got %s, wanted %s\n", $what, var_export($got, true), var_export($want, true)); }
}
/** The transfer_lock a set of raw statuses produces for a domain. */
function lock(string $name, array $raw) {
    $r = domainBlankResult('test');
    $r['statuses'] = domainNormaliseStatuses($raw);
    domainDeriveFlags($r, $name);
    return $r['transfer_lock'];
}

echo "\nDomains: transfer lock from registry statuses (#188)\n" . str_repeat('=', 78) . "\n";

echo "\nThe reported case - a country registry answering just \"active\":\n";
is_('.id, RDAP "active" only -> not known', lock('example.co.id', ['active']), null);
is_('.au, RDAP "active" only -> not known', lock('example.com.au', ['active']), null);
is_('.id, WHOIS "ok" only -> not known', lock('example.id', ['ok']), null);

echo "\nOn - a transfer-prohibited status, any TLD:\n";
is_('.com, client transfer prohibited', lock('example.com', ['client transfer prohibited']), true);
is_('.id, clientTransferProhibited (WHOIS form)', lock('example.co.id', ['clientTransferProhibited https://icann.org/epp#clientTransferProhibited']), true);
is_('.com, server transfer prohibited', lock('example.com', ['server transfer prohibited', 'active']), true);

echo "\nOff - only with evidence:\n";
is_('.com "active" only: ICANN registries publish every status', lock('example.com', ['active']), false);
is_('.app (new gTLD) "active" only', lock('example.app', ['active']), false);
is_('.id with delete prohibited but no transfer one', lock('example.co.id', ['active', 'client delete prohibited']), false);

echo "\nUnchanged behaviour:\n";
is_('.co.uk "active" only -> not applicable (null)', lock('example.co.uk', ['active']), null);
is_('.co.uk with client transfer prohibited -> on', lock('example.co.uk', ['client transfer prohibited']), true);
is_('.de DENIC "connect" -> not known', lock('example.de', ['connect']), null);
is_('no statuses at all -> not known', lock('example.com', []), null);
is_('IDN TLD "active" only -> not known (could be a ccTLD)', lock('example.xn--p1ai', ['active']), null);
is_('no name given, "active" only -> not known', lock('', ['active']), null);

echo "\n" . str_repeat('-', 78) . "\n  $pass passed, $fail failed\n\n";
exit($fail ? 1 : 0);
