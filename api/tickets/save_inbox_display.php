<?php
/**
 * API Endpoint: save what the inbox row shows.
 *
 * Two different things behind one endpoint, with two different permissions:
 *
 *   scope = "me"      this analyst's own preference. No capability — it is a view
 *                     preference, and gating it would mean asking an administrator
 *                     for permission to find your own queue readable.
 *   scope = "install" the default for everyone who has not chosen. That IS
 *                     administration, so it needs Cap::TICKETS_MANAGE.
 *   scope = "reset"   forget my choice and follow the install default again.
 *
 * See includes/inbox_display.php for the registry these values are validated
 * against, and for why priority defaults to the left edge rather than a dot.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/inbox_display.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$scope     = (string)($input['scope'] ?? 'me');
$analystId = (int)$_SESSION['analyst_id'];

try {
    $conn = connectToDatabase();

    if ($scope === 'reset') {
        $conn->prepare("DELETE FROM user_preferences WHERE analyst_id = ? AND preference_key = 'inbox_row_display'")
             ->execute([$analystId]);
        echo json_encode([
            'success'  => true,
            'personal' => false,
            'config'   => inboxDisplayInstallDefault($conn),
        ]);
        exit;
    }

    // ⚠️ Normalise BEFORE storing, not on the way out. These values become CSS
    // class-name fragments in the browser, so anything not in the registry must
    // never reach the database in the first place — validating only on read
    // would leave a stored value that a future, more trusting reader would use.
    $config = inboxDisplayNormalise($input['config'] ?? []);

    if ($scope === 'install') {
        requireCapabilityJson(Cap::TICKETS_MANAGE, $conn);

        $stmt = $conn->prepare(
            "INSERT INTO system_settings (setting_key, setting_value)
             VALUES ('inbox_row_display', :v)
             ON DUPLICATE KEY UPDATE setting_value = :v2"
        );
        $stmt->execute([':v' => json_encode($config), ':v2' => json_encode($config)]);

        echo json_encode([
            'success'  => true,
            'scope'    => 'install',
            'config'   => $config,
            'personal' => inboxDisplayIsPersonal($conn, $analystId),
        ]);
        exit;
    }

    // Default: this analyst's own preference.
    $stmt = $conn->prepare(
        "INSERT INTO user_preferences (analyst_id, preference_key, preference_value, updated_datetime)
         VALUES (:a, 'inbox_row_display', :v, UTC_TIMESTAMP())
         ON DUPLICATE KEY UPDATE preference_value = :v2, updated_datetime = UTC_TIMESTAMP()"
    );
    $stmt->execute([':a' => $analystId, ':v' => json_encode($config), ':v2' => json_encode($config)]);

    echo json_encode([
        'success'  => true,
        'scope'    => 'me',
        'config'   => $config,
        'personal' => true,
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
