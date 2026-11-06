<?php
/**
 * API: every submission in a collection, across its forms, plus the field
 * definitions of each form involved.
 *
 * A collection's rows do not share a set of questions, so the detail panel and
 * the PDF are handed the right form for each submission rather than assuming
 * one form for the page.
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
    $id = (int)($_GET['id'] ?? 0);

    $meta = $conn->prepare(
        "SELECT c.id, c.name, c.description, c.closed_datetime, closer.full_name AS closed_by_name
           FROM form_collections c
           LEFT JOIN analysts closer ON closer.id = c.closed_by
          WHERE c.id = ?"
    );
    $meta->execute([$id]);
    $collection = $meta->fetch(PDO::FETCH_ASSOC);
    if (!$collection) {
        echo json_encode(['success' => false, 'error' => 'Collection not found']);
        exit;
    }

    $out = FormsService::collectionSubmissions($conn, $id, ActorContext::fromSession($conn)->companyScope);
    echo json_encode([
        'success'     => true,
        'collection'  => $collection,
        'submissions' => $out['submissions'],
        'forms'       => $out['forms'],
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
