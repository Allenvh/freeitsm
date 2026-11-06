<?php
/**
 * Domains — the start of every api/domains/*.php endpoint.
 *
 * Signed in, allowed into the module, JSON out, a connection and an actor.
 * Kept here rather than repeated fifteen times so a guard cannot be forgotten
 * on the sixteenth. Capabilities (settings tabs, auth codes) are checked by the
 * endpoint that needs them, AFTER this, with domainRequireCap().
 *
 * Lives in includes/ so it cannot be requested on its own.
 */

session_start(['read_and_close' => true]);
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../rbac.php';
require_once __DIR__ . '/../services/domains.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('domains');

$conn      = connectToDatabase();
$analystId = (int)$_SESSION['analyst_id'];
$ctx       = ActorContext::fromSession($conn);

/** The decoded JSON body (never null). */
function domainApiBody(): array
{
    static $b = null;
    if ($b === null) {
        $raw = file_get_contents('php://input');
        $b = $raw !== '' ? (json_decode($raw, true) ?: []) : [];
    }
    return $b;
}

/**
 * Sends {success: true} merged with $data as JSON and ends the request.
 *
 * @param array $data  extra keys to send alongside success
 */
function domainApiOk(array $data = []): never
{
    echo json_encode(['success' => true] + $data);
    exit;
}

/**
 * Sends {success: false, error} as JSON, with an HTTP status when one other than 200 is given, and ends the request.
 *
 * @param string $message  the error text, shown to the user as it stands
 * @param int    $status   HTTP status code; 200 leaves the status alone
 */
function domainApiFail(string $message, int $status = 200): never
{
    if ($status !== 200) http_response_code($status);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

/**
 * Stops the request with a JSON refusal unless the signed-in analyst holds the capability.
 *
 * @param Cap $cap  a capability, normally Cap::DOMAINS_*
 */
function domainRequireCap(PDO $conn, Cap $cap): void
{
    requireCapabilityJson($cap, $conn);
}

/**
 * True when the analyst holds the capability; false on any error.
 *
 * @param Cap $cap  a capability, normally Cap::DOMAINS_*
 */
function domainHasCap(PDO $conn, int $analystId, Cap $cap): bool
{
    try { return analystHasCapability($conn, $analystId, $cap); } catch (Throwable) { return false; }
}

/**
 * The company a NEW record belongs to: the one the request names (checked
 * against what this analyst may reach), else the analyst's active company.
 *
 * @param mixed $requested  the company_id from the request body (untrusted); null, '' or 0 means the active company
 */
function domainApiTenantForCreate(PDO $conn, int $analystId, $requested): int
{
    if ($requested !== null && $requested !== '' && (int)$requested > 0 && isMultiTenant($conn)) {
        $t = (int)$requested;
        if (!getTenantById($conn, $t) || !analystCanAccessTenant($conn, $analystId, $t)) {
            domainApiFail('You cannot add domains for that company.');
        }
        return $t;
    }
    return getActiveTenantId($conn, $analystId);
}

/**
 * Run a service call and turn any failure into the UI's {success:false} shape.
 *
 * @param callable $fn  the endpoint's work - takes no arguments and normally ends with domainApiOk()
 */
function domainApiRun(callable $fn): void
{
    try {
        $fn();
    } catch (ServiceError $e) {
        domainApiFail($e->getMessage());
    } catch (Throwable $e) {
        error_log('domains api: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
        domainApiFail('Something went wrong: ' . $e->getMessage());
    }
}
