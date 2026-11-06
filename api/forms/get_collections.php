<?php
/**
 * API: List form collections, with their paired forms.
 *
 * Also reports whether the schema is present at all, so the settings tab can
 * say "run DB Verification" rather than showing an empty list that looks like
 * a working feature nobody has used yet.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/forms.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('forms');

try {
    $conn = connectToDatabase();
    $available = FormsService::collectionsAvailable($conn);

    $collections = FormsService::listCollections($conn);
    foreach ($collections as &$c) {
        // Only when there is something to show: a collection with no forms is
        // the normal state right after you create one.
        $c['forms'] = ((int)$c['form_count'] > 0)
            ? FormsService::collectionForms($conn, (int)$c['id'])
            : [];
    }
    unset($c);

    echo json_encode([
        'success'      => true,
        'available'    => $available,
        'close_effect' => FormsService::closeEffect($conn),
        'collections'  => $collections,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
