<?php
/**
 * API: run an import — preview or live.
 *
 * 🔑 Preview and live are the SAME call with one word different. Anything a
 * preview cannot tell you is something a live run would surprise you with, so
 * they must not be two code paths that drift.
 *
 * The uploaded file is re-read from its stored name rather than re-uploaded, so
 * committing what you previewed cannot accidentally commit a different file.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
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
    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $stored = basename((string)($data['stored_file'] ?? ''));
    if ($stored === '' || !preg_match('/^[A-Za-z0-9_.-]+$/', $stored)) {
        // basename() plus a whitelist: the stored name is ours, so anything
        // that does not look like one is not a file we wrote.
        throw new Exception('That upload could not be found. Upload the file again.');
    }
    $path = dirname(__DIR__, 2) . '/uploads/asset-imports/' . $stored;
    if (!is_file($path)) {
        throw new Exception('That upload has expired. Upload the file again.');
    }

    $mapping = is_array($data['mapping'] ?? null) ? $data['mapping'] : [];
    if (!$mapping) {
        throw new Exception('Nothing is mapped, so there is nothing to import.');
    }

    $mode = (($data['mode'] ?? 'preview') === 'live') ? 'live' : 'preview';

    $parsed = AssetImportService::readCsv($path, ['delimiter' => $data['delimiter'] ?? ',']);

    $opts = [
        'match_keys'            => (array)($data['match_keys'] ?? ['hostname']),
        'write_mode'            => $data['write_mode'] ?? 'fill',
        'on_unknown_option'     => $data['on_unknown_option'] ?? 'reject',
        'on_missing'            => $data['on_missing'] ?? 'ignore',
        'default_asset_type_id' => $data['default_asset_type_id'] ?? null,
        'default_status_id'     => $data['default_status_id'] ?? null,
        'apply_field_set_id'    => $data['apply_field_set_id'] ?? null,
        'profile_id'            => $data['profile_id'] ?? null,
        'source_name'           => $data['source_name'] ?? $stored,
        'stored_file'           => $stored,
    ];

    $run = AssetImportService::run(
        $conn, ActorContext::fromSession($conn), $parsed['rows'], $mapping, $opts, $mode
    );

    echo json_encode([
        'success' => true,
        'run'     => $run,
        'entries' => AssetImportService::runEntries($conn, (int)$run['id'], null, 500),
    ]);

} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
