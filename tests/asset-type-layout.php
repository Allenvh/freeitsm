<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Asset type layouts (GH #200, part A): which built-in sections and tabs a type shows.
 *
 *   php tests/asset-type-layout.php
 *
 * Why it exists: the storage rule is "no row means shown", and everything that keeps old
 * installs unchanged depends on it - a type nobody configured must store nothing, a layout put
 * back to the default must store nothing again, and a tab added in a later release must appear
 * on every type, including ones whose layout was saved before it existed. Those are easy to
 * break while making the editor nicer, and nothing on screen would show it until an upgrade.
 *
 * Runs against the real database inside ONE transaction that is rolled back, so the ZZ type it
 * makes never outlives the run.
 */

chdir(__DIR__ . '/..');
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/services/asset_type_layout.php';

$pass = 0; $fail = 0;
/**
 * Records one check.
 *
 * @param string $what  what is being proved
 * @param bool $cond  true when it holds
 * @param string $detail  shown on failure
 */
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->beginTransaction();
try {
    $conn->prepare("INSERT INTO asset_types (name, description, display_order, is_active) VALUES (?, '', 99, 1)")
         ->execute(['ZZ Layout ' . bin2hex(random_bytes(3))]);
    $typeId = (int)$conn->lastInsertId();
    $rows = function () use ($conn, $typeId): int {
        $s = $conn->prepare("SELECT COUNT(*) FROM asset_type_layout WHERE asset_type_id = ?");
        $s->execute([$typeId]);
        return (int)$s->fetchColumn();
    };

    echo "A type nobody configured\n";
    $L = AssetTypeLayout::forTypes($conn, [$typeId])[$typeId];
    ok('shows every section', !in_array(false, $L['sections'], true) && array_keys($L['sections']) === AssetTypeLayout::SECTIONS);
    ok('shows every tab, in the default order', array_column($L['tabs'], 'key') === AssetTypeLayout::TABS && !in_array(false, array_column($L['tabs'], 'shown'), true));

    echo "Saving a layout\n";
    $monitor = ['sections' => ['hardware' => false, 'os' => false],
                'tabs' => [['key' => 'documents', 'shown' => true], ['key' => 'devices', 'shown' => false], ['key' => 'software', 'shown' => false]]];
    AssetTypeLayout::save($conn, $typeId, $monitor);
    $L = AssetTypeLayout::forTypes($conn, [$typeId])[$typeId];
    ok('the hidden sections come back hidden', $L['sections']['hardware'] === false && $L['sections']['os'] === false && $L['sections']['make_model'] === true);
    ok('the tab order comes back', array_column($L['tabs'], 'key')[0] === 'documents', json_encode(array_column($L['tabs'], 'key')));
    ok('tabs not sent are kept, shown, after', count($L['tabs']) === count(AssetTypeLayout::TABS)
       && array_column($L['tabs'], 'shown', 'key')['tickets'] === true);
    ok('the hidden tabs come back hidden', array_column($L['tabs'], 'shown', 'key')['devices'] === false);

    echo "Back to the default\n";
    AssetTypeLayout::save($conn, $typeId, AssetTypeLayout::defaults());
    ok('stores no rows, so the type counts as never configured', $rows() === 0, (string)$rows());

    echo "A tab added in a later release\n";
    // A layout saved before 'projects' existed: every other tab stored, projects not.
    AssetTypeLayout::save($conn, $typeId, $monitor);
    $conn->prepare("DELETE FROM asset_type_layout WHERE asset_type_id = ? AND item_key = 'projects'")->execute([$typeId]);
    $L = AssetTypeLayout::forTypes($conn, [$typeId])[$typeId];
    ok('still appears, shown', in_array('projects', array_column($L['tabs'], 'key'), true) && array_column($L['tabs'], 'shown', 'key')['projects'] === true);
    // A tab a later release removed: a stored key nobody knows any more.
    $conn->prepare("INSERT INTO asset_type_layout (asset_type_id, item_kind, item_key, is_shown, sort_order) VALUES (?, 'tab', 'zz_gone', 0, 0)")->execute([$typeId]);
    $L = AssetTypeLayout::forTypes($conn, [$typeId])[$typeId];
    ok('an unknown stored key is ignored', !in_array('zz_gone', array_column($L['tabs'], 'key'), true));

    echo "What a request can send\n";
    $n = AssetTypeLayout::normalise(['sections' => ['hardware' => false, 'identity' => false, 'zz' => false],
                                     'tabs' => [['key' => 'keyinfo', 'shown' => false], ['key' => 'software', 'shown' => false], ['key' => 'software', 'shown' => true], 'nonsense']]);
    ok('identity cannot be hidden - it is not a section', !isset($n['sections']['identity']) && !isset($n['sections']['zz']));
    ok('Key info cannot be hidden or moved - it is not a tab', !in_array('keyinfo', array_column($n['tabs'], 'key'), true));
    ok('a tab sent twice counts once, first wins', count(array_keys(array_column($n['tabs'], 'key'), 'software')) === 1 && array_column($n['tabs'], 'shown', 'key')['software'] === false);
    ok('garbage is everything shown, not an error', AssetTypeLayout::normalise('zz') == AssetTypeLayout::defaults() && AssetTypeLayout::normalise(null) == AssetTypeLayout::defaults());

    echo "Presets\n";
    $bad = [];
    foreach (AssetTypeLayout::PRESETS as $name => $p) {
        foreach ($p['hide_sections'] as $s) if (!in_array($s, AssetTypeLayout::SECTIONS, true)) $bad[] = "$name:$s";
        foreach ($p['hide_tabs'] as $t) if (!in_array($t, AssetTypeLayout::TABS, true)) $bad[] = "$name:$t";
    }
    ok('only name sections and tabs that exist', !$bad, implode(', ', $bad));
    ok('"everything" hides nothing', AssetTypeLayout::PRESETS['everything'] === ['hide_sections' => [], 'hide_tabs' => []]);

    echo "Deleting the type\n";
    AssetTypeLayout::save($conn, $typeId, $monitor);
    $conn->prepare("DELETE FROM asset_types WHERE id = ?")->execute([$typeId]);
    ok('takes its layout with it', $rows() === 0);
} finally {
    $conn->rollBack();
}

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
