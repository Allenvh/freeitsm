<?php
/**
 * API: Asset search for the "Link equipment" picker on a ticket (discussion #57).
 * GET ?ticket_id=&q=
 *
 * Returns two lists. The ticket requester's own assets come back separately and
 * are shown first, because "my monitor is flickering" is nearly always their own
 * monitor — but the full estate stays searchable, because it often isn't. The
 * worked example from #57 is a user reporting the TV in a meeting room: nobody
 * holds it, so it can only ever be found by searching.
 *
 * ⚠️ Location is searched as well as hostname/model/serial/tag, and that is not
 * a nicety. Nobody knows the hostname of a meeting-room TV; they know where it
 * is. Without it there is nothing useful to type for shared equipment.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
// GH #217: refused while the assets module is switched off for the install.
requireModuleOnJson(connectToDatabase(), 'assets');

const LINKABLE_ASSET_LIMIT = 25;

try {
    $ticketId = isset($_GET['ticket_id']) ? (int)$_GET['ticket_id'] : 0;
    $q        = trim((string)($_GET['q'] ?? ''));
    if ($ticketId <= 0) throw new Exception('ticket_id is required');

    $conn      = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
        throw new Exception('Ticket not found');
    }

    // Scope every asset read to the companies this analyst can reach. This is the
    // list, not the check — save_ticket_asset.php re-validates on write, because a
    // scoped list has never been a substitute for a gate.
    [$tAsset, $aAsset] = activeTenantFilter($conn, $analystId, 'a');

    $select =
        "SELECT a.id AS asset_id, a.hostname, a.manufacturer, a.model,
                a.service_tag, a.asset_tag,
                ty.name AS type_name, l.name AS location_name
           FROM assets a
      LEFT JOIN asset_types ty     ON ty.id = a.asset_type_id
      LEFT JOIN asset_locations l  ON l.id = a.location_id";

    // Assets already on this ticket are excluded from both lists — offering
    // something that is already attached is just a way to produce a no-op.
    $notAlready = " AND a.id NOT IN (SELECT asset_id FROM ticket_assets WHERE ticket_id = ?)";

    // ── The requester's own equipment ────────────────────────────────────────
    // INNER JOIN, deliberately: users_assets carries no foreign key on asset_id
    // and there are orphan rows, which would otherwise render as blank entries.
    $mine = [];
    $req  = $conn->prepare("SELECT user_id FROM tickets WHERE id = ?");
    $req->execute([$ticketId]);
    $requesterId = $req->fetchColumn();

    if ($requesterId) {
        $stmt = $conn->prepare(
            $select . " JOIN users_assets ua ON ua.asset_id = a.id AND ua.user_id = ?
                        WHERE 1=1" . $tAsset . $notAlready . "
                     ORDER BY a.hostname, a.asset_tag
                        LIMIT " . LINKABLE_ASSET_LIMIT
        );
        $stmt->execute(array_merge([(int)$requesterId], $aAsset, [$ticketId]));
        $mine = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Everything else, when they type ──────────────────────────────────────
    $others = [];
    if ($q !== '') {
        $like  = '%' . $q . '%';
        $where = " WHERE (a.hostname LIKE ? OR a.manufacturer LIKE ? OR a.model LIKE ?
                          OR a.service_tag LIKE ? OR a.asset_tag LIKE ? OR l.name LIKE ?)";
        $args  = array_fill(0, 6, $like);

        // Don't repeat what is already in the requester's list above it.
        $exclude = '';
        if ($mine) {
            $ids     = array_map(fn($r) => (int)$r['asset_id'], $mine);
            $exclude = " AND a.id NOT IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";
            $args    = array_merge($args, $ids);
        }

        $stmt = $conn->prepare(
            $select . $where . $tAsset . $exclude . $notAlready . "
                     ORDER BY a.hostname, a.asset_tag
                        LIMIT " . LINKABLE_ASSET_LIMIT
        );
        $stmt->execute(array_merge($args, $aAsset, [$ticketId]));
        $others = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $coerce = function (&$rows) {
        foreach ($rows as &$r) { $r['asset_id'] = (int)$r['asset_id']; }
    };
    $coerce($mine);
    $coerce($others);

    echo json_encode([
        'success'   => true,
        'requester' => $mine,
        'others'    => $others,
        'limit'     => LINKABLE_ASSET_LIMIT,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
