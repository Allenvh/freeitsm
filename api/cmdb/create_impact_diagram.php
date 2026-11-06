<?php
/**
 * API: Turn an object's blast radius into a Network Mapper diagram.
 *
 * WHY THIS RATHER THAN A SECOND GRAPH
 * -----------------------------------
 * The obvious way to visualise a blast radius is to draw one, on the object
 * page. That would mean a second node-graph renderer one module away from the
 * one that already exists — and the read-only picture would evaporate the
 * moment you navigated away.
 *
 * Network Mapper already draws, saves, versions and exports diagrams of exactly
 * these objects. So the blast radius hands off to it instead: same data, no new
 * renderer, no charting dependency, and the result is an artefact you can
 * rearrange, annotate and attach to a change or an incident review.
 *
 * The two modules stay distinct in purpose. Network Mapper authors a picture a
 * human composes; the blast radius answers a question about right now. This
 * endpoint is the bridge — it gives the authoring tool a sensible starting
 * layout for a question that has just been answered.
 *
 * WHAT IT DOES
 * ------------
 * Runs the walk, lays the result out in rings by hop distance (so the geometry
 * carries the same meaning the grouped list does), and creates the diagram
 * through NetworkMapperService so every existing write rule still applies.
 * Connectors follow the impact edges and carry cmdb_relationship_id where the
 * edge is a real relationship row, so provenance matches what the module's own
 * "add related objects" flow produces.
 *
 * POST { object_id: N }  ->  { success, diagram_id }
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/cmdb_impact.php';
require_once '../../includes/service_context.php';
require_once '../../includes/services/network_mapper.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// BOTH modules. Reading a blast radius is a CMDB right; creating a diagram is a
// Network Mapper one, and this endpoint does the second on the strength of the
// first. Checking only 'cmdb' would let an analyst with no Network Mapper access
// create diagrams through the side door.
requireModuleAccessJson('cmdb');
requireModuleAccessJson('network-mapper');

/** Where the failed object sits on the canvas. */
const IMPACT_DIAGRAM_ORIGIN_X = 700;
const IMPACT_DIAGRAM_ORIGIN_Y = 480;
/** Distance between hop rings. Wide enough that labels don't collide at 8-10 per ring. */
const IMPACT_DIAGRAM_RING_GAP = 230;

try {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $objectId = isset($in['object_id']) ? (int) $in['object_id'] : 0;
    if ($objectId <= 0) throw new Exception('object_id is required');

    $conn = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];

    if (!analystCanAccessCmdbObject($conn, $analystId, $objectId)) {
        echo json_encode(['success' => false, 'error' => 'Object not found']);
        exit;
    }

    $rootStmt = $conn->prepare("SELECT id, name FROM cmdb_objects WHERE id = ?");
    $rootStmt->execute([$objectId]);
    $root = $rootStmt->fetch(PDO::FETCH_ASSOC);
    if (!$root) throw new Exception('Object not found');

    $blast = cmdbBlastRadius($conn, $objectId, cmdbImpactTenantScope($conn, $objectId));
    if (!$blast['nodes']) {
        // Nothing to draw. Say so rather than creating an empty diagram the
        // analyst then has to find and delete.
        echo json_encode(['success' => false, 'error' => 'empty_blast_radius']);
        exit;
    }

    // ---- Ring layout ------------------------------------------------------
    // The root sits at the centre and each hop is a ring further out, so the
    // picture encodes the same thing the grouped list says in words. Nodes are
    // spread evenly around their ring; the starting angle is offset per ring so
    // rings don't line up radially and overlap their labels.
    $nodes = [['cmdb_object_id' => (int) $root['id'],
               'x' => IMPACT_DIAGRAM_ORIGIN_X, 'y' => IMPACT_DIAGRAM_ORIGIN_Y,
               'size' => 'large', 'ref' => 'o' . (int) $root['id']]];

    $byDepth = [];
    foreach ($blast['nodes'] as $n) { $byDepth[$n['depth']][] = $n; }

    foreach ($byDepth as $depth => $ring) {
        $count  = count($ring);
        $radius = IMPACT_DIAGRAM_RING_GAP * $depth;
        // A ring of one looks wrong directly above the root; nudge it.
        $offset = ($depth % 2 === 0) ? (M_PI / $count) : 0;
        foreach (array_values($ring) as $i => $n) {
            $angle = $offset + (2 * M_PI * $i / $count) - (M_PI / 2);
            $nodes[] = [
                'cmdb_object_id' => (int) $n['id'],
                'x'    => (int) round(IMPACT_DIAGRAM_ORIGIN_X + $radius * cos($angle)),
                'y'    => (int) round(IMPACT_DIAGRAM_ORIGIN_Y + $radius * sin($angle)),
                'size' => 'medium',
                'ref'  => 'o' . (int) $n['id'],
            ];
        }
    }

    // ---- Connectors -------------------------------------------------------
    // One per node, from whatever it was reached through — which is exactly the
    // edge the walk followed, so the drawing cannot claim a path the engine did
    // not take. Dashed for containment and property links to distinguish them
    // from explicit relationships at a glance.
    $connectors = [];
    foreach ($blast['nodes'] as $n) {
        $connectors[] = [
            'from_ref'             => 'o' . (int) $n['via_from'],
            'to_ref'               => 'o' . (int) $n['id'],
            'cmdb_relationship_id' => $n['via_rel_id'] ?? null,
            'label'                => $n['via_label'] ?: null,
            'line_style'           => $n['via_kind'] === 'relationship' ? 'solid' : 'dashed',
        ];
    }

    $title = 'Impact: ' . $root['name'];
    if ($blast['truncated']) {
        // The diagram is a partial picture; say so in the thing people will read.
        $title .= ' (partial)';
    }

    $diagramId = NetworkMapperService::createDiagram($conn, ActorContext::fromSession($conn), [
        'title'       => $title,
        'description' => 'Generated from the blast radius of "' . $root['name'] . '" — '
                       . count($blast['nodes']) . ' affected objects, '
                       . $blast['max_depth_reached'] . ' hops deep.'
                       . ($blast['truncated'] ? ' The estate is larger than this; only the closest results are shown.' : ''),
        'nodes'       => $nodes,
        'connectors'  => $connectors,
    ]);

    echo json_encode(['success' => true, 'diagram_id' => (int) $diagramId,
                      'node_count' => count($nodes), 'truncated' => $blast['truncated']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
