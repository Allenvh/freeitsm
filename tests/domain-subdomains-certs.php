<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Domains: subdomains (a domain linked to its parent) and certificates recorded
 * by hand - the rules in DomainsService, includes/domains/subdomains.php, the
 * checks, the alerts and the calendar.
 *
 *   php tests/domain-subdomains-certs.php
 *
 * Part 1 runs inside a transaction that is ALWAYS rolled back (the
 * domain-parties.php pattern). Part 2 runs the real checks against public DNS,
 * touching no rows: www.gov.uk (gov.uk publishes p=reject but sp=none, so its
 * subdomains are NOT protected) and mail.google.com (google.com: p=reject, no sp).
 */

$root = dirname(__DIR__);
require_once "$root/config.php";
require_once "$root/includes/functions.php";
require_once "$root/includes/services/domains.php";
require_once "$root/includes/domains/alerts.php";
require_once "$root/includes/domains/read.php";

$pass = 0; $fail = 0;
function ok(string $label, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; printf("  PASS %-78s\n", $label); }
    else       { $fail++; printf("  FAIL %-78s %s\n", $label, $detail); }
}

$conn = connectToDatabase();
if (!domainSubdomainsReady($conn) || !domainManualCertsReady($conn)) { echo "Run Database Verification first.\n"; exit(1); }
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_active = 1 AND is_admin = 1 ORDER BY id LIMIT 1")->fetchColumn();
$ctx = new ActorContext(actorId: $admin, companyScope: null, source: 'ui');
$default = getDefaultTenantId($conn);
$tag = 'zzsc' . bin2hex(random_bytes(3));
// Dates counted from TODAY IN UTC, as the code counts them - never strtotime('+5 days'),
// which counts in local time and is a day out across a clock change.
$inDays = fn(int $n) => (new DateTimeImmutable(gmdate('Y-m-d')))->modify("+$n days")->format('Y-m-d');
$P = "$tag.example";
$parentOf = function (int $id) use ($conn): ?int {
    $s = $conn->prepare("SELECT parent_domain_id FROM domains WHERE id = ?"); $s->execute([$id]);
    $v = $s->fetchColumn(); return $v === null || $v === false ? null : (int)$v;
};
$refused = function (callable $fn): string {
    try { $fn(); return ''; } catch (ServiceError $e) { return $e->getMessage(); }
};

echo "\nPart 1 - the rules (rolled back)\n" . str_repeat('=', 84) . "\n";
$conn->beginTransaction();
try {
    echo "\nSubdomains\n";
    $parent = DomainsService::createDomain($conn, $ctx, ['domain_name' => $P], $default);
    $sub = DomainsService::createDomain($conn, $ctx, ['domain_name' => "students.$P"], $default);
    ok('a subdomain added after its parent is linked to it', $parentOf($sub) === $parent);
    $deep = DomainsService::createDomain($conn, $ctx, ['domain_name' => "a.b.$P"], $default);
    ok('...a deeper one too - to the closest domain in the register', $parentOf($deep) === $parent);
    $loose = DomainsService::createDomain($conn, $ctx, ['domain_name' => "staff.other-$P"], $default);
    $later = DomainsService::createDomain($conn, $ctx, ['domain_name' => "other-$P"], $default);
    ok('a subdomain added BEFORE its parent is adopted when the parent arrives', $parentOf($loose) === $later);
    ok('...and the parent is not itself made a subdomain', $parentOf($later) === null);
    $sibling = DomainsService::createDomain($conn, $ctx, ['domain_name' => "not$P"], $default);
    ok('a name that merely ENDS the same way (not' . $P . ') is not a subdomain', $parentOf($sibling) === null);

    ok('choosing a parent the name is not under is refused', str_contains($refused(fn() => DomainsService::updateDomain($conn, $ctx, $sub, ['parent_domain_id' => $later])), 'not a subdomain of'));
    $under = DomainsService::createDomain($conn, $ctx, ['domain_name' => "x.students.$P"], $default);
    ok('choosing a subdomain as the parent is refused (one level)', str_contains($refused(fn() => DomainsService::updateDomain($conn, $ctx, $under, ['parent_domain_id' => $sub])), 'subdomain itself'));
    ok('a domain with subdomains cannot become a subdomain', str_contains($refused(fn() => DomainsService::updateDomain($conn, $ctx, $later, ['parent_domain_id' => $sibling])), 'subdomains of its own')
       || str_contains($refused(fn() => DomainsService::updateDomain($conn, $ctx, $later, ['parent_domain_id' => $sibling])), 'not a subdomain of'));
    ok('a domain cannot be its own parent', $refused(fn() => DomainsService::updateDomain($conn, $ctx, $parent, ['parent_domain_id' => $parent])) !== '');
    if (isMultiTenant($conn)) {
        $other = (int)$conn->query("SELECT id FROM tenants WHERE id <> $default ORDER BY id LIMIT 1")->fetchColumn();
        if ($other) {
            $xco = DomainsService::createDomain($conn, $ctx, ['domain_name' => "x.$P"], $other);
            ok('another company\'s domain is never found as the parent', $parentOf($xco) === null);
            ok('...nor accepted when named', str_contains($refused(fn() => DomainsService::updateDomain($conn, $ctx, $xco, ['parent_domain_id' => $parent])), 'different company'));
        }
    }
    DomainsService::updateDomain($conn, $ctx, $deep, ['parent_domain_id' => null]);
    ok('the link can be removed', $parentOf($deep) === null);

    $r = DomainsService::refreshLookup($conn, $ctx, $sub);
    $err = $conn->query("SELECT last_lookup_error FROM domains WHERE id = $sub")->fetchColumn();
    ok('a subdomain is never looked up at a registry - and that is not recorded as a failure', !$r['ok'] && !empty($r['subdomain']) && $err === null, json_encode($r));
    $det = domainDetail($conn, $sub, $admin);
    ok('its page carries the parent\'s registration', ($det['parent']['id'] ?? 0) === $parent && ($det['parent']['domain_name'] ?? '') === $P);
    ok('the parent\'s page lists its subdomains', in_array($sub, array_column(domainSubdomains($conn, $parent), 'id'), true));
    $cands = array_column(domainParentCandidates($conn, "students.$P", null, $sub), 'id');
    ok('the Edit dialog is offered the parents it could have', $cands === [$parent], json_encode($cands));

    DomainsService::updateDomain($conn, $ctx, $sub, ['domain_name' => "students.elsewhere-$tag.example"]);
    ok('renamed out from under its parent: no longer its subdomain', $parentOf($sub) === null);
    $sub = DomainsService::createDomain($conn, $ctx, ['domain_name' => "students.$P"], $default);
    $hist = $conn->query("SELECT COUNT(*) FROM domain_audit WHERE domain_id = $loose AND field_name = 'parent_domain_id'")->fetchColumn();
    ok('being adopted is in the subdomain\'s history', (int)$hist === 1);

    // The one-off link of an upgraded register: rows written before the
    // feature, with no links - including a chain - linked one level deep.
    $ins = $conn->prepare("INSERT INTO domains (domain_name, tenant_id, created_datetime) VALUES (?, NULL, UTC_TIMESTAMP())");
    foreach (["y.z.old-$P", "z.old-$P", "old-$P"] as $nm) $ins->execute([$nm]);
    $id = fn($nm) => (int)$conn->query("SELECT id FROM domains WHERE domain_name = " . $conn->quote($nm))->fetchColumn();
    $n = domainLinkExistingSubdomains($conn);
    ok('an upgraded register is linked once: z.old- and y.z.old- both under old- (one level)',
       $n >= 2 && $parentOf($id("z.old-$P")) === $id("old-$P") && $parentOf($id("y.z.old-$P")) === $id("old-$P"), "$n linked");

    echo "\nRecorded certificates\n";
    $sup = (int)$conn->query("SELECT id FROM suppliers ORDER BY id LIMIT 1")->fetchColumn();
    $type = (int)$conn->query("SELECT id FROM domain_certificate_types WHERE is_active = 1 ORDER BY display_order LIMIT 1")->fetchColumn();
    $soon = $inDays(5);
    $mk = fn(array $in) => DomainsService::saveManualCertificate($conn, $ctx, $in + ['domain_id' => $sub, 'purpose' => "ZZ VPN $tag", 'expiry_date' => $soon]);
    ok('one with no purpose is refused', str_contains($refused(fn() => $mk(['purpose' => '  '])), 'what the certificate is for'));
    ok('one with no (or a bad) expiry date is refused', $refused(fn() => $mk(['expiry_date' => '2027-02-30'])) !== '');
    ok('a javascript: "address" is refused', str_contains($refused(fn() => $mk(['url' => 'javascript:alert(1)'])), 'http'));
    $c = $mk(['cert_type_id' => $type, 'supplier_id' => $sup ?: null, 'provider_name' => 'typed too', 'url' => 'https://vpn.example/', 'purpose' => "ZZ VPN\n$tag"]);
    $row = $conn->query("SELECT * FROM domain_manual_certificates WHERE id = {$c['id']}")->fetch(PDO::FETCH_ASSOC);
    ok('a certificate is recorded', $c['created'] && $row && $row['expiry_date'] === $soon);
    ok('...its purpose kept to one line (it goes into e-mail subjects)', $row['purpose'] === "ZZ VPN $tag", json_encode($row['purpose']));
    ok('...a supplier wins over typed text - one provider, not two', !$sup || ((int)$row['supplier_id'] === $sup && $row['provider_name'] === null));
    ok('...and it is in the domain\'s history', (int)$conn->query("SELECT COUNT(*) FROM domain_audit WHERE domain_id = $sub AND field_name = 'certificate_added'")->fetchColumn() === 1);
    $list = domainManualCertificates($conn, $sub);
    ok('the page lists it with its days left', count($list) === 1 && $list[0]['days_left'] === 5 && ($list[0]['provider'] ?? '') !== '', json_encode($list[0] ?? null));

    ok('a type in use cannot be deleted', str_contains($refused(fn() => DomainsService::deleteCertType($conn, $type)), 'still use'));
    $conn->prepare("UPDATE domain_certificate_types SET is_active = 0 WHERE id = ?")->execute([$type]);
    $again = DomainsService::saveManualCertificate($conn, $ctx, ['id' => $c['id'], 'purpose' => "ZZ VPN $tag", 'cert_type_id' => $type, 'expiry_date' => $soon]);
    ok('...a type switched off stays on the certificates that already have it', (int)$conn->query("SELECT cert_type_id FROM domain_manual_certificates WHERE id = {$again['id']}")->fetchColumn() === $type);
    $new = $inDays(3);
    ok('...but cannot be given to a new one', $refused(fn() => DomainsService::saveManualCertificate($conn, $ctx, ['domain_id' => $sub, 'purpose' => 'x', 'cert_type_id' => $type, 'expiry_date' => $new])) !== '');
    $conn->prepare("UPDATE domain_certificate_types SET is_active = 1 WHERE id = ?")->execute([$type]);

    echo "\nReminders\n";
    $conn->prepare("UPDATE domains SET expiry_date = ? WHERE id IN (?, ?)")->execute([$inDays(7), $sub, $parent]);
    $s = domainSettings($conn, true);
    $s['domain_alert_days'] = '7'; $s['domain_alert_auto_renew'] = '1'; $s['domain_ssl_alerts'] = '1'; $s['domain_ssl_warn_days'] = '21';
    $due = domainAlertsDue($conn, $s);
    $mine = array_values(array_filter($due, fn($a) => in_array((int)$a['id'], [$sub, $parent], true)));
    $kinds = array_map(fn($a) => $a['id'] . ':' . $a['kind'], $mine);
    ok('a subdomain is never reminded of an expiry - its parent is', in_array("$parent:expiry", $kinds, true) && !in_array("$sub:expiry", $kinds, true), json_encode($kinds));
    $certDue = array_values(array_filter($mine, fn($a) => $a['kind'] === 'ssl' && ($a['certificate'] ?? '') !== ''));
    ok('a recorded certificate expiring in 5 days is a certificate alert', count($certDue) === 1 && $certDue[0]['days'] === 5);
    ok('...fingerprinted by certificate AND date, so it fires once and again after renewal', ($certDue[0]['fingerprint'] ?? '') === 'm' . $c['id'] . ':' . $soon);
    $digest = domainAlertDigest($conn, 'ZZ', $certDue);
    ok('...and the e-mail says which certificate', str_contains($digest['subject'], "ZZ VPN $tag") && str_contains($digest['body'], "ZZ VPN $tag"), $digest['subject']);

    // The calendar: shown on it for this run (setting changed inside the rolled-back transaction).
    require_once "$root/includes/domains/calendar.php";
    foreach (['domain_cert_surface' => 'calendar', 'domain_expiry_surface' => 'calendar'] as $k => $v) {
        $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")->execute([$k, $v]);
    }
    domainSettings($conn, true);   // re-read: the cache still holds the values from before
    domainSyncExpiryCalendar($conn);
    $cal = $conn->prepare("SELECT title FROM calendar_events WHERE source IN ('domain_cert', 'domain_expiry') AND title LIKE ?");
    $cal->execute(["%$tag%"]);
    $titles = $cal->fetchAll(PDO::FETCH_COLUMN);
    ok('the recorded certificate is on the calendar, by name', in_array("Certificate expires: ZZ VPN $tag (students.$P)", $titles, true), json_encode($titles));
    ok('the parent\'s renewal is on it - the subdomain\'s is not', in_array("Domain expires: $P", $titles, true) && !in_array("Domain expires: students.$P", $titles, true));

    DomainsService::deleteManualCertificate($conn, $ctx, $c['id']);
    ok('a certificate can be deleted, and that is in the history',
       !$conn->query("SELECT COUNT(*) FROM domain_manual_certificates WHERE id = {$c['id']}")->fetchColumn()
       && (int)$conn->query("SELECT COUNT(*) FROM domain_audit WHERE domain_id = $sub AND field_name = 'certificate_removed'")->fetchColumn() === 1);
} finally {
    if ($conn->inTransaction()) $conn->rollBack();
    domainSettings($conn, true);
}
$left = (int)$conn->query("SELECT COUNT(*) FROM domains WHERE domain_name LIKE '%$tag%'")->fetchColumn();
ok('nothing survived part 1 (rolled back)', $left === 0, "$left rows");

// Deleting runs its own transaction, which cannot nest inside the rolled-back
// one - so these few rows are real, and deleted by the very code under test
// (with a sweep afterwards, should a check fail half way).
echo "\nDeleting\n";
try {
    $dp = DomainsService::createDomain($conn, $ctx, ['domain_name' => "del-$P"], $default);
    $ds = DomainsService::createDomain($conn, $ctx, ['domain_name' => "s.del-$P"], $default);
    $dc = DomainsService::saveManualCertificate($conn, $ctx, ['domain_id' => $ds, 'purpose' => 'ZZ to cascade', 'expiry_date' => $inDays(30)]);
    DomainsService::deleteDomain($conn, $ctx, $dp);
    ok('deleting a parent leaves its subdomains, standing on their own', $parentOf($ds) === null);
    DomainsService::deleteDomain($conn, $ctx, $ds);
    ok('deleting a domain removes its recorded certificates', !$conn->query("SELECT COUNT(*) FROM domain_manual_certificates WHERE id = {$dc['id']}")->fetchColumn());
} finally {
    foreach ($conn->query("SELECT id FROM domains WHERE domain_name LIKE '%$tag%'")->fetchAll(PDO::FETCH_COLUMN) as $id) {
        try { DomainsService::deleteDomain($conn, $ctx, (int)$id); } catch (Throwable $e) {}
    }
    domainSyncExpiryCalendar($conn);   // the deletes above re-synced it; leave it as the settings say
}
$left = (int)$conn->query("SELECT COUNT(*) FROM domains WHERE domain_name LIKE '%$tag%'")->fetchColumn();
ok('nothing left behind by the delete checks', $left === 0, "$left rows");

echo "\nPart 2 - the checks, against real DNS (no rows)\n" . str_repeat('=', 84) . "\n";
$row = fn(string $name, ?string $parent) => ['domain_name' => $name, 'purpose' => 'primary', 'registry_statuses' => '', 'expiry_date' => null,
    'transfer_lock' => null, 'registry_lock' => 0, 'renewal_mode' => 'unknown', 'nameservers' => '', 'dnssec' => null, 'dkim_selectors' => '',
    'ssl_hosts' => '', 'parent_domain_id' => $parent ? 1 : null, 'parent_name' => $parent, 'parent_dnssec' => null];
$run = fn(array $r) => domainRunChecks($r, ['domain_dns_resolver' => 'google', 'domain_ssl_warn_days' => 21]);
$keys = fn(array $res) => array_column($res['findings'], 'key');
$find = fn(array $res, string $k) => array_values(array_filter($res['findings'], fn($f) => $f['key'] === $k))[0] ?? null;

$asSub = $run($row('www.gov.uk', 'gov.uk'));
$k = $keys($asSub);
if (in_array('dns_lookup_failed', $k, true)) {
    echo "  SKIP  part 2 (DNS cannot be reached from here)\n";
} else {
    $plain = $run($row('www.gov.uk', null));
    $kp = $keys($plain);
    ok('CONTROL: checked as a domain of its own, www.gov.uk has no DMARC and no name servers', in_array('dmarc_missing', $kp, true) && in_array('dns_no_ns', $kp, true), implode(',', $kp));
    ok('as a subdomain of gov.uk: no "No DMARC record"', !in_array('dmarc_missing', $k, true), implode(',', $k));
    $inh = $find($asSub, 'dmarc_inherited');
    ok('...gov.uk\'s DMARC applies - with sp=none, NOT its p=reject', ($inh['params']['policy'] ?? '') === 'none' && ($inh['params']['parent'] ?? '') === 'gov.uk', json_encode($inh));
    ok('...so it is graded as monitor-only, which it really is', in_array('dmarc_monitor_only', $k, true));
    ok('...no "No name servers" - it lives in its parent\'s zone', !in_array('dns_no_ns', $k, true) && in_array('dns_sub_parent_zone', $k, true));
    ok('...and none of the registration checks are its own', !array_intersect(['reg_expiry_unknown', 'reg_transfer_unknown', 'renew_unknown', 'reg_registry_lock_suggest'], $k)
       && in_array('sub_registration', $k, true));
    $g = $run($row('mail.google.com', 'google.com'));
    $kg = $keys($g);
    ok('google.com publishes p=reject and no sp=: mail.google.com inherits reject', in_array('dmarc_reject', $kg, true)
       && ($find($g, 'dmarc_inherited')['params']['policy'] ?? '') === 'reject', implode(',', $kg));
    ok('...and CAA climbs to the parent, rather than "CAA missing"', !in_array('caa_missing', $kg, true) && in_array('caa_present', $kg, true));
}

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
