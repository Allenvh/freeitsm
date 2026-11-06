<?php
/**
 * API: take a CSV, store it safely, and report its columns with a suggested
 * mapping. Step one of the import wizard — nothing is imported here.
 *
 * 🔒 The file goes through uploadStoreFile(), the one place the upload rules
 * live: extension + mime whitelist, a filename WE generate, and an .htaccess
 * that disables execution in the directory. Never the caller's filename.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/uploads.php';
require_once '../../includes/services/asset_import.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_IMPORT);

try {
    $conn = connectToDatabase();
    if (!AssetImportService::schemaReady($conn)) {
        throw new Exception('Asset import needs Database Verification to run first.');
    }
    if (!isset($_FILES['file'])) {
        throw new Exception('No file was uploaded.');
    }

    // CSV only. The whitelist is narrowed from the everyday set deliberately:
    // this endpoint has no business accepting an image or a document.
    $stored = uploadStoreFile(
        $_FILES['file'],
        dirname(__DIR__, 2) . '/uploads/asset-imports',
        ['csv' => ['text/plain', 'text/csv', 'application/csv']],
        20 * 1024 * 1024
    );

    $parsed = AssetImportService::readCsv($stored['path'], [
        'delimiter' => $_POST['delimiter'] ?? ',',
    ]);

    echo json_encode([
        'success'     => true,
        'stored_file' => $stored['stored_name'],
        'source_name' => $stored['original_name'],
        'headers'     => $parsed['headers'],
        'row_count'   => count($parsed['rows']),
        // ⚠️ Surfaced, never silent. A run that quietly imported the first 5000
        // of 8000 rows reads as a complete success.
        'truncated'   => $parsed['truncated'],
        'max_rows'    => AssetImportService::MAX_ROWS,
        'sample'      => array_slice($parsed['rows'], 0, 5),
        'suggested'   => AssetImportService::suggestMapping(
            $conn, $parsed['headers'], (int)$_SESSION['analyst_id']
        ),
        'match_keys'  => AssetImportService::availableMatchKeys($conn),
        'core'        => AssetImportService::CORE_TARGETS,
        'fields'      => AssetFieldsService::catalogue($conn, (int)$_SESSION['analyst_id']),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
