<?php
/**
 * API: Submit a filled-in form
 * Expects JSON body: { form_id, data: { field_id: value, ... } }
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

$formId = (int)($input['form_id'] ?? 0);
$data = $input['data'] ?? [];

if ($formId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Missing form ID']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Validate form exists and is active
    $stmt = $conn->prepare("SELECT id FROM forms WHERE id = ? AND is_active = 1");
    $stmt->execute([$formId]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Form not found or inactive']);
        exit;
    }

    // Get fields with type info so per-type validation knows what shape
    // to expect (single string vs JSON-encoded array vs boolean string).
    $stmt = $conn->prepare("SELECT id, label, field_type, is_required FROM form_fields WHERE form_id = ?");
    $stmt->execute([$formId]);
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Per-field validation. Required-empty checks vary by type:
    //   - text/textarea/email/number: empty string fails
    //   - checkbox (single yes/no): "0" or "" fails when required
    //   - dropdown/radio: empty string fails
    //   - checkboxes (multi): empty JSON array "[]" fails when required
    // Plus light format checks for email + number.
    foreach ($fields as $field) {
        $fid = (int)$field['id'];
        $val = array_key_exists($fid, $data) ? $data[$fid] : '';
        $type = $field['field_type'];

        if ($field['is_required']) {
            $isEmpty = false;
            if ($val === '' || $val === null) {
                $isEmpty = true;
            } elseif ($type === 'checkbox' && (string)$val === '0') {
                $isEmpty = true;
            } elseif ($type === 'checkboxes') {
                $decoded = json_decode((string)$val, true);
                $isEmpty = !is_array($decoded) || count($decoded) === 0;
            }
            if ($isEmpty) {
                echo json_encode(['success' => false, 'error' => '"' . $field['label'] . '" is required']);
                exit;
            }
        }

        // Format checks — only when a value is supplied (non-required
        // fields are allowed to be empty).
        if ($val !== '' && $val !== null) {
            if ($type === 'email' && !filter_var((string)$val, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'error' => '"' . $field['label'] . '" must be a valid email address']);
                exit;
            }
            if ($type === 'number' && !is_numeric((string)$val)) {
                echo json_encode(['success' => false, 'error' => '"' . $field['label'] . '" must be a number']);
                exit;
            }
        }
    }

    $conn->beginTransaction();

    // Create submission
    $stmt = $conn->prepare("INSERT INTO form_submissions (form_id, submitted_by) VALUES (?, ?)");
    $stmt->execute([$formId, $_SESSION['analyst_id']]);
    $submissionId = (int)$conn->lastInsertId();

    // Save field values. Multi-value fields (checkboxes) already arrive
    // as a JSON string from the frontend; we store the raw string —
    // submissions.php decodes for display.
    foreach ($data as $fieldId => $value) {
        $fieldId = (int)$fieldId;
        if ($fieldId <= 0) continue;

        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }
        if (is_array($value)) {
            // Defensive — frontend should pre-encode, but if a caller
            // sends an array directly we cope by encoding here.
            $value = json_encode(array_values($value));
        }

        $stmt = $conn->prepare("INSERT INTO form_submission_data (submission_id, field_id, field_value) VALUES (?, ?, ?)");
        $stmt->execute([$submissionId, $fieldId, (string)$value]);
    }

    $conn->commit();

    echo json_encode(['success' => true, 'submission_id' => $submissionId, 'message' => 'Form submitted']);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
