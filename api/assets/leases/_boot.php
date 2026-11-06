<?php
/**
 * Shared start for the Assets -> Leases endpoints (3.5.0): signed in, has the
 * Assets module, JSON out. The rules - who may see which lease, what may go on
 * one - are all in includes/leases.php; these files only adapt requests to it.
 *
 *   leaseEndpoint('GET'|'POST', function (PDO $conn, ActorContext $ctx, array $in) { return [...]; });
 *
 * $in is the JSON body for POST, $_GET for GET. A ServiceError becomes
 * {success:false, error}; anything else is logged and reported generically.
 */
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === '_boot.php') { http_response_code(404); exit; }

session_start(['read_and_close' => true]);
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/tenancy.php';
require_once __DIR__ . '/../../../includes/leases.php';

/**
 * Runs one Leases endpoint: checks sign-in, Assets module access, the HTTP method and
 * that the lease tables exist, then calls $fn and echoes its result as JSON.
 *
 * A ServiceError is shown to the caller as its message; any other exception is logged
 * and reported as a generic error.
 *
 * @param string   $method  'GET' or 'POST' - anything else is refused with HTTP 405
 * @param callable $fn      function (PDO $conn, ActorContext $ctx, array $in): ?array - its array is merged into {success: true}
 */
function leaseEndpoint(string $method, callable $fn): void
{
    header('Content-Type: application/json');
    if (!isset($_SESSION['analyst_id'])) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        return;
    }
    requireModuleAccessJson('assets');
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
        return;
    }
    try {
        $conn = connectToDatabase();
        if (!leaseReady($conn)) {
            echo json_encode(['success' => false, 'error' => 'Run Database Verification (System -> Database) to add leases.']);
            return;
        }
        $in = $method === 'POST' ? (json_decode(file_get_contents('php://input'), true) ?: []) : $_GET;
        $out = $fn($conn, ActorContext::fromSession($conn), $in);
        echo json_encode(['success' => true] + ($out ?? []));
    } catch (ServiceError $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    } catch (Throwable $e) {
        error_log('Leases endpoint ' . basename($_SERVER['SCRIPT_NAME'] ?? '') . ': ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Something went wrong. The details are in the PHP error log.']);
    }
}
