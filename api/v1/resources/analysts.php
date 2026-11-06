<?php
/**
 * FreeITSM REST API v1 — analyst provisioning (3.5.0, PR #174 from @Kraleemil's
 * design). Lets an identity source keep analysts and their access in step:
 *
 *   POST  /analysts               create (starts with no access; signs in via SSO)
 *   GET   /analysts/{id}          one analyst, active or not
 *   PATCH /analysts/{id}          full_name, email, is_active, auth_provider_id
 *   GET   /analysts/{id}/access   admin, modules, companies, capabilities (+ effective)
 *   PATCH /analysts/{id}/access   set any of them
 *
 * GET /analysts (the list) is in reference.php.
 *
 * The rules are in includes/services/analysts.php. This adapter adds the KEY
 * rule: 🔴 every write needs a key that (a) acts as a System administrator and
 * (b) is scoped to all companies. In the app only administrators manage analysts
 * (admin_api_guard.php); a key is held to the same rule, because a key that could
 * create staff or hand out modules and capabilities without being an admin could
 * grant its own analyst more than it has.
 */

require_once __DIR__ . '/../../../includes/services/analysts.php';

/** 403 unless this key may provision analysts. */
function apiAnalystsRequireProvisioner(PDO $conn, array $apiKey): void
{
    if ($apiKey['company_scope'] !== null) {
        apiError(403, 'forbidden', 'Analyst provisioning needs a key scoped to all companies: an analyst belongs to the whole install.');
    }
    if (empty($apiKey['analyst_id']) || !analystIsAdmin($conn, (int)$apiKey['analyst_id'])) {
        apiError(403, 'forbidden', 'Analyst provisioning needs a key that acts as a System administrator, as managing analysts does in the app.');
    }
}

/**
 * Shapes an analyst row for the API - id, username, name, email, active and admin flags,
 * sign-in provider and dates. Never the password hash or MFA secret.
 *
 * @param array $a  the analysts row as AnalystsService::load returns it
 */
function apiSerializeAnalyst(array $a): array
{
    return [
        'id'               => (int)$a['id'],
        'username'         => $a['username'],
        'name'             => $a['full_name'],
        'email'            => $a['email'],
        'is_active'        => (int)$a['is_active'] === 1,
        'is_admin'         => (int)$a['is_admin'] === 1,
        'auth_provider_id' => $a['auth_provider_id'] !== null ? (int)$a['auth_provider_id'] : null,
        'created_at'       => apiIsoDate($a['created_datetime'] ?? null),
        'last_login_at'    => apiIsoDate($a['last_login_datetime'] ?? null),
    ];
}

// POST /analysts
function apiAnalystsCreate(PDO $conn, array $apiKey, array $params, array $body): void
{
    apiAnalystsRequireProvisioner($conn, $apiKey);
    try {
        $id = AnalystsService::create($conn, ActorContext::fromApiKey($apiKey), $body);
        apiRespond(apiSerializeAnalyst(AnalystsService::load($conn, $id)), 201);
    } catch (ServiceError $e) { apiFailFromService($e); }
}

// GET /analysts/{id}
function apiAnalystsGet(PDO $conn, array $apiKey, array $params, array $body): void
{
    try {
        apiRespond(apiSerializeAnalyst(AnalystsService::load($conn, (int)$params[0])));
    } catch (ServiceError $e) { apiFailFromService($e); }
}

// PATCH /analysts/{id}
function apiAnalystsUpdate(PDO $conn, array $apiKey, array $params, array $body): void
{
    apiAnalystsRequireProvisioner($conn, $apiKey);
    try {
        AnalystsService::update($conn, ActorContext::fromApiKey($apiKey), (int)$params[0], $body);
        apiRespond(apiSerializeAnalyst(AnalystsService::load($conn, (int)$params[0])));
    } catch (ServiceError $e) { apiFailFromService($e); }
}

// GET /analysts/{id}/access
function apiAnalystsAccessGet(PDO $conn, array $apiKey, array $params, array $body): void
{
    try {
        apiRespond(AnalystsService::access($conn, (int)$params[0]));
    } catch (ServiceError $e) { apiFailFromService($e); }
}

// PATCH /analysts/{id}/access
function apiAnalystsAccessUpdate(PDO $conn, array $apiKey, array $params, array $body): void
{
    apiAnalystsRequireProvisioner($conn, $apiKey);
    try {
        AnalystsService::setAccess($conn, ActorContext::fromApiKey($apiKey), (int)$params[0], $body);
        apiRespond(AnalystsService::access($conn, (int)$params[0]));
    } catch (ServiceError $e) { apiFailFromService($e); }
}
