<?php
/**
 * Self-Service Portal Authentication Guard
 * Include at the top of every authenticated self-service page (after session_start)
 */
if (!isset($_SESSION['ss_user_id'])) {
    header('Location: login.php');
    exit;
}

// Marked as left since signing in (3.5.0): sign them out here rather than let the
// session run on. See requesterIsActive() in includes/functions.php.
require_once __DIR__ . '/../../includes/functions.php';
if (!requesterIsActive(connectToDatabase(), (int)$_SESSION['ss_user_id'])) {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    unset($_SESSION['ss_user_id'], $_SESSION['ss_user_name'], $_SESSION['ss_user_email']);
    $_SESSION['sso_error'] = 'Your account is no longer active. Contact the service desk if you think this is a mistake.';
    header('Location: login.php');
    exit;
}

$ss_user_id = (int)$_SESSION['ss_user_id'];
$ss_user_name = $_SESSION['ss_user_name'] ?? 'User';
$ss_user_email = $_SESSION['ss_user_email'] ?? '';
