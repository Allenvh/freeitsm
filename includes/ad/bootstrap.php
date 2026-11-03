<?php
require_once __DIR__ . '/Service.php';
function adService(): AdService {
    static $service;
    return $service ??= new AdService(new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
}
function adCsrf(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return $_SESSION['ad_csrf'] ??= bin2hex(random_bytes(32));
}
function adCheckCsrf(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(adCsrf(), (string)($_POST['csrf'] ?? ''))) throw new AdFailure('csrf_failed');
}
function adDeny(string $reason = 'Authorization unavailable', int $code = 403): never {
    http_response_code($code);
    if (str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/api/')) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => $reason]); }
    else { echo htmlspecialchars($reason) . ' <a href="' . htmlspecialchars(BASE_URL . 'ad-permissions.php') . '">Refresh permissions</a> <a href="' . htmlspecialchars(BASE_URL . 'login.php?local=1') . '">Local sign in</a>'; }
    exit;
}
function adRequireAdmin(AdService $s): void {
    if (empty($_SESSION['analyst_id'])) throw new AdFailure('administrator_required');
    // For legacy local/OIDC accounts reuse the existing module permission model.
    require_once __DIR__ . '/../functions.php';
    $modules = getAnalystAllowedModules($s->db, (int)$_SESSION['analyst_id']);
    if ($modules !== null && (empty($_SESSION['ad_profile_id']) || !in_array('system', $modules, true))) throw new AdFailure('administrator_required');
}
/** True only for enrolled identities; unmigrated local/OIDC deployments are unchanged. */
function adManaged(PDO $db, string $column, int|string $value): bool {
    if (!in_array($column, ['analyst_id', 'user_id', 'email'], true)) throw new LogicException('Invalid identity selector');
    try { $s = $db->prepare("SELECT 1 FROM ad_profiles WHERE $column = ?"); $s->execute([$value]); return (bool)$s->fetchColumn(); }
    catch (PDOException $e) { if ($e->getCode() === '42S02') return false; throw $e; }
}
function adProtectAnalyst(PDO $db, int $id, bool $protectEmergency = true): void {
    if (!$id) return;
    if (adManaged($db, 'analyst_id', $id)) throw new AdFailure('Manage AD identities in Active Directory administration.');
    if ($protectEmergency) {
        try { $s = $db->prepare('SELECT 1 FROM ad_settings WHERE emergency_analyst_id = ?'); $s->execute([$id]); if ($s->fetchColumn()) throw new AdFailure('Protected emergency administrator. Reassign it in AD settings first.'); }
        catch (PDOException $e) { if ($e->getCode() !== '42S02') throw $e; }
    }
}
