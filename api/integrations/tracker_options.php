<?php
/**
 * What does this tracker offer? Projects, issue types and priorities, live from
 * the provider — the lists the mapping screen turns into dropdowns.
 *
 * Until now nothing called listProjects()/listIssueTypes() over HTTP at all:
 * they existed on the connector and were exercised only by tests, which is why
 * the workflow action still asks an admin to type a project key by hand.
 *
 * ⚠️ Every call here reaches out to somebody else's API, so it is deliberately
 * on demand (the screen asks when it needs a list) rather than on page load.
 *
 * GET ?connection_id=8&what=projects
 *     ?connection_id=8&what=issue_types&project=KAN
 *     ?connection_id=8&what=priorities
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/encryption.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/integrations/integrations.php';

header('Content-Type: application/json');

$conn = connectToDatabase();

if (!integrationsSchemaReady($conn)) {
    echo json_encode(['success' => false, 'error' => 'Run Database Verification first.']);
    exit;
}

$connectionId = (int)($_GET['connection_id'] ?? 0);
$what         = (string)($_GET['what'] ?? 'projects');

$connection = integrationsLoadConnection($conn, $connectionId);
if (!$connection) {
    echo json_encode(['success' => false, 'error' => 'That connection no longer exists.']);
    exit;
}

try {
    $provider = integrationsProviderFor($connection);

    switch ($what) {
        case 'projects':
            $items = $provider->listProjects();
            break;

        case 'issue_types':
            $project = trim((string)($_GET['project'] ?? ''));
            if ($project === '') {
                echo json_encode(['success' => false, 'error' => 'Choose a project first — issue types depend on it.']);
                exit;
            }
            $items = $provider->listIssueTypes($project);
            break;

        case 'priorities':
            // Not every tracker has priorities at all, and supports() is how the
            // UI is meant to find that out — never a thrown exception.
            if (!$provider->supports(IssueTrackerProvider::CAP_PRIORITIES)) {
                echo json_encode(['success' => true, 'items' => [], 'unsupported' => true]);
                exit;
            }
            $items = $provider->listPriorities();
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Unknown option list.']);
            exit;
    }

    echo json_encode(['success' => true, 'items' => $items]);

} catch (Exception $e) {
    // The provider's own message is the useful one — "HTTP 400" helps nobody.
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
