<?php
/**
 * API Endpoint: Get API Keys
 * Returns all API keys with owner information
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('software');
// The API keys tab's capability, as generate_apikey.php and delete_apikey.php require.
// Module access alone let any Software analyst read every key - reported privately,
// October 2026.
requireCapabilityJson(Cap::SOFTWARE_API_KEYS);

try {
    $conn = connectToDatabase();

    $sql = "SELECT k.id, k.apikey, k.label,
                   DATE_FORMAT(k.datestamp, '%Y-%m-%d %H:%i:%s') AS created_at,
                   k.active, k.analyst_id,
                   a.full_name AS analyst_name
            FROM apikeys k
            LEFT JOIN analysts a ON k.analyst_id = a.id
            ORDER BY k.datestamp DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $keys = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'keys' => $keys
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
