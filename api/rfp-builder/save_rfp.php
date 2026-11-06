<?php
/**
 * API: create or update an RFP - name, status, style guide, contract and chosen
 * supplier.
 *
 * On a multi-company install the RFP's company must be one the analyst can reach, and a
 * linked contract must be visible to them and in the same company - refused, never
 * quietly replaced.
 *
 * @input  id                  the RFP to update - omit to create one
 * @input  name                required
 * @input  status              draft, collecting, consolidating, generating, scoring, closed or abandoned
 * @input  style_guide         optional writing guidance for the AI-generated sections
 * @input  contract_id         optional contract the RFP leads to
 * @input  chosen_supplier_id  optional winning supplier
 * @input  tenant_id           optional company (multi-company installs)
 * @output {success, id}
 * @wiki Contracts
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// The RFP Builder is part of the Contracts module — its PAGES have always checked
// this, its endpoints never did. Any logged-in analyst could read, edit or delete
// any RFP by calling the API directly. (Found by debug tool D005.)
requireModuleAccessJson('contracts');
// The RFP's company (3.5.0): one in a company this analyst cannot reach is "not found".
require_once '../../includes/rfp_scope.php';
rfpGate('rfp', 'id');
$VALID_STATUSES = ['draft', 'collecting', 'consolidating', 'generating', 'scoring', 'closed', 'abandoned'];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? null;
    $name = trim($data['name'] ?? '');
    $status = $data['status'] ?? 'draft';
    $style_guide = trim($data['style_guide'] ?? '') ?: null;
    $contract_id = $data['contract_id'] ?? null;
    $chosen_supplier_id = $data['chosen_supplier_id'] ?? null;

    if ($name === '') {
        throw new Exception('Name is required');
    }
    if (!in_array($status, $VALID_STATUSES, true)) {
        throw new Exception('Invalid status');
    }

    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    // The company the RFP belongs to (3.5.0). A new one: the company named, else
    // the active one. An existing one keeps its company unless the form names
    // another. Either way it must be a company this analyst can reach - refused,
    // never quietly replaced. (rfpGate() above has already refused an RFP they
    // cannot see.)
    $multi = isMultiTenant($conn) && rfpTenantReady($conn);
    $tenant = null;
    if ($multi) {
        $named = isset($data['tenant_id']) && $data['tenant_id'] !== '' && $data['tenant_id'] !== null ? (int)$data['tenant_id'] : null;
        $tenant = $named ?? ($id ? rfpTenantOf($conn, (int)$id) : getActiveTenantId($conn, $analystId));
        if ($tenant === null || !getTenantById($conn, $tenant) || !analystCanAccessTenant($conn, $analystId, $tenant)) {
            throw new Exception('You cannot put an RFP in that company.');
        }
    }

    // The contract it leads to: one the analyst can see, in the RFP's own company.
    if ($contract_id) {
        require_once '../../includes/contract_party.php';
        if (!contractCanView($conn, $analystId, (int)$contract_id)) {
            throw new Exception('Contract not found');
        }
        if ($multi && contractTenantReady($conn) && contractTenantOf($conn, (int)$contract_id) !== $tenant) {
            throw new Exception('That contract belongs to a different company from this RFP.');
        }
    }
    $store = $tenant === null || $tenant === getDefaultTenantId($conn) ? null : $tenant;

    if ($id) {
        $sql = "UPDATE rfps
                   SET name = ?, status = ?, style_guide = ?,
                       contract_id = ?, chosen_supplier_id = ?,
                       updated_datetime = CURRENT_TIMESTAMP
                 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $name, $status, $style_guide,
            $contract_id ?: null, $chosen_supplier_id ?: null,
            $id
        ]);
        if ($multi) $conn->prepare("UPDATE rfps SET tenant_id = ? WHERE id = ?")->execute([$store, $id]);
    } else {
        $sql = "INSERT INTO rfps (name, status, style_guide, contract_id, chosen_supplier_id, created_by_analyst_id)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $name, $status, $style_guide,
            $contract_id ?: null, $chosen_supplier_id ?: null,
            $_SESSION['analyst_id']
        ]);
        $id = $conn->lastInsertId();
        if ($multi) $conn->prepare("UPDATE rfps SET tenant_id = ? WHERE id = ?")->execute([$store, $id]);
    }

    echo json_encode(['success' => true, 'id' => (int)$id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
