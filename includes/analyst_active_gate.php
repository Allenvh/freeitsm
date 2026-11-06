<?php
/**
 * Signs out an analyst who has been deactivated or deleted, on their next request.
 *
 * 🔴 Deactivating somebody (System -> Analysts, or includes/analyst_lifecycle.php)
 * used to leave their open sessions working. Every module gate re-reads is_active,
 * so the modules closed - but any endpoint that only asked "is somebody signed in?"
 * kept answering: their own account settings, preferences, notifications, and
 * anything else gated on the session alone. Reported privately, October 2026, as
 * part of "a demoted admin keeps admin rights" (see sessionIsAdmin()).
 *
 * One primary-key lookup per request, for signed-in analysts only. Runs on include,
 * from functions.php, like password_gate.php - so nothing has to remember to call it.
 *
 * TRAP: it must not sign out on a database error. Setup and Database Verification
 *   run before the analysts table is complete, and a failed lookup is not evidence
 *   that anybody was deactivated. The module and admin gates still fail closed.
 */

/** Ends the session of an analyst who is no longer active, and stops the request. */
function enforceAnalystStillActive(): void
{
    if (PHP_SAPI === 'cli' || empty($_SESSION['analyst_id']) || !defined('DB_SERVER')) {
        return;
    }
    // The sign-in and sign-out pages themselves, so a stale session can always leave.
    $script = basename((string)($_SERVER['SCRIPT_NAME'] ?? ''));
    if (in_array($script, ['login.php', 'logout.php', 'analyst_logout.php'], true)) {
        return;
    }

    try {
        $stmt = connectToDatabase()->prepare("SELECT COALESCE(is_active, 1) FROM analysts WHERE id = ?");
        $stmt->execute([(int)$_SESSION['analyst_id']]);
        $active = $stmt->fetchColumn();
    } catch (Throwable) {
        return;   // see the TRAP above
    }
    if ($active !== false && (int)$active === 1) {
        return;
    }

    // Deactivated, or the row is gone. Most endpoints open the session read_and_close,
    // so reopen it to actually destroy it rather than just refusing this one request.
    if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
        @session_start();
    }
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    $isApi = str_contains((string)($_SERVER['SCRIPT_NAME'] ?? ''), '/api/')
          || stripos((string)($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json') !== false
          || strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
    if ($isApi) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        exit;
    }
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . 'auth/login.php');
    exit;
}

enforceAnalystStillActive();
