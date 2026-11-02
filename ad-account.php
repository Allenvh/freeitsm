<?php
session_start();
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/ad.php';
if (empty($_SESSION['ad_profile_id'])) { header('Location: '.BASE_URL.'ad-sign-in.php'); exit; }
$db=connectToDatabase(); $p=adProfile($db,(int)$_SESSION['ad_profile_id']); $cfg=adConfig($db);
header('Cache-Control: no-store');
?>
<!doctype html><html lang="en"><meta charset="utf-8"><title>My permissions — FreeITSM</title><body><main><h1>My permissions</h1><p><?=htmlspecialchars($p['email'])?></p><p>Authorization source: <?=$cfg['authority_provider_id']?'Corporate AD':'FreeITSM'?></p><p>Last updated: <?=htmlspecialchars($p['authorization_checked_at'] ?? 'Not applicable')?></p>
<?php if ($cfg['authority_provider_id']): ?><button id="refresh">Refresh My Permissions</button><?php else: ?><p>Permissions are managed in FreeITSM.</p><?php endif ?>
<pre id="result" role="status"></pre><a href="<?=htmlspecialchars(BASE_URL)?>self-service/">Self-service portal</a></main><script>
let csrf=<?=json_encode(adCsrf(),JSON_HEX_TAG)?>;
document.getElementById('refresh')?.addEventListener('click',async()=>{try{const r=await fetch('api/ad/refresh.php',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-Token':csrf},body:'{}'});const j=await r.json();if(j.csrf)csrf=j.csrf;document.getElementById('result').textContent=JSON.stringify(j,null,2);}catch{document.getElementById('result').textContent='Refresh unavailable';}});
</script></body></html>
