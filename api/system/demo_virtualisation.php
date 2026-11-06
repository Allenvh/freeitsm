<?php
/**
 * API: System -> Demo Data -> Virtualisation (GH #199, #201).
 *
 * GET                       what of the demo is in place (counts)
 * POST action=import        builds it, replacing an earlier copy
 * POST action=remove        removes it and nothing else
 *
 * Code-driven rather than a JSON file - see includes/demo_virtualisation.php.
 *
 * @wiki Hyper-V-Discovery
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';
require_once '../../includes/demo_virtualisation.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }

try {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
        echo json_encode(['success' => true, 'counts' => demoVirtStatus($conn)]);
        exit;
    }
    // Changes data: never on a GET, which a link on another site can send (csrf.php).
    csrfRequireWriteMethod();
    $ctx = ActorContext::fromSession($conn);
    switch ($_POST['action'] ?? '') {
        case 'import':
            echo json_encode(['success' => true, 'counts' => demoVirtImport($conn, $ctx)]);
            break;
        case 'remove':
            demoVirtRemove($conn, $ctx);
            echo json_encode(['success' => true, 'counts' => demoVirtStatus($conn)]);
            break;
        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) { $conn->rollBack(); }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
