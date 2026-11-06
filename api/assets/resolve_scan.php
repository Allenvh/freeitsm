<?php
/**
 * API: resolve a scanned FreeITSM asset label to the asset it names.
 *
 * GET ?token=<qr_token>  ->  { success, asset|null, matches }
 *
 * The camera scanner (asset-management/scanner.php) decodes a QR into a URL of
 * the form `<base>/a/<token>`. Opening that URL is the single-asset journey;
 * this endpoint is the *continuous* one, where the scanner stays on screen and
 * only needs to know which asset was just seen.
 *
 * The response shape deliberately matches find_asset.php, because the scanner
 * calls whichever fits what it decoded — our own label, or the manufacturer's
 * serial barcode — and must not care which answered.
 *
 * SECURITY. Identical rules to scan.php, and for the same reasons:
 *   - the token is not a password, it is an unguessable name for a row, so a
 *     login and module access are still required;
 *   - an unknown token and a token belonging to a company this analyst cannot
 *     see return the SAME answer, or a label becomes an oracle for "does this
 *     asset exist somewhere on this install?".
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
    $token = trim((string)($_GET['token'] ?? ''));
    if ($token === '') throw new Exception('Nothing to look up');

    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    // Before the #935 schema exists there are no tokens to resolve. Say so
    // plainly rather than returning "not recognised", which would send someone
    // hunting for a bad label when the real answer is "run Database Verify".
    if (!assetLabelsSchemaReady($conn)) {
        echo json_encode(['success' => false, 'error' => 'Asset labels need a database update — an administrator can run System → Database Verification.']);
        exit;
    }

    $assetId = assetIdForToken($conn, $token);

    // The two failures are answered identically on purpose — see the note above.
    if ($assetId === null || !analystCanAccessAsset($conn, $analystId, $assetId)) {
        echo json_encode(['success' => true, 'asset' => null, 'matches' => 0]);
        exit;
    }

    // The same columns find_asset.php returns, so the scanner renders one card.
    $stmt = $conn->prepare(
        "SELECT a.id, a.hostname, a.service_tag, a.asset_tag,
                a.asset_status_id, a.location_id,
                t.name AS type_name, s.name AS status_name, l.name AS location_name
           FROM assets a
           LEFT JOIN asset_types        t ON t.id = a.asset_type_id
           LEFT JOIN asset_status_types s ON s.id = a.asset_status_id
           LEFT JOIN asset_locations    l ON l.id = a.location_id
          WHERE a.id = ?"
    );
    $stmt->execute([$assetId]);
    $asset = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    echo json_encode(['success' => true, 'asset' => $asset, 'matches' => $asset ? 1 : 0]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
