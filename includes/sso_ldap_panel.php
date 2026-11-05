<?php
// Native LDAP provider UI on the existing Authentication page; secrets are write-only.
$ldapDefaults = ldapFlavourDefaults('ad');
?>
<details id="ldapPanel" style="margin:20px 0;padding:20px;border:1px solid #ccc">
<summary><strong>LDAP / Active Directory — Add or edit a directory</strong></summary>
<p>Uses upstream FreeITSM's native directory authentication. Sign in on the normal login page with your directory username/email and password. No registration email or SMTP mailbox is required.</p>
<p>Configure analyst and self-service groups to limit access. With both group fields blank, upstream's default allows every authenticated directory user analyst access. New-account creation is controlled separately below.</p>
<form id="ldapForm" autocomplete="off">
<input type="hidden" id="ldapId" value="0">
<label>Preset <select id="ldapPreset"><option value="ad">Active Directory</option><option value="openldap">OpenLDAP</option></select></label>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px;margin-top:12px">
<?php foreach ([
'display_name'=>'Provider name','ldap_host'=>'Directory hostname','ldap_port'=>'Port','ldap_bind_dn'=>'Read-only service account DN / UPN','ldap_bind_password'=>'Service password (blank keeps stored value)',
'ldap_base_dn'=>'User search base DN','ldap_user_filter'=>'User filter (%s = escaped login)',
'ldap_attr_username'=>'Username attribute','ldap_attr_email'=>'Email attribute','ldap_attr_name'=>'Display name attribute','ldap_attr_guid'=>'Immutable identity attribute',
'ldap_group_base_dn'=>'Group search base DN','ldap_group_filter'=>'Group filter (%s = escaped user DN)',
'ldap_analyst_group'=>'Analyst group (name or full DN)','ldap_user_group'=>'Self-service group (name or full DN)',
'default_modules'=>'Default modules for new analysts (comma separated; blank = all)',
'sort_order'=>'Provider sort order','tenant_id'=>'Company ID (blank = global)',
] as $key=>$label): ?>
<label><?= htmlspecialchars($label) ?><input style="display:block;width:100%;box-sizing:border-box" id="ldap_<?= $key ?>" type="<?= $key==='ldap_bind_password'?'password':'text' ?>" value="<?= htmlspecialchars($key==='ldap_port'?'636':($ldapDefaults[$key]??''),ENT_QUOTES) ?>"></label>
<?php endforeach; ?>
<label>Transport<select id="ldapTransport"><option value="ldaps">LDAPS</option><option value="starttls">StartTLS</option><option value="none">None (isolated lab only)</option></select></label>
</div>
<label><input id="ldapEnabled" type="checkbox"> Enabled</label>
<label><input id="ldapAutoAnalysts" type="checkbox"> Auto-create analysts (requires an analyst group match when groups are configured)</label>
<label><input id="ldapAutoUsers" type="checkbox"> Auto-create self-service users</label>
<p><button type="submit">Save directory</button> <button type="button" id="ldapNew">New directory</button></p>
<details><summary>Test connection and optional user credentials</summary>
<label>Test username<input id="ldapTestUser" autocomplete="off"></label>
<label>Test password<input id="ldapTestPassword" type="password" autocomplete="new-password"></label>
<button type="button" id="ldapTest">Test LDAP connection</button>
</details>
<p id="ldapResult" role="status"></p>
</form>
<p>Enterprise CA certificates must be trusted in the application container. Review imported providers and group gates before enabling them. Legacy custom group-GUID mappings and periodic refresh are not used.</p>
</details>
<script>
const LDAP_PRESETS=<?= json_encode(['ad'=>ldapFlavourDefaults('ad'),'openldap'=>ldapFlavourDefaults('openldap')]) ?>;
const LDAP_FIELDS=<?= json_encode(['display_name','ldap_host','ldap_port','ldap_bind_dn','ldap_bind_password','ldap_base_dn','ldap_user_filter','ldap_attr_username','ldap_attr_email','ldap_attr_name','ldap_attr_guid','ldap_group_base_dn','ldap_group_filter','ldap_analyst_group','ldap_user_group','default_modules','sort_order','tenant_id']) ?>;
function openLdapModal(p) {
    const panel=document.getElementById('ldapPanel');panel.open=true;
    document.getElementById('ldapId').value=p?p.id:0;
    LDAP_FIELDS.forEach(k=>{document.getElementById('ldap_'+k).value=k==='ldap_bind_password'?'':(p?(p[k]??''):(k==='ldap_port'?636:(LDAP_PRESETS.ad[k]??'')));});
    document.getElementById('ldapTransport').value=p?(p.ldap_encryption||'ldaps'):'ldaps';
    document.getElementById('ldapEnabled').checked=p?!!p.enabled:false;
    document.getElementById('ldapAutoAnalysts').checked=p?!!p.auto_create_analysts:false;
    document.getElementById('ldapAutoUsers').checked=p?!!p.auto_create_users:false;
    document.getElementById('ldapTestPassword').value='';
    document.getElementById('ldapResult').textContent='';panel.scrollIntoView({behavior:'smooth'});
}
function ldapPayload(){
    const p={protocol:'ldap',id:document.getElementById('ldapId').value,ldap_flavour:document.getElementById('ldapPreset').value};
    LDAP_FIELDS.forEach(k=>{p[k]=document.getElementById('ldap_'+k).value;});
    p.ldap_encryption=document.getElementById('ldapTransport').value;
    p.enabled=document.getElementById('ldapEnabled').checked?1:0;
    p.auto_create_analysts=document.getElementById('ldapAutoAnalysts').checked?1:0;
    p.auto_create_users=document.getElementById('ldapAutoUsers').checked?1:0;
    return p;
}
document.getElementById('ldapPreset').addEventListener('change',function(){
    Object.entries(LDAP_PRESETS[this.value]).forEach(([k,v])=>{const el=document.getElementById('ldap_'+k);if(el)el.value=v;});
    document.getElementById('ldapTransport').value='ldaps';document.getElementById('ldap_ldap_port').value=636;
});
document.getElementById('ldapNew').addEventListener('click',()=>openLdapModal(null));
async function ldapRequest(endpoint,p){
    const result=document.getElementById('ldapResult');result.textContent='Working…';
    try{const r=await fetch(API+'system/'+endpoint,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-Token':NATIVE_LDAP_CSRF},body:JSON.stringify(p)});const d=await r.json();
        result.textContent=d.success?(d.message||'Directory saved.'):(d.error||'Request failed.');
        if(d.success&&d.id){document.getElementById('ldapId').value=d.id;loadProviders();}
        if(d.found)result.textContent+=' '+JSON.stringify(d.found);
    }catch(e){result.textContent='Request failed. Check connectivity and database migration.';}
    document.getElementById('ldap_ldap_bind_password').value='';document.getElementById('ldapTestPassword').value='';
}
document.getElementById('ldapForm').addEventListener('submit',e=>{e.preventDefault();ldapRequest('save_sso_provider.php',ldapPayload());});
document.getElementById('ldapTest').addEventListener('click',()=>{const p=ldapPayload();p.test_username=document.getElementById('ldapTestUser').value;p.test_password=document.getElementById('ldapTestPassword').value;ldapRequest('test_ldap_connection.php',p);});
</script>
