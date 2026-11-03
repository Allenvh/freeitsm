<?php
session_start();
require_once 'config.php';
require_once 'includes/ad/bootstrap.php';
header('Cache-Control: no-store');
if (empty($_SESSION['ad_profile_id'])) { header('Location: ' . BASE_URL . 'ad-login.php'); exit; }
$message = '';
try {
    $s = adService(); $id = (int)$_SESSION['ad_profile_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        adCheckCsrf(); $s->limit('manual_refresh', (string)$id, 1, 30);
        $s->refresh($id); $message = 'Permissions refreshed successfully.';
    }
} catch (Throwable $e) { $message = 'Permissions could not be refreshed. Authorization may be unavailable; try again after 30 seconds or contact an administrator.'; }
try { $p = $s->profile($id); $s->applySession($p); $state = json_decode($p['authorization_json'] ?? '{}', true); }
catch (Throwable $e) { adDeny(); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>My permissions — FreeITSM</title><link rel="stylesheet" href="assets/css/ad.css"></head><body><main class="ad-panel"><h1>My Profile — Permissions</h1>
<p role="status"><?= htmlspecialchars($message) ?></p><dl>
<dt>Current access level</dt><dd><?= htmlspecialchars($p['authorization_status'] === 'current' ? ($state['role'] ?? 'Guest') : 'Authorization unavailable') ?></dd>
<dt>Authorization source</dt><dd><?= htmlspecialchars($state['authority_name'] ?? 'Not available') ?></dd>
<dt>Last updated (UTC)</dt><dd><?= htmlspecialchars($p['refreshed_at'] ?? 'Never') ?></dd>
<dt>Status</dt><dd><?= htmlspecialchars($p['authorization_status']) ?></dd></dl>
<form method="post"><input type="hidden" name="csrf" value="<?= adCsrf() ?>"><button>Refresh My Permissions</button></form>
<p><a href="<?= isset($_SESSION['analyst_id']) ? 'index.php' : 'self-service/index.php' ?>">Return to FreeITSM</a> · <a href="self-service/logout.php">Sign out</a></p>
</main></body></html>
