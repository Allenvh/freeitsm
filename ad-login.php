<?php
session_start();
require_once 'config.php';
require_once 'includes/ad/bootstrap.php';
header('Referrer-Policy: no-referrer');
header('Cache-Control: no-store');
$message = ''; $error = ''; $available = false; $providers = []; $cfg = [];
try {
    $s = adService(); $cfg = $s->settings(); $available = (bool)$cfg['enabled'];
    if (!$available) throw new AdFailure('Corporate sign in is not enabled.');
    $providers = $s->candidates($cfg, $s->providers());
    if (isset($_GET['verify'])) {
        // Require a POST confirmation, so mail security scanners do not consume links.
        if (preg_match('/^[a-f0-9]{64}$/D', (string)$_GET['verify'])) $_SESSION['ad_verification_token'] = $_GET['verify'];
        header('Location: ' . BASE_URL . 'ad-login.php?confirm=1'); exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        adCheckCsrf();
        $action = $_POST['action'] ?? '';
        if ($action === 'verify') {
            $s->verify($_SESSION['ad_verification_token'] ?? ''); unset($_SESSION['ad_verification_token']);
            $message = 'Email verified. Sign in with your corporate credentials to continue.';
        } elseif ($action === 'register') {
            try {
                $s->requestVerification((string)($_POST['email'] ?? ''), function($email, $url, $cfg) use ($s) {
                    require_once __DIR__ . '/includes/mail/SmtpMailer.php';
                    $mailbox = $s->query('SELECT * FROM target_mailboxes WHERE id=? AND is_active=1', [$cfg['mailbox_id']])->fetch(PDO::FETCH_ASSOC);
                    if (!$mailbox) throw new AdFailure('smtp_unavailable');
                    $mailbox = decryptMailboxRow($mailbox);
                    if (!in_array($mailbox['smtp_encryption'] ?? '', ['tls', 'ssl'], true)) throw new AdFailure('encrypted_smtp_required');
                    SmtpMailer::send($mailbox, $email, 'Verify your FreeITSM corporate email', '<p>Confirm your corporate email. This link expires shortly and can be used once.</p><p><a href="' . htmlspecialchars($url, ENT_QUOTES) . '">Verify email</a></p><p>Ignore this message if you did not request it.</p>');
                });
            } catch (AdFailure $e) { if ($e->reason !== 'rate_limited') throw $e; }
            $message = 'If this address is eligible, a verification email will be sent. Existing accounts require administrator review. You may use this form to resend a link.';
        } elseif ($action === 'login') {
            try {
                $profile = $s->login((string)($_POST['email'] ?? ''), (string)($_POST['password'] ?? ''), !empty($_POST['provider_id']) ? (int)$_POST['provider_id'] : null);
                $s->applySession($profile, true);
                header('Location: ' . BASE_URL . (isset($_SESSION['analyst_id']) ? 'index.php' : 'self-service/index.php')); exit;
            } catch (Throwable $e) {
                $s->audit('sign_in_denied', null, ['reason' => $e instanceof AdFailure ? $e->reason : 'internal_error']);
                $error = 'Sign in failed or authorization is unavailable. Check your credentials or contact your administrator.';
            }
        }
    }
} catch (Throwable $e) { $error = $available ? 'Request could not be completed. Please try again or contact your administrator.' : 'Corporate sign in is not enabled. Use your existing sign-in method.'; }
$csrf = adCsrf();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Corporate sign in — FreeITSM</title>
<link rel="stylesheet" href="assets/css/ad.css"></head><body><main class="ad-panel">
<h1>Corporate sign in</h1>
<?php if ($message): ?><p role="status"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($available): ?>
<?php if (!empty($_SESSION['ad_verification_token'])): ?>
<form method="post"><input type="hidden" name="csrf" value="<?= $csrf ?>"><input type="hidden" name="action" value="verify"><button>Confirm email ownership</button></form>
<?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?= $csrf ?>"><input type="hidden" name="action" value="login">
<label>Corporate email<input name="email" type="email" required autocomplete="username" maxlength="255"></label>
<label>Password<input name="password" type="password" required autocomplete="current-password" maxlength="4096"></label>
<?php if ($cfg['multi_domain']): ?>
<details><summary>Choose a domain (optional)</summary><label>Authentication domain<select name="provider_id"><option value="">Automatic</option><?php foreach ($providers as $p): ?><option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option><?php endforeach; ?></select></label></details>
<?php endif; ?><button>Sign in</button></form>
<details><summary>Create an account or resend verification</summary><form method="post"><input type="hidden" name="csrf" value="<?= $csrf ?>"><input type="hidden" name="action" value="register"><label>Corporate email<input name="email" type="email" required maxlength="255" autocomplete="email"></label><button>Send verification email</button></form></details>
<p>Forgot your corporate password? Contact your directory administrator.</p>
<?php endif; ?>
<p><a href="login.php?local=1">Local or OpenID Connect sign in</a></p></main></body></html>
