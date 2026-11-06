<?php
/**
 * API: show what the field mapping would actually produce.
 *
 * POST { provider_id, ...the same field values the form holds... }
 *
 * Fetches ONE real person from the directory and runs the configured mapping
 * over them, so each row can show the value it would import rather than just
 * the attribute name it was told to read.
 *
 * An attribute name is abstract. "employeeID → NW-1011" is not. Most mapping
 * mistakes are invisible until somebody notices a column is empty weeks later —
 * this turns that into something you can see before you save.
 *
 * Also returns every attribute the sample person HAS, so a field that cannot be
 * mapped because the directory simply does not carry it is distinguishable from
 * one that is mapped to the wrong name.
 *
 * Reads only. Administrators only.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/encryption.php';
require_once '../../includes/directory_sync.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();
    if (!analystIsAdmin($conn, (int)$_SESSION['analyst_id'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Administrator access required']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $pid  = (int)($data['provider_id'] ?? 0);

    $s = $conn->prepare("SELECT * FROM auth_providers WHERE id = ? AND protocol = 'ldap'");
    $s->execute([$pid]);
    $provider = $s->fetch(PDO::FETCH_ASSOC);
    if (!$provider) {
        echo json_encode(['success' => false, 'error' => 'No such directory provider']);
        exit;
    }
    $provider['ldap_bind_password'] = decryptValue($provider['ldap_bind_password'] ?? '');

    // Test what is ON THE FORM, not what was last saved — otherwise you cannot
    // check a mapping before committing to it, which is the whole point.
    foreach ([
        'ldap_attr_username', 'ldap_attr_email', 'ldap_attr_name', 'ldap_attr_guid',
        'ldap_attr_job_title', 'ldap_attr_department', 'ldap_attr_office',
        'ldap_attr_phone', 'ldap_attr_mobile', 'ldap_attr_employee_id', 'ldap_attr_manager',
        'sync_base_dn', 'sync_filter',
    ] as $k) {
        if (array_key_exists($k, $data)) {
            $provider[$k] = trim((string)$data[$k]) ?: null;
        }
    }

    $ds = ldapOpen($provider);
    ldapBindService($ds, $provider);

    // One person is enough, and asking for one keeps this fast on a big
    // directory. A named sample is allowed so somebody can check a person they
    // know rather than whoever happens to sort first.
    $sample = trim((string)($data['sample'] ?? ''));
    $baseDn = trim((string)($provider['sync_base_dn'] ?? '')) ?: trim((string)($provider['ldap_base_dn'] ?? ''));
    $filter = trim((string)($provider['sync_filter'] ?? '')) ?: '(&(objectClass=user)(objectCategory=person))';
    if ($sample !== '') {
        $u = dsyncAttr($provider, 'username');
        $filter = '(&' . $filter . '(' . $u . '=' . ldap_escape($sample, '', LDAP_ESCAPE_FILTER) . '))';
    }

    // No attribute list: we want to see EVERYTHING this person carries, which is
    // how "the directory does not have that field" becomes visible.
    $res = @ldap_search($ds, $baseDn, $filter, [], 0, 5);
    if ($res === false) {
        echo json_encode(['success' => false, 'error' => 'Search failed: ' . ldap_error($ds)]);
        exit;
    }
    $entries = @ldap_get_entries($ds, $res) ?: ['count' => 0];
    if (($entries['count'] ?? 0) === 0) {
        echo json_encode([
            'success' => false,
            'error'   => $sample !== ''
                ? 'Nobody matched "' . $sample . '" in that part of the directory.'
                : 'The search found nobody. Check the scope and filter on the Importing people tab.',
        ]);
        exit;
    }

    $entry = $entries[0];
    // Null means neither the sign-in name nor the unique id resolved — a person
    // the importer would skip entirely. That is the single worst mapping fault
    // there is, so it must still render the table and the attribute list rather
    // than bailing out: those are exactly what show you why.
    $mapped = dsyncMapPerson($entry, $provider) ?: [];

    // Every attribute the sample actually has, so an unmappable field is
    // distinguishable from a mistyped one.
    $available = [];
    for ($i = 0; $i < ($entry['count'] ?? 0); $i++) {
        $name = $entry[$i];
        $val  = is_array($entry[$name] ?? null) ? ($entry[$name][0] ?? '') : '';
        // Binary values (objectGUID, thumbnailPhoto) are unprintable; say so
        // rather than emitting mojibake into the page.
        $printable = preg_match('//u', (string)$val) === 1
                  && preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', (string)$val) !== 1;
        $available[] = [
            'name'  => $name,
            'value' => $printable ? mb_substr((string)$val, 0, 120) : '(binary)',
        ];
    }
    usort($available, fn($a, $b) => strcasecmp($a['name'], $b['name']));

    $fields = [
        'name'        => 'Display name',
        'username'    => 'Sign-in name',
        'email'       => 'Email',
        'guid'        => 'Unique id',
        'job_title'   => 'Job title',
        'department'  => 'Department',
        'office'      => 'Office',
        'phone'       => 'Phone',
        'mobile'      => 'Mobile',
        'employee_id' => 'Employee number',
        'manager'     => 'Manager',
    ];
    $rows = [];
    foreach ($fields as $key => $label) {
        $attr = dsyncAttr($provider, $key);
        $val  = $key === 'manager' ? ($mapped['manager_dn'] ?? '') : ($mapped[$key] ?? '');
        if ($key === 'guid' && $val !== '') $val = substr((string)$val, 0, 24) . '…';
        $rows[] = [
            // The page matches rows to its own inputs on `key`; `field` is the
            // English label, for anyone reading the response directly.
            'key'       => $key,
            'field'     => $label,
            'attribute' => $attr,
            'value'     => (string)($val ?? ''),
            // Mapped to something the sample does not carry: the single most
            // useful thing this endpoint can tell you.
            'missing'   => $attr !== '' && ($val === '' || $val === null),
        ];
    }

    // Fall back to the DN so the person is still identifiable when the name and
    // username mappings are the very things that are wrong.
    $who = ($mapped['name'] ?? '') ?: (($mapped['username'] ?? '') ?: ($entry['dn'] ?? '(unnamed)'));

    echo json_encode([
        'success'   => true,
        'skipped'   => $mapped === [],
        'sample'    => $who,
        'rows'      => $rows,
        'available' => $available,
        'total'     => (int)($entries['count'] ?? 0),
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
