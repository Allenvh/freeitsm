<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Custom asset fields — the TV scenario, end to end.
 *
 * The case this feature exists for, exactly as it was described:
 *
 *   "We buy 10 TVs for our meeting rooms and on day 1 my manager says record
 *    make, model and size. Then 6 months later he wants to pilot making SOME of
 *    them smart — can I add IP address, MAC address and Netflix enabled to SOME
 *    of the TVs and leave the others?"
 *
 * So the test buys ten televisions, records three fields on all of them, then
 * six months later adds three more fields to three of them, and asserts that
 * the other seven are untouched — no rows, no fields, nothing to fill in.
 *
 * ⚠️ Creates only `zz_af_`-prefixed rows and sweeps them before AND after.
 * AssetFieldsService opens its own transactions, so an outer one is impossible.
 * It must never touch real data.
 *
 * Run:  php tests/asset-custom-fields.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/asset_fields.php';

$pass = 0; $fail = 0;

function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

function errOf(callable $fn): ?string {
    try { $fn(); return null; }
    catch (ServiceError $e) { return $e->getMessage(); }
    catch (Exception $e) { return 'UNEXPECTED: ' . $e->getMessage(); }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sweep = function () use ($conn): void {
    // Order matters: values and attachments before the rows they point at.
    $conn->query("DELETE v FROM asset_field_values v JOIN asset_fields f ON f.id = v.field_id WHERE f.field_key LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE v FROM asset_field_values v JOIN assets a ON a.id = v.asset_id WHERE a.hostname LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE FROM asset_field_set_assets WHERE asset_id IN (SELECT id FROM assets WHERE hostname LIKE 'zz\\_af\\_%')");
    $conn->query("DELETE sa FROM asset_field_set_assets sa JOIN asset_field_sets s ON s.id = sa.set_id WHERE s.name LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE ts FROM asset_type_field_sets ts JOIN asset_field_sets s ON s.id = ts.set_id WHERE s.name LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE ts FROM asset_type_field_sets ts JOIN asset_types t ON t.id = ts.asset_type_id WHERE t.name LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE sf FROM asset_field_set_fields sf JOIN asset_field_sets s ON s.id = sf.set_id WHERE s.name LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE sf FROM asset_field_set_fields sf JOIN asset_fields f ON f.id = sf.field_id WHERE f.field_key LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE o FROM asset_field_options o JOIN asset_fields f ON f.id = o.field_id WHERE f.field_key LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE FROM asset_history WHERE asset_id IN (SELECT id FROM assets WHERE hostname LIKE 'zz\\_af\\_%')");
    $conn->query("DELETE FROM assets WHERE hostname LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE FROM asset_field_sets WHERE name LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE FROM asset_fields WHERE field_key LIKE 'zz\\_af\\_%'");
    $conn->query("DELETE FROM asset_types WHERE name LIKE 'zz\\_af\\_%'");
};
$sweep();

$countEstate = static function (PDO $conn): array {
    return [
        'assets' => (int)$conn->query("SELECT COUNT(*) FROM assets")->fetchColumn(),
        'fields' => (int)$conn->query("SELECT COUNT(*) FROM asset_fields")->fetchColumn(),
        'values' => (int)$conn->query("SELECT COUNT(*) FROM asset_field_values")->fetchColumn(),
        'types'  => (int)$conn->query("SELECT COUNT(*) FROM asset_types")->fetchColumn(),
    ];
};
$estateBefore = $countEstate($conn);

try {
    $ctx = new ActorContext(actorId: 1, companyScope: null, source: 'api', actorName: 'Field Test');

    // ================================================================
    //  DAY ONE — ten televisions, three fields
    // ================================================================
    echo "\n--- day one: ten televisions, make / model / size ---\n";

    $conn->prepare("INSERT INTO asset_types (name, is_active) VALUES (?, 1)")->execute(['zz_af_Television']);
    $tvTypeId = (int)$conn->lastInsertId();

    $mk = static function (array $in) use ($conn, $ctx): int {
        return (int)AssetFieldsService::saveField($conn, $ctx, $in)['id'];
    };
    $fMake  = $mk(['label' => 'zz_af_make',  'field_type' => 'text']);
    $fModel = $mk(['label' => 'zz_af_model', 'field_type' => 'text']);
    $fSize  = $mk(['label' => 'zz_af_size',  'field_type' => 'number', 'config' => ['unit' => '"']]);

    $setBasics = (int)AssetFieldsService::saveSet($conn, $ctx, ['name' => 'zz_af_TV basics'])['id'];
    AssetFieldsService::setSetFields($conn, $setBasics, [
        ['field_id' => $fMake,  'sort_order' => 0, 'is_required' => 1],
        ['field_id' => $fModel, 'sort_order' => 1],
        ['field_id' => $fSize,  'sort_order' => 2],
    ]);
    AssetFieldsService::setTypeSets($conn, $tvTypeId, [$setBasics]);

    $tvIds = [];
    $insAsset = $conn->prepare("INSERT INTO assets (hostname, asset_type_id) VALUES (?, ?)");
    for ($i = 1; $i <= 10; $i++) {
        $insAsset->execute([sprintf('zz_af_TV%02d', $i), $tvTypeId]);
        $tvIds[] = (int)$conn->lastInsertId();
    }

    foreach ($tvIds as $n => $id) {
        AssetFieldsService::saveValues($conn, $ctx, $id, [
            'zz_af_make'  => 'Samsung',
            'zz_af_model' => 'QE55Q60D',
            'zz_af_size'  => 55,
        ]);
    }

    $defs = AssetFieldsService::fieldsForAsset($conn, $tvIds[0], $tvTypeId);
    ok('every TV has exactly 3 fields', count($defs) === 3, 'got ' . count($defs));
    $v = AssetFieldsService::valuesForAsset($conn, $tvIds[0], $defs);
    ok('text value round-trips',   ($v['zz_af_make'] ?? null) === 'Samsung');
    ok('number comes back a float', ($v['zz_af_size'] ?? null) === 55.0, var_export($v['zz_af_size'] ?? null, true));

    // ================================================================
    //  SIX MONTHS LATER — pilot three of them as smart TVs
    // ================================================================
    echo "\n--- six months later: three of them go smart ---\n";

    $fIp      = $mk(['label' => 'zz_af_ip address',    'field_type' => 'text']);
    $fMac     = $mk(['label' => 'zz_af_mac address',   'field_type' => 'text', 'is_unique' => 1]);
    $fNetflix = $mk(['label' => 'zz_af_netflix',       'field_type' => 'boolean']);

    $setSmart = (int)AssetFieldsService::saveSet($conn, $ctx, ['name' => 'zz_af_Smart TV pilot'])['id'];
    AssetFieldsService::setSetFields($conn, $setSmart, [
        ['field_id' => $fIp,      'sort_order' => 0],
        ['field_id' => $fMac,     'sort_order' => 1],
        ['field_id' => $fNetflix, 'sort_order' => 2],
    ]);

    // 🔑 Attached to THREE ASSETS, not to the type.
    $pilot = array_slice($tvIds, 0, 3);
    $rest  = array_slice($tvIds, 3);
    foreach ($pilot as $id) {
        AssetFieldsService::attachSetToAsset($conn, $ctx, $id, $setSmart);
    }

    $pilotDefs = AssetFieldsService::fieldsForAsset($conn, $pilot[0], $tvTypeId);
    $restDefs  = AssetFieldsService::fieldsForAsset($conn, $rest[0],  $tvTypeId);
    ok('a piloted TV now has 6 fields', count($pilotDefs) === 6, 'got ' . count($pilotDefs));
    ok('an ordinary TV still has 3',    count($restDefs) === 3,  'got ' . count($restDefs));
    ok('the other seven never saw the smart fields',
        !isset($restDefs['zz_af_ip_address'], $restDefs['zz_af_netflix']));

    foreach ($pilot as $n => $id) {
        AssetFieldsService::saveValues($conn, $ctx, $id, [
            'zz_af_ip_address'  => '10.0.0.' . (10 + $n),
            'zz_af_mac_address' => sprintf('AA:BB:CC:00:00:%02d', $n),
            'zz_af_netflix'     => true,
        ]);
    }

    $pv = AssetFieldsService::valuesForAsset($conn, $pilot[0], $pilotDefs);
    ok('boolean comes back a real bool', ($pv['zz_af_netflix'] ?? null) === true,
       var_export($pv['zz_af_netflix'] ?? null, true));

    echo "\n--- the seven that were left alone ---\n";

    $rows = $conn->prepare(
        "SELECT COUNT(*) FROM asset_field_values WHERE asset_id = ? AND field_id IN (?, ?, ?)"
    );
    $rows->execute([$rest[0], $fIp, $fMac, $fNetflix]);
    ok('NO value rows exist for an unpiloted TV', (int)$rows->fetchColumn() === 0);

    $restVals = AssetFieldsService::valuesForAsset($conn, $rest[0], $restDefs);
    ok('its three original values are untouched',
        ($restVals['zz_af_make'] ?? null) === 'Samsung' && count($restVals) === 3,
        json_encode($restVals));

    // 🔑 ABSENT IS NOT NO. The whole point of §4.5.
    ok('an unset boolean is absent, not false',
        !array_key_exists('zz_af_netflix', $restVals));

    $total = (int)$conn->query(
        "SELECT COUNT(*) FROM asset_field_values v JOIN assets a ON a.id = v.asset_id
          WHERE a.hostname LIKE 'zz\\_af\\_%'"
    )->fetchColumn();
    ok('10 TVs x 3 + 3 pilots x 3 = 39 value rows, not 60', $total === 39, "got {$total}");

    echo "\n--- detaching hides the fields, and keeps the answers ---\n";

    AssetFieldsService::detachSetFromAsset($conn, $pilot[2], $setSmart);
    $afterDetach = AssetFieldsService::fieldsForAsset($conn, $pilot[2], $tvTypeId);
    ok('detached TV is back to 3 fields', count($afterDetach) === 3, 'got ' . count($afterDetach));
    $kept = $conn->prepare("SELECT COUNT(*) FROM asset_field_values WHERE asset_id = ? AND field_id = ?");
    $kept->execute([$pilot[2], $fIp]);
    ok('but the IP address it recorded is still there', (int)$kept->fetchColumn() === 1);

    AssetFieldsService::attachSetToAsset($conn, $ctx, $pilot[2], $setSmart);
    $reDefs = AssetFieldsService::fieldsForAsset($conn, $pilot[2], $tvTypeId);
    $reVals = AssetFieldsService::valuesForAsset($conn, $pilot[2], $reDefs);
    ok('re-attaching brings the value straight back',
        ($reVals['zz_af_ip_address'] ?? null) === '10.0.0.12', json_encode($reVals['zz_af_ip_address'] ?? null));

    echo "\n--- validation ---\n";

    ok('a field the asset does not have is refused, not dropped',
        errOf(fn() => AssetFieldsService::saveValues($conn, $ctx, $rest[0], ['zz_af_netflix' => true]))
        === "Unknown property 'zz_af_netflix' for this class. See the field catalogue in Asset management settings.");

    ok('a number field rejects text',
        errOf(fn() => AssetFieldsService::saveValues($conn, $ctx, $rest[1], ['zz_af_size' => 'huge']))
        === "Property 'zz_af_size' expects a number.");

    ok('a required field cannot be blanked',
        errOf(fn() => AssetFieldsService::saveValues($conn, $ctx, $rest[1], ['zz_af_make' => '']))
        === 'Required property missing: zz_af_make');

    ok('is_unique blocks a duplicate MAC across assets',
        errOf(fn() => AssetFieldsService::saveValues($conn, $ctx, $pilot[1], ['zz_af_mac_address' => 'AA:BB:CC:00:00:00']))
        // ⚠️ The message names the LABEL ("zz_af_mac address"), while the write
        // is addressed by the KEY ("zz_af_mac_address"). That is deliberate —
        // an analyst reads labels — and it is worth an assertion, because the
        // two differing is exactly the kind of thing a careless refactor merges.
        === "Another asset already has 'zz_af_mac address' set to that value.");

    ok('...but re-saving its OWN value is fine',
        errOf(fn() => AssetFieldsService::saveValues($conn, $ctx, $pilot[1], ['zz_af_mac_address' => 'AA:BB:CC:00:00:01']))
        === null);

    echo "\n--- the catalogue is global, and guarded ---\n";

    ok('a duplicate field key is refused',
        errOf(fn() => AssetFieldsService::saveField($conn, $ctx, ['label' => 'zz_af_make', 'field_type' => 'text']))
        !== null);

    ok('the type is locked once values exist',
        errOf(fn() => AssetFieldsService::saveField($conn, $ctx, ['id' => $fMake, 'label' => 'zz_af_make', 'field_type' => 'number']))
        === "'zz_af_make' already has values recorded, so its type can no longer be changed. "
           . 'Retire it and add a new field if the type is wrong.');

    ok('...but the LABEL is still renameable',
        errOf(fn() => AssetFieldsService::saveField($conn, $ctx, ['id' => $fMake, 'label' => 'Manufacturer (renamed)']))
        === null);

    $keyStill = $conn->prepare("SELECT field_key FROM asset_fields WHERE id = ?");
    $keyStill->execute([$fMake]);
    ok('renaming the label leaves the key alone', $keyStill->fetchColumn() === 'zz_af_make');

    echo "\n--- history ---\n";

    $h = $conn->prepare("SELECT COUNT(*) FROM asset_history WHERE asset_id = ? AND field_name LIKE 'field:%'");
    $h->execute([$pilot[0]]);
    ok('custom field edits are audited like built-in ones', (int)$h->fetchColumn() > 0);

    echo "\n--- the batched reader ---\n";

    $allDefs = AssetFieldsService::fieldsForSets($conn, [$setBasics, $setSmart]);
    $batch   = AssetFieldsService::readForAssets($conn, $tvIds, $allDefs);
    ok('one call returns values for all ten TVs', count($batch) === 10, 'got ' . count($batch));
    ok('the piloted ones carry 6 values', count($batch[$pilot[0]]) === 6, 'got ' . count($batch[$pilot[0]] ?? []));
    ok('the rest carry 3', count($batch[$rest[0]]) === 3, 'got ' . count($batch[$rest[0]] ?? []));

} catch (Throwable $e) {
    $fail++;
    echo "\n  FATAL  " . get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

$sweep();

echo "\n";
$leftovers = (int)$conn->query("SELECT COUNT(*) FROM asset_fields WHERE field_key LIKE 'zz\\_af\\_%'")->fetchColumn()
           + (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname LIKE 'zz\\_af\\_%'")->fetchColumn();
ok('cleanup left nothing behind', $leftovers === 0, "found {$leftovers}");

$estateAfter = $countEstate($conn);
echo '  estate: ' . json_encode($estateAfter) . "\n";
ok('estate unchanged', $estateAfter === $estateBefore,
   'before ' . json_encode($estateBefore) . ' after ' . json_encode($estateAfter));

echo "\n" . str_repeat('=', 52) . "\n";
echo ($fail === 0 ? 'ALL GREEN' : 'FAILURES') . ": {$pass} passed, {$fail} failed\n";
exit($fail === 0 ? 0 : 1);
