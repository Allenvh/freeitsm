<?php
/**
 * Who OWNS a contract, who it is WITH, and who may see it.
 *
 * OWNER (3.5.0) - contracts.tenant_id, the company the contract belongs to. Scoped
 * data, exactly like assets and tickets: NULL is the Default company, never
 * "shared". An analyst sees a contract when they can reach its company, and the
 * contracts list follows the company switcher.
 *
 * PARTY (#153) - who the contract is WITH: a supplier (we buy, supplier_id) or a
 * customer (we sell: customer_tenant_id and/or customer_user_id). Set
 * independently of the owner - a contract owned by your Default company can be
 * with customer X. It no longer decides visibility.
 *
 * Until 3.5.0 a customer contract was hidden by its CUSTOMER's company, and a
 * setting (contracts_customer_visibility) could open them to everyone. The first
 * Database Verification of 3.5.0 filed each existing customer contract under its
 * customer's company, so nobody's view changed on upgrade; the setting is gone.
 *
 * ⚠️ EVERY place that reads `contracts` must apply contractVisibilitySql() (any
 * read - search, previews, links), contractListSql() (the contracts list and its
 * dashboard, which follow the switcher) or contractCanView() (one record). A read
 * that forgets it shows one company's contract and its value to another's analyst.
 */

require_once __DIR__ . '/tenancy.php';

const CONTRACT_PARTY_SUPPLIER = 'supplier';
const CONTRACT_PARTY_CUSTOMER = 'customer';

/** True once Database Verification has added contracts.tenant_id. */
function contractTenantReady(PDO $conn): bool
{
    static $ok = null;
    if ($ok !== null) return $ok;
    try { $conn->query("SELECT tenant_id FROM contracts LIMIT 0"); return $ok = true; }
    catch (Throwable) { return $ok = false; }
}

/**
 * The company a contract belongs to (NULL resolved to Default), or null if there is no such contract.
 *
 * @param int $contractId  contracts.id to look up
 */
function contractTenantOf(PDO $conn, int $contractId): ?int
{
    if (!contractTenantReady($conn)) return getDefaultTenantId($conn);
    $s = $conn->prepare("SELECT id, tenant_id FROM contracts WHERE id = ?");
    $s->execute([$contractId]);
    $r = $s->fetch(PDO::FETCH_ASSOC);
    if (!$r) return null;
    return $r['tenant_id'] === null ? getDefaultTenantId($conn) : (int)$r['tenant_id'];
}

/** How a company is stored on a contract: the Default company is NULL, like every scoped table. */
function contractStoreTenant(PDO $conn, ?int $tenantId): ?int
{
    if ($tenantId === null || $tenantId <= 0) return null;
    return $tenantId === getDefaultTenantId($conn) ? null : $tenantId;
}

/** True once Database Verification has added the party columns. */
function contractPartyReady(PDO $conn): bool
{
    static $ok = null;
    if ($ok !== null) return $ok;
    try { $conn->query("SELECT party_type FROM contracts LIMIT 0"); return $ok = true; }
    catch (Throwable) { return $ok = false; }
}

/**
 * The SQL expression for a contract's customer company, for alias $c.
 * Uses the customer person's company when no company was chosen.
 *
 * @param string $c  the alias the calling query gives the contracts table (trusted - put straight into SQL)
 */
function contractCustomerTenantExpr(string $c = 'c'): string
{
    return "COALESCE($c.customer_tenant_id, (SELECT cu_t.tenant_id FROM users cu_t WHERE cu_t.id = $c.customer_user_id))";
}

/**
 * " AND (...)" keeping only contracts in a company this analyst can reach, for a
 * query over `contracts` aliased $c - every company they may see, whatever the
 * switcher says. For search, previews, links and anything else that is not the
 * contracts list itself. ['', []] on a single-company install.
 *
 * @param string $c  the alias the calling query gives the contracts table (trusted - put straight into SQL)
 * @return array{0:string,1:array}
 */
function contractVisibilitySql(PDO $conn, int $analystId, string $c = 'c'): array
{
    if (!contractTenantReady($conn) || !isMultiTenant($conn)) {
        return ['', []];
    }
    return allAccessibleTenantsFilter($conn, $analystId, "$c.tenant_id");
}

/**
 * The same rule for an explicit company list, in the API's null-means-all
 * convention (ActorContext::$companyScope).
 *
 * @param ?array $scope  tenant ids the caller may reach; null means every company, empty means none
 * @param string $c  the alias the calling query gives the contracts table (trusted - put straight into SQL)
 * @return array{0:string,1:array}
 */
function contractVisibilitySqlForScope(PDO $conn, ?array $scope, string $c = 'c'): array
{
    if ($scope === null || !contractTenantReady($conn) || !isMultiTenant($conn)) {
        return ['', []];
    }
    $ids = array_values(array_unique(array_map('intval', $scope)));
    if (!$ids) {
        return [" AND 1 = 0", []];   // fail closed: no companies, no contracts
    }
    $in = implode(',', array_fill(0, count($ids), '?'));
    $nullOk = in_array(getDefaultTenantId($conn), $ids, true) ? " OR $c.tenant_id IS NULL" : '';
    return [" AND ($c.tenant_id IN ($in)$nullOk)", $ids];
}

/**
 * The contracts LIST and its dashboard: the active company, or every company the
 * analyst can see when the switcher says "All companies" - the same as Assets and
 * Domains. ['', []] on a single-company install.
 *
 * @param string $c  the alias the calling query gives the contracts table (trusted - put straight into SQL)
 * @return array{0:string,1:array}
 */
function contractListSql(PDO $conn, int $analystId, string $c = 'c'): array
{
    if (!contractTenantReady($conn) || !isMultiTenant($conn)) {
        return ['', []];
    }
    return activeTenantReadFilter($conn, $analystId, $c);
}

/**
 * May this analyst see contract $id? (Existence is the caller's business.)
 *
 * @param int $contractId  contracts.id to check; a missing contract also returns false
 */
function contractCanView(PDO $conn, int $analystId, int $contractId): bool
{
    [$sql, $params] = contractVisibilitySql($conn, $analystId, 'c');
    $s = $conn->prepare("SELECT 1 FROM contracts c WHERE c.id = ?$sql");
    $s->execute(array_merge([$contractId], $params));
    return (bool)$s->fetchColumn();
}

/**
 * The owning company, for alias $c: tenant_id (NULL read as Default) and
 * company_name. A NULL column before Database Verification, so callers need no
 * second branch.
 *
 * @param string $c  the alias the calling query gives the contracts table (trusted - put straight into SQL)
 * @return array{0:string,1:string} [select columns (with leading comma), joins]
 */
function contractOwnerSql(PDO $conn, string $c = 'c'): array
{
    if (!contractTenantReady($conn)) {
        return [", NULL AS tenant_id, NULL AS company_name", ''];
    }
    $def = getDefaultTenantId($conn);
    return [", COALESCE($c.tenant_id, $def) AS tenant_id, own_t.name AS company_name",
            "LEFT JOIN tenants own_t ON own_t.id = COALESCE($c.tenant_id, $def)"];
}

/**
 * Joins and columns that name a contract's customer, for alias $c:
 *   customer_company_name, customer_person_name, customer_person_email
 * Empty strings before Database Verification, so callers need no second branch.
 *
 * @param string $c  the alias the calling query gives the contracts table (trusted - put straight into SQL)
 * @return array{0:string,1:string} [select columns (with leading comma), joins]
 */
function contractPartySql(PDO $conn, string $c = 'c'): array
{
    if (!contractPartyReady($conn)) {
        return [", 'supplier' AS party_type, NULL AS customer_tenant_id, NULL AS customer_user_id,
                   NULL AS customer_company_name, NULL AS customer_person_name, NULL AS customer_person_email", ''];
    }
    return [
        ", $c.party_type, $c.customer_tenant_id, $c.customer_user_id,
           cpt.name AS customer_company_name,
           COALESCE(NULLIF(cpu.display_name, ''), cpu.email) AS customer_person_name,
           cpu.email AS customer_person_email",
        "LEFT JOIN tenants cpt ON cpt.id = $c.customer_tenant_id
         LEFT JOIN users cpu ON cpu.id = $c.customer_user_id",
    ];
}

/**
 * One line naming who a contract is with, for lists, previews and payloads:
 * the supplier's name, or "Company · Person" / "Company" / "Person".
 *
 * @param array $row  a contract row carrying the contractPartySql() columns plus supplier_name and
 *     supplier_trading_name
 */
function contractPartyLabel(array $row): string
{
    if (($row['party_type'] ?? 'supplier') === CONTRACT_PARTY_CUSTOMER) {
        $parts = array_filter([trim((string)($row['customer_company_name'] ?? '')), trim((string)($row['customer_person_name'] ?? ''))], 'strlen');
        return implode(' · ', $parts);
    }
    $trading = trim((string)($row['supplier_trading_name'] ?? ''));
    return $trading !== '' ? $trading : trim((string)($row['supplier_name'] ?? ''));
}

/**
 * Check and normalise the party fields of a contract being saved. Throws
 * InvalidArgumentException with a message fit for the analyst.
 *
 * A supplier contract keeps supplier_id and clears the customer; a customer
 * contract keeps the customer (at least one of company or person) and clears the
 * supplier, so a contract never points both ways. The analyst must be able to see
 * the customer company and the person's company - otherwise they could put a
 * contract on a customer they cannot see, or learn a person exists by id.
 *
 * @param array $data  the submitted contract fields (untrusted) - party_type, supplier_id,
 *     customer_tenant_id, customer_user_id are read; the rest is ignored
 * @return array{party_type:string,supplier_id:?int,customer_tenant_id:?int,customer_user_id:?int}
 */
function contractNormaliseParty(PDO $conn, int $analystId, array $data): array
{
    $type = $data['party_type'] ?? null;
    if ($type === null || $type === '') $type = CONTRACT_PARTY_SUPPLIER;
    if ($type !== CONTRACT_PARTY_SUPPLIER && $type !== CONTRACT_PARTY_CUSTOMER) {
        // A typo through the API must not quietly become a supplier contract.
        throw new InvalidArgumentException(function_exists('t') ? t('contracts.party.err_type') : "party_type must be 'supplier' or 'customer'.");
    }
    $pos = fn($v) => (is_numeric($v) && (int)$v > 0) ? (int)$v : null;

    if ($type === CONTRACT_PARTY_SUPPLIER) {
        return ['party_type' => $type, 'supplier_id' => $pos($data['supplier_id'] ?? null),
                'customer_tenant_id' => null, 'customer_user_id' => null];
    }

    $tenant = $pos($data['customer_tenant_id'] ?? null);
    $user   = $pos($data['customer_user_id'] ?? null);
    if ($tenant === null && $user === null) {
        throw new InvalidArgumentException(function_exists('t') ? t('contracts.party.err_customer_required') : 'Choose the customer: a company, a person, or both.');
    }
    $multi = isMultiTenant($conn);
    if ($tenant !== null) {
        $s = $conn->prepare("SELECT 1 FROM tenants WHERE id = ?");
        $s->execute([$tenant]);
        if (!$s->fetchColumn() || ($multi && !analystCanAccessTenant($conn, $analystId, $tenant))) {
            throw new InvalidArgumentException(function_exists('t') ? t('contracts.party.err_company') : 'That company is not one you can access.');
        }
    }
    if ($user !== null) {
        $s = $conn->prepare("SELECT tenant_id FROM users WHERE id = ?");
        $s->execute([$user]);
        $row = $s->fetch(PDO::FETCH_ASSOC);
        $ut = $row ? ($row['tenant_id'] !== null ? (int)$row['tenant_id'] : getDefaultTenantId($conn)) : null;
        if (!$row || ($multi && !analystCanAccessTenant($conn, $analystId, $ut))) {
            throw new InvalidArgumentException(function_exists('t') ? t('contracts.party.err_person') : 'That person is not one you can see.');
        }
        if ($tenant !== null && $multi && $ut !== $tenant) {
            throw new InvalidArgumentException(function_exists('t') ? t('contracts.party.err_person_company') : 'That person is not in the company you chose.');
        }
    }
    return ['party_type' => $type, 'supplier_id' => null, 'customer_tenant_id' => $tenant, 'customer_user_id' => $user];
}
