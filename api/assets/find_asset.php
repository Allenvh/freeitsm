<?php
/**
 * API: find ONE asset by something you can scan or read off it.
 *
 * GET ?q=<serial | hostname | asset tag> -> { success, asset|null, matches }
 *
 * Built for the tagging loop (assign-tags.php), where the input comes from a
 * barcode scanner pointed at the manufacturer's own serial sticker — the label
 * that is already on nearly every piece of kit. Exact matches only: a fuzzy
 * result would have somebody tag the wrong laptop, and the whole point of the
 * exercise is that the number on the box matches the number in the database.
 *
 * Ordered so the most deliberate identifier wins: asset tag (someone typed it),
 * then serial (the manufacturer's), then hostname.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/asset_labels.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('assets');

try {
    $q = trim((string)($_GET['q'] ?? ''));
    if ($q === '') throw new Exception('Nothing to look up');

    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    // Same company scope as the asset list, so this can't be used to discover
    // that a serial exists in a company you can't see.
    [$tSql, $tArgs] = activeTenantFilter($conn, $analystId, 'a');

    $ready     = assetLabelsSchemaReady($conn);
    $tagSelect = $ready ? 'a.asset_tag' : 'NULL AS asset_tag';
    $tagMatch  = $ready ? ' OR a.asset_tag = ?' : '';

    // Deliberately NO ordering placeholders in the SQL. Ranking in the query
    // would put more `?` after the tenant fragment's, and positional binding is
    // strictly left-to-right — so the argument list would have to be spliced
    // around it, which is exactly the sort of thing that silently binds the
    // wrong value later. Five rows is nothing to sort in PHP.
    // asset_status_id / location_id are returned alongside the display names so
    // a caller can tell "this is already set to what I'm applying" from "this
    // needs changing" without a second round trip — the camera scanner's
    // already-set check and its undo both need the value that was there before.
    // Additive: assign-tags.php ignores what it doesn't use.
    $sql = "SELECT a.id, a.hostname, a.service_tag, $tagSelect,
                   a.asset_status_id, a.location_id,
                   t.name AS type_name, s.name AS status_name, l.name AS location_name
              FROM assets a
              LEFT JOIN asset_types        t ON t.id = a.asset_type_id
              LEFT JOIN asset_status_types s ON s.id = a.asset_status_id
              LEFT JOIN asset_locations    l ON l.id = a.location_id
             WHERE (a.service_tag = ? OR a.hostname = ?$tagMatch)" . $tSql . "
             LIMIT 5";

    $args = $ready ? [$q, $q, $q] : [$q, $q];
    $stmt = $conn->prepare($sql);
    $stmt->execute(array_merge($args, $tArgs));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Most deliberate identifier first: a tag someone typed, then the
    // manufacturer's serial, then the hostname.
    $rank = function (array $r) use ($q) {
        if (($r['asset_tag'] ?? null) === $q)   return 0;
        if (($r['service_tag'] ?? null) === $q) return 1;
        return 2;
    };
    usort($rows, fn($a, $b) => $rank($a) <=> $rank($b));

    echo json_encode([
        'success' => true,
        'asset'   => $rows[0] ?? null,
        'matches' => count($rows),
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
