<?php
/**
 * API Endpoint: Save (create or update) an analyst
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

$id = $data['id'] ?? null;
$username = trim($data['username'] ?? '');
$fullName = trim($data['full_name'] ?? '');
$email = trim($data['email'] ?? '') ?: null;
$password = $data['password'] ?? null;
$isActive = $data['is_active'] ?? true;
// Which sign-in method: NULL/empty = local password, otherwise an auth_providers.id (SSO).
$authProviderId = !empty($data['auth_provider_id']) ? (int)$data['auth_provider_id'] : null;

// Validation
if (empty($username)) {
    echo json_encode(['success' => false, 'error' => 'Username is required']);
    exit;
}

if (empty($fullName)) {
    echo json_encode(['success' => false, 'error' => 'Full name is required']);
    exit;
}

// Password required for new analysts
if (empty($id) && empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Password is required for new analysts']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Check for duplicate username
    $checkSql = "SELECT id FROM analysts WHERE username = ? AND id != ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([$username, $id ?? 0]);
    if ($checkStmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Username already exists']);
        exit;
    }

    if ($id) {
        // Update existing analyst
        if (!empty($password)) {
            // Update with new password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE analysts SET
                    username = ?,
                    full_name = ?,
                    email = ?,
                    password_hash = ?,
                    is_active = ?,
                    auth_provider_id = ?,
                    last_modified_datetime = UTC_TIMESTAMP()
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $fullName, $email, $passwordHash, $isActive ? 1 : 0, $authProviderId, $id]);
        } else {
            // Update without changing password
            $sql = "UPDATE analysts SET
                    username = ?,
                    full_name = ?,
                    email = ?,
                    is_active = ?,
                    auth_provider_id = ?,
                    last_modified_datetime = UTC_TIMESTAMP()
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $fullName, $email, $isActive ? 1 : 0, $authProviderId, $id]);
        }
        $analystId = (int)$id;
        $message = 'Analyst updated successfully';
    } else {
        // Create new analyst
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO analysts (username, password_hash, full_name, email, is_active, auth_provider_id, created_datetime, last_modified_datetime)
                VALUES (?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $passwordHash, $fullName, $email, $isActive ? 1 : 0, $authProviderId]);
        $analystId = (int)$conn->lastInsertId();
        $message = 'Analyst created successfully';
    }

    // Multi-tenancy: company access. Only touched when the form actually sends it
    // (it's hidden on a single-company install), so we never clobber the all-access
    // default on installs that don't show the control.
    if (array_key_exists('can_access_all_tenants', $data) && $analystId > 0) {
        $allAccess = !empty($data['can_access_all_tenants']) ? 1 : 0;
        $tenantIds = is_array($data['tenant_ids'] ?? null) ? array_map('intval', $data['tenant_ids']) : [];
        try {
            $conn->prepare("UPDATE analysts SET can_access_all_tenants = ? WHERE id = ?")->execute([$allAccess, $analystId]);
            // Rebuild the per-company grants from scratch (ignored anyway while all-access).
            $conn->prepare("DELETE FROM analyst_tenant_access WHERE analyst_id = ?")->execute([$analystId]);
            if (!$allAccess && $tenantIds) {
                $ins = $conn->prepare("INSERT IGNORE INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)");
                foreach (array_unique($tenantIds) as $tid) {
                    if ($tid > 0) $ins->execute([$analystId, $tid]);
                }
            }
        } catch (Exception $e) {
            // Access tables not migrated yet → leave the analyst saved without touching access.
        }
    }

    echo json_encode(['success' => true, 'message' => $message]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>
