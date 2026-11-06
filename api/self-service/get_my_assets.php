<?php
/**
 * API: The equipment assigned to the signed-in portal user (discussion #57).
 *
 * ⚠️ This endpoint is the whole of the portal's permission rule, and it is a
 * deliberately narrow one: a portal user may only ever attach equipment that is
 * assigned to THEM. There is no search, no browsing, and no id parameter — the
 * user is taken from the session and nothing else is reachable.
 *
 * That does leave shared equipment out of a user's reach: nobody is assigned the
 * TV in a meeting room, so it cannot be picked here. That is the accepted
 * behaviour, not an oversight — the alternatives (exposing every unassigned
 * asset, or asking end users to understand a "shared" flag) were judged more
 * confusing and more leaky than letting an analyst attach it afterwards.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['ss_user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$userId = (int)$_SESSION['ss_user_id'];

try {
    $conn = connectToDatabase();
    // GH #217: nothing from a module switched off for the install.
    requireModuleOnJson($conn, 'assets');

    // INNER JOIN, deliberately: users_assets carries no foreign key on asset_id
    // and there are orphan rows on real installs, which would otherwise come
    // back as blank entries in the dropdown.
    $stmt = $conn->prepare(
        "SELECT a.id AS asset_id, a.hostname, a.manufacturer, a.model,
                a.service_tag, a.asset_tag,
                ty.name AS type_name
           FROM users_assets ua
           JOIN assets a          ON a.id = ua.asset_id
      LEFT JOIN asset_types ty    ON ty.id = a.asset_type_id
          WHERE ua.user_id = ?
       ORDER BY ty.name, a.hostname, a.asset_tag"
    );
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$r) { $r['asset_id'] = (int)$r['asset_id']; }

    echo json_encode(['success' => true, 'assets' => $rows]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Could not load your equipment']);
}
