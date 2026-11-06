<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Assets covered by a contract (discussion #106).
 *
 * Exercises every query in includes/contract_assets.php against the real
 * database, and checks the guards from the attacker's side as well as the
 * happy path — a scoped list is not a gate, so the gate is tested separately.
 *
 * ⚠️ Touches the database. Everything it makes is prefixed ZZCA and removed in
 * the cleanup at the bottom, including on failure.
 *
 * Run:  php tests/contract-assets.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/contract_assets.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
/** Assert that a call throws, which is how every guard reports a refusal. */
function refuses(string $what, callable $fn): void {
    try { $fn(); ok($what, false, 'it did NOT refuse'); }
    catch (Throwable $e) { ok($what, true); }
}

echo "\nAssets on contracts (#106)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$analystId = (int)$conn->query("SELECT id FROM analysts ORDER BY id LIMIT 1")->fetchColumn();
$madeAssets = [];
$madeContracts = [];

try {
    // ---- Fixtures ----------------------------------------------------------
    $conn->prepare("INSERT INTO contracts (contract_number, title, is_active, created_datetime)
                    VALUES ('ZZCA-001', 'ZZCA Mobile Service Agreement', 1, UTC_TIMESTAMP())")->execute();
    $contractId = (int)$conn->lastInsertId();
    $madeContracts[] = $contractId;

    $mkAsset = function (string $host) use ($conn, &$madeAssets): int {
        // No created_datetime: the assets table has no such column, and no
        // NOT NULL column without a default, so a hostname is enough.
        $conn->prepare("INSERT INTO assets (hostname) VALUES (?)")->execute([$host]);
        $id = (int)$conn->lastInsertId();
        $madeAssets[] = $id;
        return $id;
    };
    $phone = $mkAsset('ZZCA-handset-01');
    $sim   = $mkAsset('ZZCA-sim-01');
    $spare = $mkAsset('ZZCA-handset-02');

    // ---- 1. Linking --------------------------------------------------------
    echo "\nLinking equipment to a contract:\n";
    $linkA = contractAssetLink($conn, $analystId, $contractId, $phone, '07700 900123');
    ok('a link is created', $linkA > 0);

    $again = contractAssetLink($conn, $analystId, $contractId, $phone, '07700 900999');
    ok('linking the same asset twice does not make a second row', $again === $linkA);

    $rows = contractAssetsFor($conn, $analystId, $contractId);
    ok('and the second attempt updated the note instead',
       count($rows) === 1 && $rows[0]['reference'] === '07700 900999',
       json_encode(array_column($rows, 'reference')));

    contractAssetLink($conn, $analystId, $contractId, $sim, null);
    ok('a second asset appears on the contract',
       count(contractAssetsFor($conn, $analystId, $contractId)) === 2);

    // A note is optional, and an empty one is stored as nothing rather than ''.
    $simRow = null;
    foreach (contractAssetsFor($conn, $analystId, $contractId) as $r) {
        if ((int)$r['asset_id'] === $sim) { $simRow = $r; }
    }
    ok('a link with no note stores NULL, not an empty string',
       $simRow !== null && $simRow['reference'] === null);

    // ---- 2. The other direction -------------------------------------------
    echo "\nFrom the asset's side:\n";
    $forPhone = contractsForAsset($conn, $phone);
    ok('the asset knows which contract covers it', count($forPhone) === 1);
    ok('and carries the contract number and title',
       $forPhone && $forPhone[0]['contract_number'] === 'ZZCA-001'
                 && $forPhone[0]['title'] === 'ZZCA Mobile Service Agreement');
    ok('an unlinked asset has no contracts', contractsForAsset($conn, $spare) === []);

    // ---- 3. The picker -----------------------------------------------------
    echo "\nThe picker:\n";
    $found = contractAssetSearch($conn, $analystId, $contractId, 'ZZCA-handset');
    $ids   = array_column($found, 'asset_id');
    ok('it finds a matching asset', in_array($spare, $ids, true));
    ok('and EXCLUDES one already on the contract', !in_array($phone, $ids, true),
       'the linked handset was offered again');
    ok('a search matching nothing returns nothing',
       contractAssetSearch($conn, $analystId, $contractId, 'ZZCA-no-such-thing-here') === []);

    // ---- 4. The guards -----------------------------------------------------
    // The point of the file. Every one of these must refuse.
    echo "\nGuards (each of these must refuse):\n";
    refuses('linking to a contract that does not exist',
        fn() => contractAssetLink($conn, $analystId, 999999999, $spare, null));
    refuses('linking an asset that does not exist',
        fn() => contractAssetLink($conn, $analystId, $contractId, 999999999, null));
    refuses('removing a link that does not exist',
        fn() => contractAssetUnlink($conn, $analystId, 999999999));
    refuses('renaming a link that does not exist',
        fn() => contractAssetSetReference($conn, $analystId, 999999999, 'x'));
    refuses('loading a link that does not exist',
        fn() => contractAssetLoad($conn, $analystId, 999999999));

    // A positive control, so the four refusals above are not passing because
    // everything refuses.
    $loaded = contractAssetLoad($conn, $analystId, $linkA);
    ok('POSITIVE CONTROL: a real link still loads', (int)$loaded['id'] === $linkA);

    // ---- 5. A note that is too long, and one that is blank -----------------
    echo "\nThe note:\n";
    contractAssetSetReference($conn, $analystId, $linkA, str_repeat('x', 500));
    $long = contractAssetLoad($conn, $analystId, $linkA);
    $stored = $conn->prepare("SELECT reference FROM contract_assets WHERE id = ?");
    $stored->execute([$linkA]);
    ok('an over-long note is trimmed rather than rejected or truncated by MySQL',
       mb_strlen((string)$stored->fetchColumn()) === 190);

    contractAssetSetReference($conn, $analystId, $linkA, '   ');
    $stored->execute([$linkA]);
    ok('a note of only spaces is stored as nothing', $stored->fetchColumn() === null);

    // ---- 6. Unlinking destroys the link and NOTHING else -------------------
    echo "\nUnlinking:\n";
    contractAssetUnlink($conn, $analystId, $linkA);
    ok('the link is gone', count(contractAssetsFor($conn, $analystId, $contractId)) === 1);

    $stillThere = $conn->prepare("SELECT COUNT(*) FROM assets WHERE id = ?");
    $stillThere->execute([$phone]);
    ok('the ASSET survives', (int)$stillThere->fetchColumn() === 1);
    $stillThere = $conn->prepare("SELECT COUNT(*) FROM contracts WHERE id = ?");
    $stillThere->execute([$contractId]);
    ok('the CONTRACT survives', (int)$stillThere->fetchColumn() === 1);

    // ---- 7. Cascade --------------------------------------------------------
    // Not decoration: demo-data cleanup deletes parents and relies on the
    // cascade to take the links with them. Bypassing it once already outlived
    // an account and locked somebody out permanently.
    echo "\nDeleting a parent takes its links with it:\n";
    $conn->prepare("DELETE FROM assets WHERE id = ?")->execute([$sim]);
    $left = $conn->prepare("SELECT COUNT(*) FROM contract_assets WHERE asset_id = ?");
    $left->execute([$sim]);
    ok('deleting an asset removes its links', (int)$left->fetchColumn() === 0);

    contractAssetLink($conn, $analystId, $contractId, $spare, 'seat 4');
    $conn->prepare("DELETE FROM contracts WHERE id = ?")->execute([$contractId]);
    $left = $conn->prepare("SELECT COUNT(*) FROM contract_assets WHERE contract_id = ?");
    $left->execute([$contractId]);
    ok('deleting a contract removes its links', (int)$left->fetchColumn() === 0);

} finally {
    // ---- Cleanup -----------------------------------------------------------
    try {
        $conn->prepare("DELETE FROM assets WHERE hostname LIKE 'ZZCA-%'")->execute();
        $conn->prepare("DELETE FROM contracts WHERE contract_number LIKE 'ZZCA-%'")->execute();
        $leftA = (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname LIKE 'ZZCA-%'")->fetchColumn();
        $leftC = (int)$conn->query("SELECT COUNT(*) FROM contracts WHERE contract_number LIKE 'ZZCA-%'")->fetchColumn();
        echo "\n  cleanup: " . ($leftA + $leftC === 0 ? "no ZZCA rows left\n" : "⚠️  {$leftA} assets / {$leftC} contracts REMAIN\n");
    } catch (Exception $e) {
        echo "\n  ⚠️  cleanup failed: " . $e->getMessage() . "\n";
    }
}

echo str_repeat('=', 70) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);
