<?php
/**
 * API: Compute the "blast radius" for an object — what would be affected if
 * this object went offline / was deleted.
 *
 * Returns two views of the same estate, computed by includes/cmdb_impact.php:
 *
 *   impact.*        — the three one-hop buckets (descendants,
 *                     referenced_by_property, referenced_by_relationship).
 *                     Shape frozen; the REST API publishes it.
 *   blast_radius.*  — the TRANSITIVE view: everything ultimately affected,
 *                     following only edges configured to carry impact, with
 *                     each object's shortest hop count and how it was reached.
 *
 * Used by:
 *   - The Impact panel on the object detail page
 *   - The AI summary generator (so the prose can say "X databases depend on this")
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/cmdb_impact.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

requireModuleAccessJson('cmdb');

try {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) throw new Exception('id is required');

    $conn = connectToDatabase();

    // Company gate — impact walks descendants and inbound references, so an
    // ungated call here would enumerate another company's estate through the
    // blast-radius panel.
    if (!analystCanAccessCmdbObject($conn, (int) $_SESSION['analyst_id'], $id)) {
        echo json_encode(['success' => false, 'error' => 'Object not found']);
        exit;
    }

    // Both views come from the shared engine so this endpoint, the REST API and
    // the AI summary can never drift apart.
    $impact = cmdbDirectImpact($conn, $id);
    $blast  = cmdbBlastRadius($conn, $id, cmdbImpactTenantScope($conn, $id));

    echo json_encode([
        'success'      => true,
        'impact'       => $impact,
        'blast_radius' => $blast,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
