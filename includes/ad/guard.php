<?php
/** Runs from both supported configs, before any page/API authorization or output. */
if (PHP_SAPI !== 'cli' && (!empty($_SESSION['ad_profile_id']) || !empty($_SESSION['analyst_id']) || !empty($_SESSION['ss_user_id']))) {
    require_once __DIR__ . '/bootstrap.php';
    $adService = adService();
    try {
        $adId = (int)($_SESSION['ad_profile_id'] ?? 0);
        if (!$adId) {
            // Refuse legacy sessions for accounts subsequently enrolled in AD.
            $adId = (int)$adService->query('SELECT id FROM ad_profiles WHERE analyst_id = ? OR user_id = ?', [$_SESSION['analyst_id'] ?? 0, $_SESSION['ss_user_id'] ?? 0])->fetchColumn();
            if ($adId) adDeny('Please sign in with Active Directory.');
        }
        if ($adId) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $adPath = ltrim(substr($_SERVER['SCRIPT_NAME'] ?? '', strlen(rtrim(BASE_URL, '/'))), '/');
            $adRecovery = in_array($adPath, ['ad-permissions.php', 'logout.php', 'analyst_logout.php', 'self-service/logout.php', 'ad-login.php', 'login.php'], true);
            $adCfg = $adService->settings(); $adProfile = $adService->profile($adId);
            $adAdministrative = preg_match('~^(api/)?(system|settings)/|/settings/|(?:save|delete|reset|toggle|create)_(?:analyst|user|mailbox)|(?:save|test|verify|generate|import)_[^/]*(?:settings|mailbox|provider|encryption|config|smtp|imap)|/get_mailboxes\.php$~', $adPath) === 1;
            $adSensitive = $adAdministrative || preg_match('~^(api/)?(workflow|network-mapper|intune|messaging)/~', $adPath) === 1;
            $adProfile = $adService->sessionProfile($adId, $adSensitive, $adRecovery);
            if (!$adRecovery) {
                // Self-service uses its existing requester ownership checks. All other
                // paths must have a current explicitly granted analyst module.
                $adPortal = str_starts_with($adPath, 'self-service/') || str_starts_with($adPath, 'api/self-service/');
                if (!$adPortal) {
                    if (empty($_SESSION['analyst_id']) || $adProfile['authorization_status'] !== 'current') adDeny();
                    $adModule = preg_replace('~^api/~', '', $adPath);
                    $adModule = explode('/', $adModule)[0];
                    $adAliases = ['asset-management' => 'assets', 'change-management' => 'changes', 'problem-management' => 'problems', 'system-wiki' => 'wiki', 'process-mapper' => 'process-mapper'];
                    $adModule = $adAliases[$adModule] ?? $adModule;
                    if ($adAdministrative && !in_array('system', $_SESSION['allowed_modules'] ?? [], true)) adDeny('Administrative permissions required.');
                    $adOwn = str_starts_with($adPath, 'api/myaccount/') && !str_contains($adPath, 'password');
                    if ($adModule !== 'index.php' && !$adOwn && !in_array($adModule, $_SESSION['allowed_modules'] ?? [], true)) adDeny('This module is not permitted.');
                }
                // AD-managed identity/security fields cannot be changed by legacy local APIs.
                if (preg_match('~(change_password|register|verify_login_otp)\.php$~', $adPath)) adDeny('Manage directory identity through your administrator.');
            }
        }
    } catch (Throwable $adError) {
        // A missing migration must not break existing local/OIDC installs.
        // Once AD is in the session, every exception fails closed.
        if (!empty($_SESSION['ad_profile_id'])) {
            unset($_SESSION['analyst_id'], $_SESSION['allowed_modules']);
            adDeny('Authorization unavailable. Try refreshing permissions.');
        }
    }
    unset($adService, $adId, $adProfile, $adCfg);
}
