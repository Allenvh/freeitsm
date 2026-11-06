<?php
/**
 * API: create or update one of this analyst's own signatures.
 * POST { id?, name, body, is_default }
 *
 * ⚠️ EVERY statement is scoped by analyst_id from the SESSION, and the incoming id is
 * only ever used together with it. Without that pairing, posting somebody else's
 * signature id would let an analyst rewrite what another person signs their emails
 * with — which is both a tampering bug and, since signatures carry phone numbers, a
 * way to read them back afterwards.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/signatures.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $data      = json_decode(file_get_contents('php://input'), true);
    $analystId = (int)$_SESSION['analyst_id'];
    $id        = isset($data['id']) && $data['id'] ? (int)$data['id'] : null;
    $name      = trim((string)($data['name'] ?? ''));
    $body      = trim((string)($data['body'] ?? ''));
    $isDefault = !empty($data['is_default']);

    if ($name === '') {
        throw new Exception('Give the signature a name, so you can tell it from the others.');
    }
    // Judged on the TEXT, not the markup: a body cleared in the editor comes back as
    // <p>&nbsp;</p>, which is a truthy string and would save as an empty signature.
    $plain = trim(html_entity_decode(strip_tags($body), ENT_QUOTES, 'UTF-8'));
    $plain = trim(str_replace("\xC2\xA0", ' ', $plain));
    if ($plain === '') {
        throw new Exception('The signature is empty.');
    }

    $conn = connectToDatabase();

    if ($id) {
        $stmt = $conn->prepare("UPDATE analyst_signatures
                                   SET name = ?, body = ?, updated_datetime = UTC_TIMESTAMP()
                                 WHERE id = ? AND analyst_id = ?");
        $stmt->execute([$name, $body, $id, $analystId]);
        if ($stmt->rowCount() === 0) {
            // Either it is not theirs, or nothing changed. Confirm ownership rather
            // than assuming, so a genuine no-op edit does not report a failure.
            $own = $conn->prepare("SELECT COUNT(*) FROM analyst_signatures WHERE id = ? AND analyst_id = ?");
            $own->execute([$id, $analystId]);
            if (!(int)$own->fetchColumn()) {
                throw new Exception('That signature does not exist.');
            }
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO analyst_signatures (analyst_id, name, body, is_default, display_order)
                                VALUES (?, ?, ?, 0, 0)");
        $stmt->execute([$analystId, $name, $body]);
        $id = (int)$conn->lastInsertId();

        // The FIRST signature is the default whether or not the box was ticked.
        // Otherwise an analyst creates one signature, none is flagged, and nothing is
        // ever inserted into a reply — which reads as the feature simply not working.
        $count = $conn->prepare("SELECT COUNT(*) FROM analyst_signatures WHERE analyst_id = ?");
        $count->execute([$analystId]);
        if ((int)$count->fetchColumn() === 1) {
            $isDefault = true;
        }
    }

    if ($isDefault) {
        setDefaultSignature($conn, $analystId, (int)$id);
    }

    echo json_encode(['success' => true, 'id' => (int)$id]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
