<?php
/**
 * API: what people may change about themselves in the self-service portal.
 *
 * GET                                  the current settings, plus the address
 *                                      books the second one could apply to
 * POST { fields: [...], address_book } save them
 *
 * 🔴 Both settings decide what CUSTOMERS may write, and the second one lets
 * them write into the organisation's address book, so this is gated on System
 * administrators like every other System endpoint.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/functions.php';
require_once '../../includes/users.php';

header('Content-Type: application/json');

/** What the page needs to draw itself - always read back from the database. */
function portalProfileState(PDO $conn): array
{
    $enabled = portalProfileEditableFields($conn);
    $fields = [];
    foreach (USER_SELF_EDITABLE_FIELDS as $f) {
        $fields[] = ['key' => $f, 'enabled' => in_array($f, $enabled, true)];
    }

    // The address books the second setting could reach, so the page can say
    // whether it would do anything at all. Only those with write-back on are
    // ever written to; the rest are listed so an admin is not left wondering.
    $books = [];
    try {
        foreach ($conn->query(
            "SELECT id, display_name AS name, carddav_write_back FROM auth_providers
              WHERE protocol = 'carddav' ORDER BY display_name"
        )->fetchAll(PDO::FETCH_ASSOC) as $b) {
            $books[] = [
                'id'         => (int)$b['id'],
                'name'       => $b['name'],
                'write_back' => (int)$b['carddav_write_back'] === 1,
            ];
        }
    } catch (Throwable $e) {
        // An install that has never run Database Verification has no write-back
        // column; it simply has no address books to offer.
    }

    return [
        'success'       => true,
        'fields'        => $fields,
        'address_book'  => portalProfileAddressBookWrites($conn),
        'address_books' => $books,
    ];
}

try {
    $conn = connectToDatabase();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(portalProfileState($conn));
        exit;
    }

    $in = json_decode(file_get_contents('php://input'), true);
    if (!is_array($in) || !isset($in['fields']) || !is_array($in['fields'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
        exit;
    }

    // Only names the portal could ever offer. Anything else in the request -
    // manager_id, employee_id - is dropped, never stored: the setting narrows
    // USER_SELF_EDITABLE_FIELDS and cannot widen it.
    $chosen = array_values(array_intersect(USER_SELF_EDITABLE_FIELDS, array_map('strval', $in['fields'])));

    $save = $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value, updated_datetime)
              VALUES (?, ?, UTC_TIMESTAMP())
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value),
                                 updated_datetime = UTC_TIMESTAMP()"
    );
    // Stored even when empty: "" means "none", which is different from never
    // having been saved (all of them). See portalProfileEditableFields().
    $save->execute([PORTAL_PROFILE_FIELDS_SETTING, implode(',', $chosen)]);
    $save->execute([PORTAL_PROFILE_ADDRESS_BOOK_SETTING, !empty($in['address_book']) ? '1' : '0']);

    echo json_encode(portalProfileState($conn));
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
