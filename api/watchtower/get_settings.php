<?php
/**
 * API Endpoint: Watchtower settings — the cards, and what each count includes.
 *
 * Returns every choosable member alongside the current selection, so the screen
 * never has to know the names of statuses in advance — the whole point of the
 * exercise. `customised = false` means "following the built-in default", which
 * is deliberately different from "customised with nothing selected".
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/watchtower_settings.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('watchtower');

try {
    $conn = connectToDatabase();

    $items = [];
    foreach (wtSelectableItems() as $key => $spec) {
        $rows = $conn->query(
            "SELECT `{$spec['id']}` AS id, `{$spec['name']}` AS name
               FROM `{$spec['table']}`
              WHERE {$spec['where']}
              ORDER BY {$spec['order']}"
        )->fetchAll(PDO::FETCH_ASSOC);

        $selected = wtItemMembers($conn, $key);
        $items[$key] = [
            'entity_type' => $spec['entity_type'],
            'customised'  => $selected !== null,
            'selected'    => $selected ?? [],
            'options'     => array_map(fn($r) => ['id' => (int)$r['id'], 'name' => $r['name']], $rows),
        ];
    }

    // The paused-too-long threshold. It has been read on every dashboard load
    // since it was added and has never had anywhere to be set — this screen is
    // the first place it can be changed without editing the database by hand.
    $paused = 24;
    try {
        $v = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'watchtower_paused_too_long_hours' LIMIT 1")->fetchColumn();
        if ($v !== false && (int)$v > 0) $paused = (int)$v;
    } catch (Exception $e) { /* default stands */ }

    echo json_encode([
        'success'        => true,
        'cards'          => wtVisibleCards($conn),
        'card_keys'      => wtCardKeys(),                    // the default order (Reset)
        'card_order'     => wtCardOrder($conn),              // the install's order (GH #187)
        'card_order_custom' => wtCardOrderSaved($conn) !== [],
        // key => module colour key, from the one card list (wtCardRegistry).
        'card_modules'   => array_map(fn($c) => $c['module'], wtCardRegistry()),
        'items'          => $items,
        'paused_hours'   => $paused,
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
