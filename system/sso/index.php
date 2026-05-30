<?php
/**
 * System - Single Sign-On (SSO / OIDC)
 * Configure OpenID Connect identity providers + global SSO switches.
 */
session_start();
require_once '../../config.php';

$current_page = 'sso';
$path_prefix = '../../';

// The redirect URI the admin must register in their IdP. Built from the
// deployment's BASE_URL so it's correct whatever path the app is served at.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$redirectUri = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . BASE_URL . 'api/auth/oidc_callback.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Single Sign-On</title>
    <link rel="stylesheet" href="../../assets/css/inbox.css">
    <style>
        .sso-container { height: calc(100vh - 48px); overflow-y: auto; padding: 30px 20px; }
        .page-title { font-size: 22px; font-weight: 600; color: #333; margin: 0 0 6px 0; }
        .page-subtitle { font-size: 13px; color: #888; margin: 0 0 30px 0; }

        .settings-card { background: #fff; border-radius: 8px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); margin-bottom: 24px; }
        .settings-card h3 { font-size: 15px; font-weight: 600; color: #333; margin: 0 0 4px 0; }
        .settings-card .card-desc { font-size: 13px; color: #888; margin: 0 0 20px 0; line-height: 1.5; }

        .setting-row { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
        .setting-row:last-child { margin-bottom: 0; }
        .setting-label { flex: 1; font-size: 13px; color: #555; }
        .setting-label strong { display: block; color: #333; margin-bottom: 2px; }

        /* Toggle switch */
        .switch { position: relative; display: inline-block; width: 44px; height: 24px; flex: none; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .switch .slider { position: absolute; cursor: pointer; inset: 0; background: #ccc; border-radius: 24px; transition: .2s; }
        .switch .slider:before { content: ""; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .2s; }
        .switch input:checked + .slider { background: #546e7a; }
        .switch input:checked + .slider:before { transform: translateX(20px); }

        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.15s; }
        .btn-primary { background: #546e7a; color: #fff; }
        .btn-primary:hover { background: #455a64; }
        .btn-secondary { background: #eceff1; color: #455a64; }
        .btn-secondary:hover { background: #cfd8dc; }
        .btn-test { background: #fff; color: #546e7a; border: 1px solid #cfd8dc; }
        .btn-test:hover { background: #f5f7fa; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .save-area { margin-top: 24px; }

        .info-note { background: #f5f7fa; border: 1px solid #e0e0e0; border-radius: 6px; padding: 14px 16px; font-size: 12px; color: #666; line-height: 1.6; }
        .info-note strong { color: #333; }
        .redirect-uri-box { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .redirect-uri-box code { flex: 1; background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 8px 10px; font-size: 12px; color: #333; overflow-x: auto; white-space: nowrap; }

        /* Providers table */
        .providers-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .add-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #546e7a; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .add-btn:hover { background: #455a64; }
        table.providers { width: 100%; border-collapse: collapse; font-size: 13px; }
        table.providers th { text-align: left; color: #888; font-weight: 600; font-size: 12px; padding: 8px 10px; border-bottom: 1px solid #eee; }
        table.providers td { padding: 10px; border-bottom: 1px solid #f2f2f2; color: #444; vertical-align: middle; }
        table.providers tr:last-child td { border-bottom: none; }
        .issuer-cell { color: #888; font-size: 12px; max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .status-badge.on { background: #e8f5e9; color: #2e7d32; }
        .status-badge.off { background: #f0f0f0; color: #999; }
        .badge-jit { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11px; background: #e3f2fd; color: #1565c0; }
        .table-action-btn { background: none; border: none; cursor: pointer; color: #607d8b; padding: 4px 8px; font-size: 13px; border-radius: 4px; }
        .table-action-btn:hover { background: #eceff1; }
        .table-action-btn.danger:hover { background: #ffebee; color: #c62828; }
        .empty-row td { text-align: center; color: #aaa; padding: 24px; font-style: italic; }

        /* Modal — namespaced (sso-) so it doesn't inherit inbox.css's global .modal
           framework, whose .modal rule sets opacity:0/visibility:hidden by default. */
        .sso-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 2100; align-items: center; justify-content: center; }
        .sso-modal-overlay.open { display: flex; }
        .sso-modal { background: #fff; border-radius: 10px; width: 560px; max-width: 92vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .sso-modal-header { padding: 20px 24px; border-bottom: 1px solid #eee; font-size: 16px; font-weight: 600; color: #333; }
        .sso-modal-body { padding: 20px 24px; }
        .sso-modal-footer { padding: 16px 24px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 10px; }
        .form-field { margin-bottom: 16px; }
        .form-field label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 4px; }
        .form-field .hint { font-size: 12px; color: #999; font-weight: 400; margin-bottom: 6px; }
        .form-field input[type=text], .form-field input[type=password] { width: 100%; padding: 9px 11px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; font-family: inherit; box-sizing: border-box; }
        .form-field input:focus { outline: none; border-color: #546e7a; }
        .issuer-row { display: flex; gap: 8px; }
        .issuer-row input { flex: 1; }
        .checkbox-field { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 14px; }
        .checkbox-field input { margin-top: 3px; }
        .checkbox-field .cb-label { font-size: 13px; color: #444; }
        .checkbox-field .cb-label strong { display: block; }
        .checkbox-field .cb-label span { color: #999; font-size: 12px; }
        .test-result { margin-top: 6px; font-size: 12px; padding: 8px 10px; border-radius: 5px; display: none; }
        .test-result.ok { display: block; background: #e8f5e9; color: #2e7d32; }
        .test-result.err { display: block; background: #ffebee; color: #c62828; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="sso-container">
        <h1 class="page-title">Single sign-on</h1>
        <p class="page-subtitle">Let users sign in through an external identity provider (OpenID Connect) such as Keycloak, Microsoft Entra, Okta or Google — alongside local accounts.</p>

        <!-- Global switches -->
        <div class="settings-card">
            <h3>Global settings</h3>
            <p class="card-desc">Master controls for sign-on across the whole system.</p>
            <div class="setting-row">
                <div class="setting-label">
                    <strong>Enable single sign-on</strong>
                    Show the configured provider buttons on the login page. Turn off to instantly fall back to local logins everywhere (break-glass).
                </div>
                <label class="switch"><input type="checkbox" id="ssoEnabled"><span class="slider"></span></label>
            </div>
            <div class="setting-row">
                <div class="setting-label">
                    <strong>Allow local login</strong>
                    Keep the username + password form available. Leave on so a misconfigured or down provider can never lock everyone out.
                </div>
                <label class="switch"><input type="checkbox" id="localLoginEnabled"><span class="slider"></span></label>
            </div>
            <div class="save-area"><button class="btn btn-primary" id="saveGlobalBtn">Save</button></div>
        </div>

        <!-- Redirect URI -->
        <div class="settings-card">
            <h3>Redirect URI</h3>
            <p class="card-desc">Register this exact URL in each identity provider as an allowed redirect / callback URL. It's where the provider sends users back after they sign in.</p>
            <div class="redirect-uri-box">
                <code id="redirectUri"><?php echo htmlspecialchars($redirectUri); ?></code>
                <button class="btn btn-secondary" id="copyRedirectBtn">Copy</button>
            </div>
        </div>

        <!-- Providers -->
        <div class="settings-card">
            <div class="providers-head">
                <div>
                    <h3 style="margin:0;">Identity providers</h3>
                    <p class="card-desc" style="margin:4px 0 0;">Each provider is a separate IdP. Assign different users to different providers to run pilots in parallel.</p>
                </div>
                <button class="add-btn" id="addProviderBtn">+ Add</button>
            </div>
            <table class="providers">
                <thead>
                    <tr><th>Name</th><th>Issuer</th><th>Status</th><th>Auto-create</th><th style="text-align:right;">Actions</th></tr>
                </thead>
                <tbody id="providersBody">
                    <tr class="empty-row"><td colspan="5">Loading…</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit modal -->
    <div class="sso-modal-overlay" id="providerModal">
        <div class="sso-modal">
            <div class="sso-modal-header" id="modalTitle">Add provider</div>
            <div class="sso-modal-body">
                <input type="hidden" id="providerId">
                <div class="form-field">
                    <label>Display name</label>
                    <div class="hint">Shown on the login button, e.g. "Sign in with Keycloak"</div>
                    <input type="text" id="fDisplayName" placeholder="Sign in with Keycloak">
                </div>
                <div class="form-field">
                    <label>Issuer URL</label>
                    <div class="hint">The provider's base URL. e.g. http://localhost:8080/realms/freeitsm</div>
                    <div class="issuer-row">
                        <input type="text" id="fIssuerUrl" placeholder="https://your-idp/realms/your-realm">
                        <button class="btn btn-test" id="testDiscoveryBtn" type="button">Test</button>
                    </div>
                    <div class="test-result" id="testResult"></div>
                </div>
                <div class="form-field">
                    <label>Client ID</label>
                    <div class="hint">The client/app identifier created in the provider, e.g. freeitsm-app</div>
                    <input type="text" id="fClientId" placeholder="freeitsm-app">
                </div>
                <div class="form-field">
                    <label>Client secret</label>
                    <div class="hint" id="secretHint">The client's secret from the provider. Stored encrypted.</div>
                    <input type="password" id="fClientSecret" placeholder="" autocomplete="new-password">
                </div>
                <div class="form-field">
                    <label>Scopes</label>
                    <div class="hint">Space-separated OIDC scopes. Leave as default unless your provider needs more.</div>
                    <input type="text" id="fScopes" value="openid email profile">
                </div>
                <div class="checkbox-field">
                    <input type="checkbox" id="fEnabled" checked>
                    <div class="cb-label"><strong>Enabled</strong><span>Show this provider's button on the login page</span></div>
                </div>
                <div class="checkbox-field">
                    <input type="checkbox" id="fAutoCreate">
                    <div class="cb-label"><strong>Auto-create users on first login (JIT)</strong><span>Create an analyst automatically the first time someone signs in via this provider. Leave off for tightly controlled pilots where only pre-created users may enter.</span></div>
                </div>
            </div>
            <div class="sso-modal-footer">
                <button class="btn btn-secondary" id="cancelModalBtn" type="button">Cancel</button>
                <button class="btn btn-primary" id="saveProviderBtn" type="button">Save</button>
            </div>
        </div>
    </div>

    <script>
    const API = '<?php echo $path_prefix; ?>api/';
    let providers = [];

    // ---------- Global switches ----------
    async function loadGlobal() {
        try {
            const r = await fetch(API + 'settings/get_system_settings.php');
            const d = await r.json();
            if (d.success) {
                document.getElementById('ssoEnabled').checked = d.settings.sso_enabled === '1';
                document.getElementById('localLoginEnabled').checked = d.settings.local_login_enabled !== '0';
            }
        } catch (e) { console.error(e); }
    }
    document.getElementById('saveGlobalBtn').addEventListener('click', async function () {
        this.disabled = true;
        const settings = {
            sso_enabled: document.getElementById('ssoEnabled').checked ? '1' : '0',
            local_login_enabled: document.getElementById('localLoginEnabled').checked ? '1' : '0'
        };
        try {
            const r = await fetch(API + 'settings/save_system_settings.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ settings })
            });
            const d = await r.json();
            showToast(d.success ? 'Global settings saved' : ('Error: ' + d.error), d.success ? 'success' : 'error');
        } catch (e) { showToast('Failed to save', 'error'); }
        this.disabled = false;
    });

    // ---------- Redirect URI copy ----------
    document.getElementById('copyRedirectBtn').addEventListener('click', function () {
        const txt = document.getElementById('redirectUri').textContent;
        navigator.clipboard.writeText(txt).then(() => showToast('Redirect URI copied', 'success'));
    });

    // ---------- Providers list ----------
    function esc(s) { return (s ?? '').toString().replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }

    async function loadProviders() {
        try {
            const r = await fetch(API + 'system/get_sso_providers.php');
            const d = await r.json();
            providers = d.success ? d.providers : [];
        } catch (e) { providers = []; }
        renderProviders();
    }

    function renderProviders() {
        const body = document.getElementById('providersBody');
        if (!providers.length) {
            body.innerHTML = '<tr class="empty-row"><td colspan="5">No providers yet. Click <strong>Add</strong> to configure one.</td></tr>';
            return;
        }
        body.innerHTML = providers.map(p => `
            <tr>
                <td><strong>${esc(p.display_name)}</strong></td>
                <td class="issuer-cell" title="${esc(p.issuer_url)}">${esc(p.issuer_url)}</td>
                <td><span class="status-badge ${p.enabled ? 'on' : 'off'}">${p.enabled ? 'Enabled' : 'Disabled'}</span></td>
                <td>${p.auto_create_users ? '<span class="badge-jit">JIT on</span>' : '<span style="color:#bbb;">Off</span>'}</td>
                <td style="text-align:right;">
                    <button class="table-action-btn" data-edit="${p.id}">Edit</button>
                    <button class="table-action-btn danger" data-del="${p.id}">Delete</button>
                </td>
            </tr>`).join('');
    }

    // ---------- Modal ----------
    const modal = document.getElementById('providerModal');
    function openModal(p) {
        document.getElementById('testResult').className = 'test-result';
        document.getElementById('modalTitle').textContent = p ? 'Edit provider' : 'Add provider';
        document.getElementById('providerId').value = p ? p.id : '';
        document.getElementById('fDisplayName').value = p ? p.display_name : '';
        document.getElementById('fIssuerUrl').value = p ? p.issuer_url : '';
        document.getElementById('fClientId').value = p ? p.client_id : '';
        document.getElementById('fScopes').value = p ? (p.scopes || 'openid email profile') : 'openid email profile';
        document.getElementById('fEnabled').checked = p ? !!p.enabled : true;
        document.getElementById('fAutoCreate').checked = p ? !!p.auto_create_users : false;
        const secret = document.getElementById('fClientSecret');
        secret.value = '';
        if (p && p.has_secret) {
            secret.placeholder = '•••••••• (leave blank to keep current)';
            document.getElementById('secretHint').textContent = 'A secret is already stored. Leave blank to keep it, or type a new one to replace it.';
        } else {
            secret.placeholder = '';
            document.getElementById('secretHint').textContent = "The client's secret from the provider. Stored encrypted.";
        }
        modal.classList.add('open');
    }
    function closeModal() { modal.classList.remove('open'); }

    document.getElementById('addProviderBtn').addEventListener('click', () => openModal(null));
    document.getElementById('cancelModalBtn').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

    document.getElementById('providersBody').addEventListener('click', function (e) {
        const editId = e.target.getAttribute('data-edit');
        const delId = e.target.getAttribute('data-del');
        if (editId) openModal(providers.find(p => p.id == editId));
        if (delId) deleteProvider(delId);
    });

    // ---------- Test discovery ----------
    document.getElementById('testDiscoveryBtn').addEventListener('click', async function () {
        const issuer = document.getElementById('fIssuerUrl').value.trim();
        const box = document.getElementById('testResult');
        if (!issuer) { box.className = 'test-result err'; box.textContent = 'Enter an issuer URL first.'; return; }
        this.disabled = true; box.className = 'test-result'; box.textContent = '';
        try {
            const r = await fetch(API + 'system/test_oidc_discovery.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ issuer_url: issuer })
            });
            const d = await r.json();
            if (d.success) {
                box.className = 'test-result ok';
                box.textContent = '✓ Discovery OK — issuer: ' + d.issuer;
            } else {
                box.className = 'test-result err';
                box.textContent = '✗ ' + d.error;
            }
        } catch (e) {
            box.className = 'test-result err'; box.textContent = '✗ Request failed';
        }
        this.disabled = false;
    });

    // ---------- Save provider ----------
    document.getElementById('saveProviderBtn').addEventListener('click', async function () {
        const payload = {
            id: document.getElementById('providerId').value || 0,
            display_name: document.getElementById('fDisplayName').value.trim(),
            issuer_url: document.getElementById('fIssuerUrl').value.trim(),
            client_id: document.getElementById('fClientId').value.trim(),
            client_secret: document.getElementById('fClientSecret').value,
            scopes: document.getElementById('fScopes').value.trim(),
            enabled: document.getElementById('fEnabled').checked ? 1 : 0,
            auto_create_users: document.getElementById('fAutoCreate').checked ? 1 : 0
        };
        if (!payload.display_name || !payload.issuer_url || !payload.client_id) {
            showToast('Display name, issuer URL and client ID are required', 'error');
            return;
        }
        this.disabled = true;
        try {
            const r = await fetch(API + 'system/save_sso_provider.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const d = await r.json();
            if (d.success) {
                showToast('Provider saved', 'success');
                closeModal();
                loadProviders();
            } else {
                showToast('Error: ' + d.error, 'error');
            }
        } catch (e) { showToast('Failed to save', 'error'); }
        this.disabled = false;
    });

    // ---------- Delete provider ----------
    async function deleteProvider(id) {
        const p = providers.find(x => x.id == id);
        const msg = `Delete "${p ? p.display_name : 'this provider'}"? Users assigned to it will revert to local login.`;
        const ok = window.showConfirm ? await showConfirm(msg) : confirm(msg);
        if (!ok) return;
        try {
            const r = await fetch(API + 'system/delete_sso_provider.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const d = await r.json();
            if (d.success) { showToast('Provider deleted', 'success'); loadProviders(); }
            else showToast('Error: ' + d.error, 'error');
        } catch (e) { showToast('Failed to delete', 'error'); }
    }

    loadGlobal();
    loadProviders();
    </script>
</body>
</html>
