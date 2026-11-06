<?php
/**
 * Which company an RFP belongs to, and who may see it (3.5.0).
 *
 * rfps.tenant_id is SCOPED DATA, like contracts and assets: NULL is the Default
 * company, never "shared". An analyst sees an RFP only when they can reach its
 * company. Everything below an RFP - documents, extracted and consolidated
 * requirements, conflicts, sections and their history, framing, invited
 * suppliers, scores, the processing log - is reached THROUGH its RFP, so it
 * follows the RFP's company with no column of its own.
 *
 * ⚠️ EVERY RFP Builder endpoint calls rfpGate() straight after its module check,
 * naming the kind of id it was given. One gate, one place: an endpoint that
 * forgets it reads another company's tender documents, and nothing would look
 * wrong. The secondary ids an endpoint takes (a category, a requirement, a
 * section version) are already checked against the RFP by the endpoint itself.
 *
 * The RFP's linked contract must be in the RFP's company (contracts belong to
 * companies too, see includes/contract_party.php). Suppliers and RFP departments
 * stay shared by every company, as they are for contracts and assets.
 */

require_once __DIR__ . '/tenancy.php';

/** True once Database Verification has added rfps.tenant_id. */
function rfpTenantReady(PDO $conn): bool
{
    static $ok = null;
    if ($ok !== null) return $ok;
    try { $conn->query("SELECT tenant_id FROM rfps LIMIT 0"); return $ok = true; }
    catch (Throwable) { return $ok = false; }
}

/**
 * " AND ..." keeping RFPs (alias $r) in companies this analyst can reach.
 *
 * @param string $r  the alias the calling query gives the rfps table
 */
function rfpVisibilitySql(PDO $conn, int $analystId, string $r = 'r'): array
{
    if (!rfpTenantReady($conn) || !isMultiTenant($conn)) return ['', []];
    return allAccessibleTenantsFilter($conn, $analystId, "$r.tenant_id");
}

/**
 * The RFP LIST: the active company, or every company the analyst can see under "All companies".
 *
 * @param string $r  the alias the calling query gives the rfps table
 */
function rfpListSql(PDO $conn, int $analystId, string $r = 'r'): array
{
    if (!rfpTenantReady($conn) || !isMultiTenant($conn)) return ['', []];
    return activeTenantReadFilter($conn, $analystId, $r);
}

/**
 * The company an RFP belongs to (NULL read as Default), or null if there is no such RFP.
 *
 * @param int $rfpId  the rfps id
 */
function rfpTenantOf(PDO $conn, int $rfpId): ?int
{
    $s = $conn->prepare(rfpTenantReady($conn) ? "SELECT id, tenant_id FROM rfps WHERE id = ?" : "SELECT id, NULL AS tenant_id FROM rfps WHERE id = ?");
    $s->execute([$rfpId]);
    $row = $s->fetch(PDO::FETCH_ASSOC);
    if (!$row) return null;
    return $row['tenant_id'] === null ? getDefaultTenantId($conn) : (int)$row['tenant_id'];
}

/**
 * May this analyst see RFP $id? False for one that does not exist - the same answer, deliberately.
 *
 * @param int $rfpId  the rfps id; 0 or less gives false
 */
function rfpCanView(PDO $conn, int $analystId, int $rfpId): bool
{
    if ($rfpId <= 0) return false;
    [$sql, $args] = rfpVisibilitySql($conn, $analystId, 'r');
    $s = $conn->prepare("SELECT 1 FROM rfps r WHERE r.id = ?$sql");
    $s->execute(array_merge([$rfpId], $args));
    return (bool)$s->fetchColumn();
}

/**
 * The RFP a child row belongs to. $kind names the table the id is for; every
 * one carries rfp_id except section history (through its section).
 *
 * @param string $kind  which table the id is for: rfp, document, extracted, consolidated, conflict, section, framing or invited
 * @param int $id  the row's id in that table
 */
function rfpIdOf(PDO $conn, string $kind, int $id): ?int
{
    $sql = [
        'rfp'          => "SELECT id FROM rfps WHERE id = ?",
        'document'     => "SELECT rfp_id FROM rfp_documents WHERE id = ?",
        'extracted'    => "SELECT rfp_id FROM rfp_extracted_requirements WHERE id = ?",
        'consolidated' => "SELECT rfp_id FROM rfp_consolidated_requirements WHERE id = ?",
        'conflict'     => "SELECT rfp_id FROM rfp_conflicts WHERE id = ?",
        'section'      => "SELECT rfp_id FROM rfp_output_sections WHERE id = ?",
        'framing'      => "SELECT rfp_id FROM rfp_document_sections WHERE id = ?",
        'invited'      => "SELECT rfp_id FROM rfp_invited_suppliers WHERE id = ?",
    ][$kind] ?? null;
    if ($sql === null || $id <= 0) return null;
    $s = $conn->prepare($sql);
    $s->execute([$id]);
    $v = $s->fetchColumn();
    return $v === false || $v === null ? null : (int)$v;
}

/**
 * The gate every RFP endpoint passes. Reads the id named $key from the query
 * string, the form, or the JSON body; resolves it to its RFP; and answers "not
 * found" - never forbidden, so ids cannot be probed - when the analyst cannot
 * see that RFP. A missing id is left to the endpoint's own validation. With
 * $key naming a LIST (merge's `ids`), every id must resolve to RFPs the analyst
 * can see.
 *
 * @param string $kind  which table the request's id is for, as rfpIdOf() takes it ('rfp' for an RFP id itself)
 * @param string $key  the request field holding the id (or a list of ids), read from the query string, the form or the JSON body
 */
function rfpGate(string $kind, string $key): void
{
    if (!function_exists('connectToDatabase')) require_once __DIR__ . '/functions.php';
    $conn = connectToDatabase();
    if (!isMultiTenant($conn) || !rfpTenantReady($conn)) return;

    static $body = null;
    if ($body === null) {
        $raw = file_get_contents('php://input');
        $body = is_string($raw) && $raw !== '' ? (json_decode($raw, true) ?: []) : [];
    }
    $value = $_GET[$key] ?? $_POST[$key] ?? $body[$key] ?? null;
    if ($value === null || $value === '' || $value === []) return;

    $analystId = (int)($_SESSION['analyst_id'] ?? 0);
    foreach ((array)$value as $one) {
        $rfpId = $kind === 'rfp' ? (int)$one : rfpIdOf($conn, $kind, (int)$one);
        if ($rfpId === null || !rfpCanView($conn, $analystId, $rfpId)) {
            http_response_code(200);
            echo json_encode(['success' => false, 'error' => 'RFP not found']);
            exit;
        }
    }
}
