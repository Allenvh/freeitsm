<?php
/**
 * API Endpoint: Save forms module settings
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/forms.php';   // CLOSE_EFFECTS

header('Content-Type: application/json');

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('forms');
/* Per key below, not once here: the two settings this endpoint now writes
   belong to different capabilities. See docs/design/rbac.md. */

$input = json_decode(file_get_contents('php://input'), true);
$settings = $input['settings'] ?? null;

if (!$settings) {
    echo json_encode(['success' => false, 'error' => 'Settings required']);
    exit;
}

try {
    $conn = connectToDatabase();

    // key => the capability that may write it.
    $allowed = [
        'logo_alignment'          => capKey(Cap::FORMS_LAYOUT),
        'collection_close_effect' => capKey(Cap::FORMS_COLLECTIONS),
        'design_restricted'       => capKey(Cap::FORMS_ACCESS),   // who may design forms (#180)
    ];
    $validAlignments = ['left', 'center', 'right'];

    foreach ($allowed as $key => $cap) {
        if (!isset($settings[$key])) continue;
        // A caller without the capability for THIS key is refused this key,
        // rather than the whole request - the tabs are separately granted.
        requireCapabilityJson(Cap::from($cap));

        $value = $settings[$key];
        if ($key === 'logo_alignment' && !in_array($value, $validAlignments)) {
            $value = 'center';
        }
        /* An unrecognised value falls back to the default rather than being
           stored: a typo here would otherwise mean closing a collection
           silently does nothing, which is the failure hardest to notice. */
        if ($key === 'collection_close_effect' && !in_array($value, FormsService::CLOSE_EFFECTS, true)) {
            $value = FormsService::CLOSE_EFFECT_DEFAULT;
        }
        if ($key === 'design_restricted') {
            $value = $value === '1' || $value === 1 || $value === true ? '1' : '0';
        }

        $dbKey = 'forms_' . $key;

        $stmt = $conn->prepare("UPDATE system_settings SET setting_value = ?, updated_datetime = UTC_TIMESTAMP() WHERE setting_key = ?");
        $stmt->execute([$value, $dbKey]);

        if ($stmt->rowCount() === 0) {
            $stmt = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value, updated_datetime) VALUES (?, ?, UTC_TIMESTAMP())");
            $stmt->execute([$dbKey, $value]);
        }
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
