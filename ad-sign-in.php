<?php
session_start();
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/ad.php';
header('Cache-Control: no-store');
header('Referrer-Policy: no-referrer');
$message=''; $providers=[]; $enabled=false;
try {
    $db=connectToDatabase(); $cfg=adConfig($db); $enabled=(bool)$cfg['enabled'];
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        adCheckCsrf($_POST['csrf'] ?? '');
        adRateLimit($db,'verify-ip',$_SERVER['REMOTE_ADDR'] ?? 'unknown',10,900);
        adVerifyEmail($db,(string)($_POST['token'] ?? ''));
        $message='Email verified. Sign in with your AD password to continue.';
    }
    if ($cfg['multi_domain']) foreach(adProviders($db) as $p) {
        if ($p['enabled'] && $p['authentication_enabled']) $providers[]=['id'=>(int)$p['id'],'name'=>$p['display_name']];
    }
} catch (Throwable $e) { $message='Verification or AD configuration unavailable. Contact your administrator.'; }
$csrf=adCsrf();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Corporate AD sign-in — FreeITSM</title>
<style>body{font:16px system-ui;background:#f3f5f8;color:#223;margin:40px auto;max-width:480px;padding:20px}main{background:white;padding:28px;border-radius:10px}label{display:block;margin:14px 0}input,select,button{box-sizing:border-box;width:100%;padding:10px;margin-top:6px}button{cursor:pointer}a{color:#3458a4}#result{white-space:pre-wrap}</style></head><body><main><h1>Corporate AD sign-in</h1>
<p id="result" role="status"><?=htmlspecialchars($message)?></p>
<?php if (!empty($_GET['token']) && $_SERVER['REQUEST_METHOD']!=='POST'): ?>
<form method="post"><input type="hidden" name="csrf" value="<?=htmlspecialchars($csrf)?>"><input type="hidden" name="token" value="<?=htmlspecialchars((string)$_GET['token'])?>"><button>Verify my email</button></form>
<?php elseif ($enabled): ?>
<form id="signin"><label>Corporate email<input name="email" type="email" autocomplete="username" required maxlength="255"></label><label>AD password<input name="password" type="password" autocomplete="current-password" required></label>
<?php if ($providers): ?><label>Domain (optional)<select name="provider_id"><option value="">Automatic</option><?php foreach($providers as $p): ?><option value="<?=$p['id']?>"><?=htmlspecialchars($p['name'])?></option><?php endforeach ?></select></label><?php endif ?>
<button>Sign in</button></form><hr><form id="register"><h2>Verify your corporate email</h2><p>New employees can request or resend a verification link. Existing accounts require administrator review.</p><label>Email<input name="email" type="email" required maxlength="255"></label><button>Send verification link</button></form>
<?php else: ?><p>Corporate AD sign-in is not enabled.</p><?php endif ?>
<p><a href="<?=htmlspecialchars(BASE_URL)?>login.php">Local or OIDC sign-in</a></p></main>
<script>
const csrf=<?=json_encode($csrf,JSON_HEX_TAG|JSON_HEX_AMP)?>;
for(const name of ['signin','register']) document.getElementById(name)?.addEventListener('submit',async e=>{
 e.preventDefault();const form=e.target,button=form.querySelector('button');button.disabled=true;
 try{const data=Object.fromEntries(new FormData(form));const r=await fetch('api/ad/'+(name==='signin'?'login':'register')+'.php',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-Token':csrf},body:JSON.stringify(data)});const j=await r.json();document.getElementById('result').textContent=j.message||j.error||'Signed in';if(j.redirect)location.assign(j.redirect);}
 catch{document.getElementById('result').textContent='Request failed. Please try again.';}finally{button.disabled=false;if(name==='signin')form.password.value='';}
});
</script></body></html>
