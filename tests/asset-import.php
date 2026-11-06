<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Asset import — the parts that quietly ruin a scheduled import.
 *
 * Not "does it read a CSV" (it obviously does) but the four things that make an
 * import safe to leave running unattended:
 *
 *   1. RECONCILIATION — the same file twice must UPDATE, never duplicate, and
 *      an ambiguous match must refuse rather than guess.
 *   2. PREVIEW — reports exactly what a live run would do, and writes nothing.
 *   3. THE HOLDING AREA — a bad row is kept with its reason and its source line.
 *   4. ROWS THAT VANISH — handled by an explicit policy, never a default guess.
 *
 * ⚠️ Creates only `zzimp`-prefixed rows and sweeps them before AND after.
 * The services open their own transactions, so an outer one is impossible.
 *
 * Run:  php tests/asset-import.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/asset_import.php';

$pass = 0; $fail = 0;

function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tmp = sys_get_temp_dir() . '/zzimp_' . getmypid() . '.csv';

$sweep = function () use ($conn, $tmp): void {
    $conn->query("DELETE e FROM asset_import_run_entries e JOIN asset_import_runs r ON r.id=e.run_id WHERE r.source_name LIKE 'zzimp%'");
    $conn->query("DELETE FROM asset_import_runs WHERE source_name LIKE 'zzimp%'");
    $conn->query("DELETE v FROM asset_field_values v JOIN assets a ON a.id=v.asset_id WHERE a.hostname LIKE 'ZZIMP-%'");
    $conn->query("DELETE FROM asset_field_set_assets WHERE asset_id IN (SELECT id FROM assets WHERE hostname LIKE 'ZZIMP-%')");
    $conn->query("DELETE FROM asset_history WHERE asset_id IN (SELECT id FROM assets WHERE hostname LIKE 'ZZIMP-%')");
    $conn->query("DELETE FROM assets WHERE hostname LIKE 'ZZIMP-%'");
    $conn->query("DELETE sf FROM asset_field_set_fields sf JOIN asset_fields f ON f.id=sf.field_id WHERE f.field_key LIKE 'zzimp%'");
    $conn->query("DELETE o FROM asset_field_options o JOIN asset_fields f ON f.id=o.field_id WHERE f.field_key LIKE 'zzimp%'");
    $conn->query("DELETE ts FROM asset_type_field_sets ts JOIN asset_field_sets s ON s.id=ts.set_id WHERE s.name LIKE 'zzimp%'");
    $conn->query("DELETE ts FROM asset_type_field_sets ts JOIN asset_types t ON t.id=ts.asset_type_id WHERE t.name LIKE 'zzimp%'");
    $conn->query("DELETE FROM asset_field_sets WHERE name LIKE 'zzimp%'");
    $conn->query("DELETE FROM asset_fields WHERE field_key LIKE 'zzimp%'");
    $conn->query("DELETE FROM asset_types WHERE name LIKE 'zzimp%'");
    @unlink($tmp);
};
$sweep();

$estate = static fn(PDO $c): array => [
    'assets' => (int)$c->query("SELECT COUNT(*) FROM assets")->fetchColumn(),
    'runs'   => (int)$c->query("SELECT COUNT(*) FROM asset_import_runs")->fetchColumn(),
];
$before = $estate($conn);

/** Write a CSV and read it back through the service. */
function csvRows(string $path, string $body): array {
    file_put_contents($path, $body);
    return AssetImportService::readCsv($path);
}

try {
    $ctx = new ActorContext(actorId: 1, companyScope: null, source: 'api', actorName: 'Import Test');

    // A type with one custom field, so the import exercises both halves.
    $conn->prepare("INSERT INTO asset_types (name, is_active) VALUES (?, 1)")->execute(['zzimp Television']);
    $typeId = (int)$conn->lastInsertId();
    $fieldId = (int)AssetFieldsService::saveField($conn, $ctx, [
        'label' => 'zzimp Screen size', 'field_type' => 'number',
    ])['id'];
    $panelId = (int)AssetFieldsService::saveField($conn, $ctx, [
        'label' => 'zzimp Panel', 'field_type' => 'dropdown', 'options' => ['OLED', 'LED'],
    ])['id'];
    $setId = (int)AssetFieldsService::saveSet($conn, $ctx, ['name' => 'zzimp TV'])['id'];
    AssetFieldsService::setSetFields($conn, $setId, [
        ['field_id' => $fieldId, 'sort_order' => 0],
        ['field_id' => $panelId, 'sort_order' => 1],
    ]);
    AssetFieldsService::setTypeSets($conn, $typeId, [$setId]);

    $mapping = [
        'Name'        => ['target_kind' => 'core',  'target_key' => 'hostname'],
        'Make'        => ['target_kind' => 'core',  'target_key' => 'manufacturer'],
        'Screen size' => ['target_kind' => 'field', 'target_key' => 'zzimp_screen_size'],
        'Panel'       => ['target_kind' => 'field', 'target_key' => 'zzimp_panel'],
        'Notes'       => null,   // deliberately unmapped
    ];
    $opts = [
        'match_keys' => ['hostname'],
        'default_asset_type_id' => $typeId,
        'source_name' => 'zzimp-tvs.csv',
    ];

    // ================================================================
    echo "\n--- the BOM, which is why a first import often does nothing ---\n";
    // ================================================================

    $bom = csvRows($tmp, "\xEF\xBB\xBFName,Make\nZZIMP-01,Samsung\n");
    ok('a UTF-8 BOM does not corrupt the first header',
        $bom['headers'][0] === 'Name', var_export($bom['headers'][0], true));

    $dupes = csvRows($tmp, "Serial,Serial\nA,B\n");
    ok('a duplicate header is renamed, not silently merged',
        $dupes['headers'] === ['Serial', 'Serial (2)'], json_encode($dupes['headers']));

    // ================================================================
    echo "\n--- preview writes NOTHING ---\n";
    // ================================================================

    $csv = "Name,Make,Screen size,Panel,Notes\n"
         . "ZZIMP-01,Samsung,55,LED,first\n"
         . "ZZIMP-02,Samsung,65,OLED,second\n"
         . "ZZIMP-03,LG,43,LED,third\n";
    $parsed = csvRows($tmp, $csv);
    ok('three rows read', count($parsed['rows']) === 3, (string)count($parsed['rows']));

    $prev = AssetImportService::run($conn, $ctx, $parsed['rows'], $mapping, $opts, 'preview');
    ok('preview says it would create 3', $prev['created_count'] === 3, json_encode($prev));
    $n = (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname LIKE 'ZZIMP-%'")->fetchColumn();
    ok('...and created none', $n === 0, "found {$n}");

    // ================================================================
    echo "\n--- live run ---\n";
    // ================================================================

    $live = AssetImportService::run($conn, $ctx, $parsed['rows'], $mapping, $opts, 'live');
    ok('live run created 3', $live['created_count'] === 3, json_encode($live));
    $n = (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname LIKE 'ZZIMP-%'")->fetchColumn();
    ok('...and there are 3 assets', $n === 3, "found {$n}");

    $a = $conn->query("SELECT id, manufacturer, asset_type_id FROM assets WHERE hostname='ZZIMP-01'")->fetch(PDO::FETCH_ASSOC);
    ok('a core column was written', $a['manufacturer'] === 'Samsung');
    ok('the default type was applied', (int)$a['asset_type_id'] === $typeId);

    $defs = AssetFieldsService::fieldsForAsset($conn, (int)$a['id'], $typeId);
    $vals = AssetFieldsService::valuesForAsset($conn, (int)$a['id'], $defs);
    ok('a CUSTOM field was written in the same pass',
        ($vals['zzimp_screen_size'] ?? null) === 55.0, json_encode($vals));
    ok('a dropdown value from the list was accepted',
        ($vals['zzimp_panel'] ?? null) === 'LED', json_encode($vals));

    // ================================================================
    echo "\n--- 🔑 THE SAME FILE AGAIN: update, never duplicate ---\n";
    // ================================================================

    $again = AssetImportService::run($conn, $ctx, $parsed['rows'], $mapping, $opts, 'live');
    ok('run two created NOTHING', $again['created_count'] === 0, json_encode($again));
    ok('run two saw them as unchanged', $again['unchanged_count'] === 3, json_encode($again));
    $n = (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname LIKE 'ZZIMP-%'")->fetchColumn();
    ok('still 3 assets, not 6', $n === 3, "found {$n}");

    // A changed cell IS picked up.
    $changed = csvRows($tmp, "Name,Make,Screen size,Panel,Notes\nZZIMP-03,LG,50,LED,third\n");
    $upd = AssetImportService::run($conn, $ctx, $changed['rows'], $mapping,
                                   $opts + ['write_mode' => 'overwrite'], 'live');
    ok('a changed value is an update', $upd['updated_count'] === 1, json_encode($upd));
    $id3 = (int)$conn->query("SELECT id FROM assets WHERE hostname='ZZIMP-03'")->fetchColumn();
    $v3  = AssetFieldsService::valuesForAsset($conn, $id3, AssetFieldsService::fieldsForAsset($conn, $id3, $typeId));
    ok('...and the new value landed', ($v3['zzimp_screen_size'] ?? null) === 50.0, json_encode($v3));

    echo "\n--- fill vs overwrite ---\n";

    $back = csvRows($tmp, "Name,Make,Screen size,Panel,Notes\nZZIMP-03,Sony,99,LED,third\n");
    AssetImportService::run($conn, $ctx, $back['rows'], $mapping, $opts + ['write_mode' => 'fill'], 'live');
    $m3 = $conn->query("SELECT manufacturer FROM assets WHERE hostname='ZZIMP-03'")->fetchColumn();
    ok('fill mode leaves an answered field ALONE', $m3 === 'LG', var_export($m3, true));

    // ================================================================
    echo "\n--- 🔑 AMBIGUITY REFUSES, it does not guess ---\n";
    // ================================================================

    // Two assets that both answer to the same service tag.
    $conn->prepare("UPDATE assets SET service_tag='ZZDUP' WHERE hostname IN ('ZZIMP-01','ZZIMP-02')")->execute();
    $ambig = csvRows($tmp, "Serial,Make\nZZDUP,Philips\n");
    $amap  = ['Serial' => ['target_kind' => 'core', 'target_key' => 'service_tag'],
              'Make'   => ['target_kind' => 'core', 'target_key' => 'manufacturer']];
    $conf  = AssetImportService::run($conn, $ctx, $ambig['rows'], $amap,
                                     ['match_keys' => ['service_tag'], 'source_name' => 'zzimp-dup.csv'], 'live');
    ok('two matches = a conflict', $conf['conflict_count'] === 1, json_encode($conf));
    $m1 = $conn->query("SELECT manufacturer FROM assets WHERE hostname='ZZIMP-01'")->fetchColumn();
    ok('...and NEITHER asset was touched', $m1 === 'Samsung', var_export($m1, true));

    $entry = $conn->query(
        "SELECT detail FROM asset_import_run_entries WHERE action='conflict' ORDER BY id DESC LIMIT 1"
    )->fetchColumn();
    ok('the conflict says how many it matched', str_contains((string)$entry, 'Matches 2'), (string)$entry);

    // ================================================================
    echo "\n--- 🔑 THE HOLDING AREA ---\n";
    // ================================================================

    $bad = csvRows($tmp, "Name,Make,Screen size,Panel,Notes\nZZIMP-09,Sony,not-a-number,LED,x\n");
    $br  = AssetImportService::run($conn, $ctx, $bad['rows'], $mapping, $opts, 'live');
    ok('a bad value is an error, not a silent skip', $br['error_count'] === 1, json_encode($br));

    // 🔑 THE ASSERTION THAT MATTERS. A row logged as an error must have left
    // NOTHING behind — the create had already succeeded before the custom field
    // rejected the value, so without per-row transactions an asset survived
    // while the log called the row a failure.
    $orphan = (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname='ZZIMP-09'")->fetchColumn();
    ok('a failed row leaves NO asset behind', $orphan === 0, "found {$orphan}");

    $held = AssetImportService::unresolved($conn);
    ok('the bad row is waiting in the holding area', count($held) >= 1, (string)count($held));
    $mine = null;
    foreach ($held as $h) { if (($h['display_name'] ?? '') === 'ZZIMP-09') { $mine = $h; break; } }
    ok('it kept the SOURCE ROW verbatim',
        $mine && ($mine['raw_row']['Screen size'] ?? null) === 'not-a-number',
        json_encode($mine['raw_row'] ?? null));
    ok('it kept the REASON', $mine && str_contains($mine['detail'], 'expects a number'),
        $mine['detail'] ?? '');
    ok('and the conflict is in there too',
        count(array_filter($held, static fn($h) => $h['action'] === 'conflict')) >= 1);

    AssetImportService::resolveEntry($conn, (int)$mine['id']);
    $after = AssetImportService::unresolved($conn);
    ok('resolving takes it out of the holding area',
        !array_filter($after, static fn($h) => (int)$h['id'] === (int)$mine['id']));

    echo "\n--- an unknown dropdown value ---\n";

    $unk = csvRows($tmp, "Name,Make,Screen size,Panel,Notes\nZZIMP-10,Sony,40,PLASMA,x\n");
    $r1  = AssetImportService::run($conn, $ctx, $unk['rows'], $mapping, $opts, 'live');
    ok('reject is the default, and it errors', $r1['error_count'] === 1, json_encode($r1));

    $r2 = AssetImportService::run($conn, $ctx, $unk['rows'], $mapping,
                                  $opts + ['on_unknown_option' => 'add'], 'live');
    ok('"add" accepts it instead', $r2['created_count'] === 1, json_encode($r2));
    $opt = $conn->prepare("SELECT COUNT(*) FROM asset_field_options WHERE field_id=? AND option_value='PLASMA'");
    $opt->execute([$panelId]);
    ok('...and the option now exists', (int)$opt->fetchColumn() === 1);

    echo "\n--- rows with nothing to identify them ---\n";

    $noid = csvRows($tmp, "Name,Make,Screen size,Panel,Notes\n,Sony,40,LED,x\n");
    $r3   = AssetImportService::run($conn, $ctx, $noid['rows'], $mapping, $opts, 'live');
    ok('a row with no identity is skipped, and says why', $r3['skipped_count'] === 1, json_encode($r3));

    echo "\n--- no match keys at all is refused up front ---\n";
    $threw = null;
    try {
        AssetImportService::run($conn, $ctx, $parsed['rows'], $mapping, ['match_keys' => []], 'preview');
    } catch (ServiceError $e) { $threw = $e->getMessage(); }
    ok('refuses to run without an identity column',
        $threw !== null && str_contains($threw, 'identifies a row'), (string)$threw);

    echo "\n--- unmapped columns are ignored, not guessed ---\n";
    $a1 = $conn->query("SELECT id FROM assets WHERE hostname='ZZIMP-01'")->fetchColumn();
    $notes = $conn->prepare("SELECT COUNT(*) FROM asset_field_values v JOIN asset_fields f ON f.id=v.field_id WHERE v.asset_id=? AND f.label LIKE '%Notes%'");
    $notes->execute([$a1]);
    ok('the unmapped Notes column went nowhere', (int)$notes->fetchColumn() === 0);

    // ================================================================
    echo "\n--- 🔑 a spreadsheet says \"Printer\", not \"20\" ---\n";
    // ================================================================

    $byName = csvRows($tmp, "Name,Type\nZZIMP-20,zzimp Television\n");
    $nmap   = ['Name' => ['target_kind' => 'core', 'target_key' => 'hostname'],
               'Type' => ['target_kind' => 'core', 'target_key' => 'asset_type_id']];
    $rn = AssetImportService::run($conn, $ctx, $byName['rows'], $nmap,
                                  ['match_keys' => ['hostname'], 'source_name' => 'zzimp-name.csv'], 'live');
    ok('an asset TYPE given by name is resolved', $rn['created_count'] === 1, json_encode($rn));
    $t20 = (int)$conn->query("SELECT asset_type_id FROM assets WHERE hostname='ZZIMP-20'")->fetchColumn();
    ok('...to the right id', $t20 === $typeId, "got {$t20} want {$typeId}");

    $caseless = csvRows($tmp, "Name,Type\nZZIMP-21,ZZIMP TELEVISION\n");
    $rc = AssetImportService::run($conn, $ctx, $caseless['rows'], $nmap,
                                  ['match_keys' => ['hostname'], 'source_name' => 'zzimp-case.csv'], 'live');
    ok('matching a name ignores case', $rc['created_count'] === 1, json_encode($rc));

    // ⚠️ The important half: an unknown name must NOT quietly create a type.
    $typo = csvRows($tmp, "Name,Type\nZZIMP-22,Televsion\n");
    $rt = AssetImportService::run($conn, $ctx, $typo['rows'], $nmap,
                                  ['match_keys' => ['hostname'], 'source_name' => 'zzimp-typo.csv'], 'live');
    ok('a TYPO in a type name is an error, not a new type', $rt['error_count'] === 1, json_encode($rt));
    $invented = (int)$conn->query("SELECT COUNT(*) FROM asset_types WHERE name='Televsion'")->fetchColumn();
    ok('...and no type called "Televsion" was invented', $invented === 0, "found {$invented}");
    $held2 = AssetImportService::unresolved($conn);
    $typoRow = null;
    foreach ($held2 as $h) { if (($h['display_name'] ?? '') === 'ZZIMP-22') { $typoRow = $h; break; } }
    ok('...and it says exactly what is missing',
        $typoRow && str_contains($typoRow['detail'], 'No asset type called "Televsion"'),
        $typoRow['detail'] ?? 'not parked');

    $numeric = csvRows($tmp, "Name,Type\nZZIMP-23,{$typeId}\n");
    $rnum = AssetImportService::run($conn, $ctx, $numeric['rows'], $nmap,
                                   ['match_keys' => ['hostname'], 'source_name' => 'zzimp-num.csv'], 'live');
    ok('a numeric id still works (an export from another FreeITSM)',
        $rnum['created_count'] === 1, json_encode($rnum));

    echo "\n--- suggestions ---\n";
    $sug = AssetImportService::suggestMapping($conn, ['Hostname', 'Serial Number', 'zzimp Screen size', 'Wibble'], 1);
    ok('a core column is suggested', ($sug['Hostname']['target_key'] ?? null) === 'hostname', json_encode($sug['Hostname'] ?? null));
    ok('a synonym is suggested', ($sug['Serial Number']['target_key'] ?? null) === 'service_tag', json_encode($sug['Serial Number'] ?? null));
    ok('a CUSTOM field beats a core guess', ($sug['zzimp Screen size']['target_kind'] ?? null) === 'field', json_encode($sug['zzimp Screen size'] ?? null));
    ok('an unknown column is left unmapped, not guessed', $sug['Wibble'] === null, json_encode($sug['Wibble']));

} catch (Throwable $e) {
    $fail++;
    echo "\n  FATAL  " . get_class($e) . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

$sweep();

echo "\n";
$after = $estate($conn);
echo '  estate: ' . json_encode($after) . "\n";
ok('estate unchanged', $after === $before,
   'before ' . json_encode($before) . ' after ' . json_encode($after));

echo "\n" . str_repeat('=', 52) . "\n";
echo ($fail === 0 ? 'ALL GREEN' : 'FAILURES') . ": {$pass} passed, {$fail} failed\n";
exit($fail === 0 ? 0 : 1);
