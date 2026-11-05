<?php
session_start();
require_once '../../config.php';
require_once '../../includes/ad/Admin.php';
header('Cache-Control: no-store');
if (empty($_SESSION['analyst_id'])) adDeny('Administrator access required.');
try { $s = adService(); } catch (PDOException $e) { adDeny('Database connection unavailable. Check the application database configuration.', 503); }
try { adRequireAdmin($s); } catch (Throwable $e) { adDeny('Administrator access required.'); }
// Upgrades may deploy the PHP files before running the additive database migration.
// Check the entire AD schema before rendering forms or accepting configuration writes.
try {
    foreach (['ad_settings', 'ad_providers', 'ad_profiles', 'ad_identities', 'ad_group_mappings', 'ad_verifications', 'ad_rate_limits', 'ad_audit'] as $table) {
        $s->query("SELECT 1 FROM $table LIMIT 0");
    }
    if (!$s->settings()) throw new AdFailure('ad_settings_missing');
} catch (Throwable $e) {
    $missingSchema = $e instanceof AdFailure || ($e instanceof PDOException && in_array($e->getCode(), ['42S02', '42S22'], true));
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Active Directory setup required</title></head><body><main>';
    if ($missingSchema) {
        echo '<h1>Active Directory database setup required</h1>';
        echo '<p>The Active Directory database migration has not been completed. The configuration page will be available after it runs.</p>';
        echo '<p>Open <a href="' . htmlspecialchars(BASE_URL . 'setup/', ENT_QUOTES) . '">Setup</a> and run <strong>DB Verify</strong>, or apply <code>database/migrations/20261103_active_directory.sql</code> using your deployment tooling.</p>';
        echo '<p>Back up the database before applying migrations. Reload this page afterward.</p>';
    } else {
        echo '<h1>Active Directory configuration unavailable</h1><p>The database could not be read. Check database connectivity and permissions, then retry.</p>';
    }
    echo '<p><a href="' . htmlspecialchars(BASE_URL . 'system/', ENT_QUOTES) . '">Return to System</a></p></main></body></html>';
    exit;
}
$error = ''; $message = $_SESSION['ad_admin_message'] ?? ''; unset($_SESSION['ad_admin_message']);
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        adCheckCsrf(); $admin = new AdAdmin($s); $action = $_POST['action'] ?? '';
        if (in_array($action, ['provider', 'settings', 'mapping', 'modules', 'delete_mapping'], true)) {
            $s->db->beginTransaction();
            // Serialize settings/mapping updates and refresh commits.
            $s->query('SELECT id FROM ad_settings WHERE id=1 FOR UPDATE');
            if ($action === 'provider') $admin->saveProvider($_POST);
            if ($action === 'settings') $admin->saveSettings($_POST);
            if ($action === 'mapping') $admin->mapping($_POST);
            if ($action === 'delete_mapping') {
                $s->query('DELETE FROM ad_group_mappings WHERE id=?', [(int)$_POST['mapping_id']]);
                $s->query('UPDATE ad_settings SET revision=revision+1 WHERE id=1');
                $s->audit('group_mapping_deleted', null, ['mapping_id' => (int)$_POST['mapping_id']]);
            }
            if ($action === 'modules') {
                $group = (int)$_POST['access_group_id'];
                $allowed = ['watchtower','tickets','assets','knowledge','changes','problems','calendar','morning-checks','reporting','software','forms','contracts','service-status','wiki','lms','process-mapper','tasks','cmdb','network-mapper','workflow','system'];
                $mods = array_values(array_unique(array_filter(array_map('trim', explode(',', $_POST['modules'] ?? '')))));
                if (array_diff($mods, $allowed)) throw new AdFailure('invalid_module_keys');
                $s->query('DELETE FROM access_group_modules WHERE access_group_id=?', [$group]);
                foreach ($mods as $module) $s->query('INSERT INTO access_group_modules (access_group_id, module_key) VALUES (?, ?)', [$group, $module]);
                $s->query('UPDATE ad_settings SET revision=revision+1 WHERE id=1');
                $s->audit('access_group_modules_updated', null, ['access_group_id' => $group, 'modules' => $mods]);
            }
            $s->db->commit();
        } elseif ($action === 'refresh') {
            $s->limit('admin_refresh', (string)$_SESSION['analyst_id'], 10, 60);
            $s->refresh((int)$_POST['profile_id']);
        } elseif ($action === 'reconcile') {
            $admin->reconcile($_POST);
        } elseif ($action === 'review_profile') {
            $email = AdService::email($_POST['email'] ?? '');
            $s->query('INSERT INTO ad_profiles (email) VALUES (?)', [$email]);
            $s->audit('identity_review_created', (int)$s->db->lastInsertId());
        } else { throw new AdFailure('unknown_action'); }
        $_SESSION['ad_admin_message'] = 'Changes saved.';
        header('Location: ' . BASE_URL . 'system/active-directory/'); exit;
    }
} catch (Throwable $e) {
    if ($s->db->inTransaction()) $s->db->rollBack();
    $error = $e instanceof AdFailure ? $e->reason : 'Operation failed. Check configuration and the server logs.';
}
$cfg = $s->settings(); $providers = $s->providers();
$groups = $s->query('SELECT * FROM access_groups ORDER BY display_name')->fetchAll(PDO::FETCH_ASSOC);
$profiles = $s->query('SELECT * FROM ad_profiles ORDER BY id DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
$mappings = $s->query('SELECT m.*, ag.display_name FROM ad_group_mappings m JOIN access_groups ag ON ag.id=m.access_group_id ORDER BY precedence, id')->fetchAll(PDO::FETCH_ASSOC);
$selected = null;
if (!empty($_GET['profile_id'])) { try { $selected = $s->profile((int)$_GET['profile_id']); } catch (Throwable $e) { $error = 'Profile unavailable.'; } }
function adEscape($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function adInput(string $name, string $label, $value = '', string $type = 'text'): void {
    echo '<label>' . adEscape($label) . '<input name="' . adEscape($name) . '" type="' . $type . '" value="' . adEscape($value) . '"></label>';
}
function adCheck(string $name, string $label, $value): void {
    echo '<label><input type="checkbox" name="' . adEscape($name) . '" value="1" ' . ($value ? 'checked' : '') . '>' . adEscape($label) . '</label>';
}
function adForm(string $action): void { echo '<form method="post"><input type="hidden" name="csrf" value="' . adCsrf() . '"><input type="hidden" name="action" value="' . adEscape($action) . '">'; }
function adProvidersSelect(string $key, string $label, array $providers, $selected): void {
    echo '<label>' . adEscape($label) . '<select name="' . $key . '"><option value="">Select provider</option>';
    foreach ($providers as $p) echo '<option value="' . (int)$p['id'] . '" ' . ((int)$p['id'] === (int)$selected ? 'selected' : '') . '>' . adEscape($p['name']) . '</option>';
    echo '</select></label>';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Active Directory &amp; User Authorization</title><link rel="stylesheet" href="../../assets/css/ad.css"></head><body><main class="ad-panel ad-wide">
<p><a href="../">System administration</a></p><h1>Active Directory &amp; User Authorization</h1>
<p role="status"><?= adEscape($message) ?></p><p role="alert"><?= adEscape($error) ?></p>
<p>One domain is sufficient. Additional trusted authentication domains are optional. Exactly one provider controls authorization. Local and OpenID Connect sign in remain available.</p>
<details><summary>Corporate authentication settings</summary>
<?php adForm('settings'); adCheck('enabled', 'Enable Active Directory', $cfg['enabled']); adCheck('multi_domain', 'Enable multiple authentication domains (optional)', $cfg['multi_domain']); adCheck('allow_fallback', 'Allow controlled password fallback to a second trusted domain', $cfg['allow_fallback']); ?>
<div class="ad-grid"><?php
adProvidersSelect('default_provider_id', 'Default authentication provider', $providers, $cfg['default_provider_id']);
adProvidersSelect('authority_id', 'Sole authorization authority', $providers, $cfg['authority_id']);
adInput('max_attempts', 'Maximum user binds per login (1–2; single domain always 1)', $cfg['max_attempts'], 'number');
adInput('allowed_domains', 'Allowed corporate email domains (comma separated)', implode(', ', json_decode($cfg['allowed_domains'], true)));
adInput('base_url', 'Public HTTPS application URL', $cfg['base_url'], 'url');
adInput('mailbox_id', 'Existing outbound SMTP mailbox ID', $cfg['mailbox_id'], 'number');
adInput('verification_minutes', 'Verification expiration (5–30 minutes)', $cfg['verification_minutes'], 'number');
adInput('refresh_seconds', 'Session refresh interval (60–3600 seconds)', $cfg['refresh_seconds'], 'number');
adInput('sensitive_seconds', 'Privileged authorization maximum age (0–300 seconds)', $cfg['sensitive_seconds'], 'number');
adInput('emergency_analyst_id', 'Protected local emergency administrator ID', $cfg['emergency_analyst_id'], 'number');
adInput('emergency_password', 'Verify emergency administrator password when enabling/saving enabled AD', '', 'password');
?></div><button>Save corporate settings</button></form></details>
<h2>Directory providers</h2>
<?php foreach (array_merge($providers, [['id'=>0, 'name'=>'New provider', 'config'=>[]]]) as $p): $c=$p['config']; ?>
<details><summary><?= adEscape($p['name']) ?><?= $p['id'] ? ' (#' . (int)$p['id'] . ')' : '' ?></summary>
<?php adForm('provider'); adInput('id', '', $p['id'], 'hidden'); adInput('name', 'Provider name', $p['id'] ? $p['name'] : '');
adCheck('enabled', 'Enabled', $p['enabled'] ?? false); adCheck('trusted', 'Explicitly trusted corporate identity namespace', $p['trusted'] ?? false); adCheck('authentication_enabled', 'Allow authentication', $p['authentication_enabled'] ?? true); ?>
<div class="ad-grid"><?php
foreach (['domain_dns'=>'Domain DNS name','domain_suffix'=>'Domain UPN suffix','primary'=>'Primary controller hostname','secondary'=>'Secondary controller hostname (optional)','base_dn'=>'Base DN','user_base'=>'User search base (blank uses base DN)','group_base'=>'Group search base (blank uses base DN)','bind_identity'=>'Read-only service account DN or UPN','ca_file'=>'Mounted trusted CA file (blank uses system trust)'] as $key=>$label) adInput($key,$label,$c[$key]??'');
?><label>Encrypted transport<select name="transport"><option value="ldaps">LDAPS</option><option value="starttls" <?= ($c['transport']??'')==='starttls'?'selected':'' ?>>StartTLS</option></select></label><?php
adInput('port','LDAP port',$c['port']??636,'number'); adInput('timeout','Network/search timeout (1–15 seconds)',$c['timeout']??5,'number');
adInput('priority','Provider priority (lower first)',$p['priority']??100,'number');
foreach (['username_attribute'=>'sAMAccountName','email_attribute'=>'mail','display_attribute'=>'displayName'] as $key=>$default) adInput($key,$key,$c[$key]??$default);
adInput('service_password','Service account password (blank keeps stored secret)','','password');
?></div><?php adCheck('nested_groups','Resolve nested Security Groups',$c['nested_groups']??true); ?><p>TLS certificate and hostname validation is mandatory. Service credentials are encrypted and never returned.</p><button>Save provider</button></form></details>
<?php endforeach; ?>
<h2>Authoritative group mappings</h2>
<p>Use 32 lowercase hex characters from the raw AD objectGUID bytes. Lower precedence values win the displayed role; module grants from all matching active groups are combined. Empty module grants always mean Guest for AD.</p>
<table><thead><tr><th>AD group</th><th>Access group</th><th>Precedence</th><th>Action</th></tr></thead><tbody>
<?php foreach ($mappings as $m): ?><tr><td><?= adEscape($m['group_name']) ?> (#<?= (int)$m['provider_id'] ?>)</td><td><?= adEscape($m['display_name']) ?></td><td><?= (int)$m['precedence'] ?></td><td><?php adForm('delete_mapping'); adInput('mapping_id','',$m['id'],'hidden'); ?><button>Remove mapping</button></form></td></tr><?php endforeach; ?></tbody></table>
<details><summary>Add or update group mapping</summary><?php adForm('mapping'); adProvidersSelect('provider_id','Authorization provider',$providers,$cfg['authority_id']); adInput('group_guid','AD group objectGUID (raw hex)'); adInput('group_name','Group display name'); ?>
<label>Existing access group<select name="access_group_id"><?php foreach ($groups as $g): ?><option value="<?= (int)$g['id'] ?>"><?= adEscape($g['display_name']) ?></option><?php endforeach; ?></select></label><?php adInput('precedence','Role precedence',100,'number'); ?><button>Save mapping</button></form></details>
<details><summary>Existing access-group module permissions</summary><p>These are the existing shared RBAC groups, also used by OIDC. Changes affect every member of the selected group.</p>
<?php foreach ($groups as $g): $mods=$s->query('SELECT module_key FROM access_group_modules WHERE access_group_id=?',[$g['id']])->fetchAll(PDO::FETCH_COLUMN); adForm('modules'); adInput('access_group_id','',$g['id'],'hidden'); adInput('modules',$g['display_name'].' — comma separated module keys',implode(',',$mods)); ?><button>Save group modules</button></form><?php endforeach; ?></details>
<h2>User Authorization</h2>
<form method="get"><label>Inspect profile ID<input name="profile_id" type="number" min="1"></label><button>Inspect</button></form>
<table><thead><tr><th>Profile</th><th>Email</th><th>Status</th><th>Refreshed (UTC)</th></tr></thead><tbody><?php foreach ($profiles as $p): ?><tr><td><a href="?profile_id=<?= (int)$p['id'] ?>"><?= (int)$p['id'] ?></a></td><td><?= adEscape($p['email']) ?></td><td><?= adEscape($p['authorization_status']) ?></td><td><?= adEscape($p['refreshed_at']) ?></td></tr><?php endforeach; ?></tbody></table>
<?php if ($selected): $pid=(int)$selected['id']; ?>
<h3><?= adEscape($selected['email']) ?></h3><pre><?= adEscape(json_encode($selected,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)) ?></pre>
<h4>Linked immutable AD identities</h4><pre><?= adEscape(json_encode($s->query('SELECT * FROM ad_identities WHERE profile_id=?',[$pid])->fetchAll(PDO::FETCH_ASSOC),JSON_PRETTY_PRINT)) ?></pre>
<?php adForm('refresh'); adInput('profile_id','',$pid,'hidden'); ?><button>Refresh Authorization / Test Lookup</button></form>
<h4>Authentication and authorization history (latest 100)</h4><pre><?= adEscape(json_encode($s->query('SELECT * FROM ad_audit WHERE profile_id=? ORDER BY id DESC LIMIT 100',[$pid])->fetchAll(PDO::FETCH_ASSOC),JSON_PRETTY_PRINT)) ?></pre>
<details><summary>Reviewed identity reconciliation / controlled email change</summary><p>Retains existing internal IDs and ticket relationships. Does not merge profiles or replace immutable directory GUIDs. New emails must resolve to every previously linked GUID. Review employee identity out of band before adopting legacy records.</p>
<?php adForm('reconcile'); adInput('profile_id','',$pid,'hidden'); adInput('email','Reviewed corporate email',$selected['email'],'email'); adInput('user_id','Existing requester ID (optional)',$selected['user_id'],'number'); adInput('analyst_id','Existing analyst ID (optional)',$selected['analyst_id'],'number'); adInput('review_reason','Identity review evidence / reason'); ?><button>Record reviewed identity</button></form></details>
<?php endif; ?>
<details><summary>Create identity review for an existing employee</summary><p>Existing accounts cannot self-register or merge by email. Create a review profile, then inspect it and explicitly reconcile existing IDs.</p><?php adForm('review_profile'); adInput('email','Corporate email','','email'); ?><button>Create pending review</button></form></details>
</main></body></html>
