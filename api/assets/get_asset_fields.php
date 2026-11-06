<?php
/**
 * API Endpoint: the custom asset field catalogue, its sets, and which sets each
 * asset type carries. Everything the settings screen needs, in one call.
 *
 * Read-only, so it needs module access but not the design capability — the
 * asset detail page reads this too, and a field's LABEL is not a secret.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/asset_fields.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

try {
    $conn      = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    // ⚠️ Not an error, and deliberately not an empty list either: an install
    // that has pulled the update but not run Database Verification must be told
    // WHY there is nothing here, or the screen reads as "you have no fields".
    if (!AssetFieldsService::schemaReady($conn)) {
        echo json_encode([
            'success'      => true,
            'schema_ready' => false,
            'fields'       => [],
            'sets'         => [],
            'type_sets'    => [],
        ]);
        exit;
    }

    $fields = AssetFieldsService::catalogue($conn, $analystId);
    $sets   = AssetFieldsService::sets($conn, $analystId);

    // Decode config so the UI never has to parse JSON out of a string, and pull
    // the option list for dropdowns.
    $optStmt = $conn->prepare(
        "SELECT option_value, colour FROM asset_field_options WHERE field_id = ? ORDER BY display_order, id"
    );
    $usage = $conn->prepare("SELECT COUNT(*) FROM asset_field_set_fields WHERE field_id = ?");
    $vals  = $conn->prepare("SELECT COUNT(*) FROM asset_field_values WHERE field_id = ?");
    foreach ($fields as &$f) {
        $f['config']  = $f['config'] ? (json_decode($f['config'], true) ?: []) : [];
        $f['options'] = [];
        if ($f['field_type'] === 'dropdown') {
            $optStmt->execute([(int)$f['id']]);
            $f['options'] = $optStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        foreach (['is_unique', 'is_searchable', 'show_in_list'] as $b) {
            $f[$b] = (bool)$f[$b];
        }
        // "Used by N sets" — so nothing is ever retired blind.
        $usage->execute([(int)$f['id']]);
        $f['set_count'] = (int)$usage->fetchColumn();
        // 🔑 Whether the TYPE is still editable. Once an answer exists the type
        // is locked, and the screen must say so BEFORE somebody tries.
        $vals->execute([(int)$f['id']]);
        $f['value_count'] = (int)$vals->fetchColumn();
        $f['type_locked'] = $f['value_count'] > 0;
    }
    unset($f);

    // Each set's field list, in order.
    $memberStmt = $conn->prepare(
        "SELECT sf.field_id, sf.sort_order, sf.is_required, sf.default_value,
                f.field_key, f.label, f.field_type
           FROM asset_field_set_fields sf
           JOIN asset_fields f ON f.id = sf.field_id
          WHERE sf.set_id = ? AND f.is_deleted = 0
       ORDER BY sf.sort_order, f.label"
    );
    $typeCount = $conn->prepare("SELECT COUNT(*) FROM asset_type_field_sets WHERE set_id = ?");
    $assetCount = $conn->prepare("SELECT COUNT(*) FROM asset_field_set_assets WHERE set_id = ?");
    foreach ($sets as &$s) {
        $memberStmt->execute([(int)$s['id']]);
        $s['fields'] = array_map(static function (array $m): array {
            $m['field_id']    = (int)$m['field_id'];
            $m['is_required'] = (bool)$m['is_required'];
            return $m;
        }, $memberStmt->fetchAll(PDO::FETCH_ASSOC));

        $typeCount->execute([(int)$s['id']]);
        $s['type_count'] = (int)$typeCount->fetchColumn();
        // Sets attached to individual assets — the answer to "why does this TV
        // have a field its type doesn't?" before anybody has to ask.
        $assetCount->execute([(int)$s['id']]);
        $s['asset_count'] = (int)$assetCount->fetchColumn();
    }
    unset($s);

    // asset_type_id => [set ids], for the "which types carry this set" screen.
    $typeSets = [];
    foreach ($conn->query("SELECT asset_type_id, set_id FROM asset_type_field_sets ORDER BY sort_order")
                  ->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $typeSets[(int)$r['asset_type_id']][] = (int)$r['set_id'];
    }

    echo json_encode([
        'success'      => true,
        'schema_ready' => true,
        'fields'       => $fields,
        'sets'         => $sets,
        'type_sets'    => (object)$typeSets,
        'types'        => AssetFieldsService::TYPES,
        'ref_kinds'    => AssetFieldsService::REF_KINDS,
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
