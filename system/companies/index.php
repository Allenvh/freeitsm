<?php
/**
 * System - Companies
 * List / create / edit companies (the user-facing word for tenants). On a
 * single-company install this just shows the one "Default" company.
 */
session_start();
require_once '../../config.php';
require_once '../../includes/i18n.php';
I18n::initFromSession();

$current_page = 'companies';
$path_prefix = '../../';
$translationNamespaces = ['common', 'system'];
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(I18n::getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - <?php echo htmlspecialchars(t('system.companies.title')); ?></title>
    <link rel="stylesheet" href="../../assets/css/inbox.css">
    <style>
        /* flex:1 (not a hardcoded 100vh-48px height) so a taller/wrapping header
           can't push the page off-screen — see the tickets/settings fix (#535). */
        .companies-container { flex: 1; overflow-y: auto; padding: 30px 20px; }
        .page-title { font-size: 22px; font-weight: 600; color: #333; margin: 0 0 6px 0; }
        .page-subtitle { font-size: 13px; color: #888; margin: 0 0 30px 0; }

        .settings-card { background: #fff; border-radius: 8px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); margin-bottom: 24px; }

        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.15s; }
        .btn-primary { background: #546e7a; color: #fff; }
        .btn-primary:hover { background: #455a64; }
        .btn-secondary { background: #eceff1; color: #455a64; }
        .btn-secondary:hover { background: #cfd8dc; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Companies table */
        .companies-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .add-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #546e7a; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .add-btn:hover { background: #455a64; }
        table.companies { width: 100%; border-collapse: collapse; font-size: 13px; }
        table.companies th { text-align: left; color: #888; font-weight: 600; font-size: 12px; padding: 8px 10px; border-bottom: 1px solid #eee; }
        table.companies td { padding: 10px; border-bottom: 1px solid #f2f2f2; color: #444; vertical-align: middle; }
        table.companies tr:last-child td { border-bottom: none; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .status-badge.on { background: #e8f5e9; color: #2e7d32; }
        .status-badge.off { background: #f0f0f0; color: #999; }
        .badge-default { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11px; background: #e3f2fd; color: #1565c0; margin-left: 8px; }
        .domain-chip { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11px; background: #ede7f6; color: #5e35b1; margin: 2px 4px 2px 0; }
        .domains-none { color: #bbb; }
        .table-action-btn { background: none; border: none; cursor: pointer; color: #607d8b; padding: 4px 8px; font-size: 13px; border-radius: 4px; }
        .table-action-btn:hover { background: #eceff1; }
        .empty-row td { text-align: center; color: #aaa; padding: 24px; font-style: italic; }

        /* Modal — namespaced (co-) so it doesn't inherit inbox.css's global .modal
           framework, whose .modal rule sets opacity:0/visibility:hidden by default. */
        .co-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 2100; align-items: center; justify-content: center; }
        .co-modal-overlay.open { display: flex; }
        .co-modal { background: #fff; border-radius: 10px; width: 480px; max-width: 92vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .co-modal-header { padding: 20px 24px; border-bottom: 1px solid #eee; font-size: 16px; font-weight: 600; color: #333; }
        .co-modal-body { padding: 20px 24px; }
        .co-modal-footer { padding: 16px 24px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 10px; }
        .form-field { margin-bottom: 16px; }
        .form-field label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 4px; }
        .form-field .hint { font-size: 12px; color: #999; font-weight: 400; margin-bottom: 6px; }
        .form-field input[type=text] { width: 100%; padding: 9px 11px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; font-family: inherit; box-sizing: border-box; }
        .form-field input:focus { outline: none; border-color: #546e7a; }
        .checkbox-field { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 14px; }
        .checkbox-field input { margin-top: 3px; }
        .checkbox-field .cb-label { font-size: 13px; color: #444; }
        .checkbox-field .cb-label strong { display: block; }
        .checkbox-field .cb-label span { color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="companies-container">
        <h1 class="page-title"><?php echo htmlspecialchars(t('system.companies.title')); ?></h1>
        <p class="page-subtitle"><?php echo htmlspecialchars(t('system.companies.subtitle')); ?></p>

        <div class="settings-card">
            <div class="companies-head">
                <div></div>
                <button class="add-btn" id="addCompanyBtn"><?php echo htmlspecialchars(t('system.companies.add')); ?></button>
            </div>
            <table class="companies">
                <thead>
                    <tr>
                        <th><?php echo htmlspecialchars(t('system.companies.col_name')); ?></th>
                        <th><?php echo htmlspecialchars(t('system.companies.col_domains')); ?></th>
                        <th><?php echo htmlspecialchars(t('system.companies.col_status')); ?></th>
                        <th style="text-align:right;"><?php echo htmlspecialchars(t('system.companies.col_actions')); ?></th>
                    </tr>
                </thead>
                <tbody id="companiesBody">
                    <tr class="empty-row"><td colspan="4"><?php echo htmlspecialchars(t('system.companies.loading')); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit modal -->
    <div class="co-modal-overlay" id="companyModal">
        <div class="co-modal">
            <div class="co-modal-header" id="modalTitle"><?php echo htmlspecialchars(t('system.companies.modal_add_title')); ?></div>
            <div class="co-modal-body">
                <input type="hidden" id="companyId">
                <div class="form-field">
                    <label><?php echo htmlspecialchars(t('system.companies.field_name')); ?></label>
                    <div class="hint"><?php echo htmlspecialchars(t('system.companies.field_name_hint')); ?></div>
                    <input type="text" id="fName" placeholder="<?php echo htmlspecialchars(t('system.companies.field_name_placeholder')); ?>">
                </div>
                <div class="checkbox-field">
                    <input type="checkbox" id="fActive" checked>
                    <div class="cb-label"><strong><?php echo htmlspecialchars(t('system.companies.cb_active')); ?></strong><span><?php echo htmlspecialchars(t('system.companies.cb_active_desc')); ?></span></div>
                </div>

                <!-- Email domains (shared-intake routing). Shown only when editing an
                     existing company on a multi-company install (populated by JS). -->
                <div class="form-field" id="domainsSection" style="display: none;">
                    <label><?php echo htmlspecialchars(t('system.companies.domains_label')); ?></label>
                    <div class="hint"><?php echo htmlspecialchars(t('system.companies.domains_hint')); ?></div>
                    <div id="domainsList"></div>
                    <div style="display: flex; gap: 8px; margin-top: 8px;">
                        <input type="text" id="domainInput" placeholder="<?php echo htmlspecialchars(t('system.companies.domain_placeholder')); ?>" style="flex: 1;">
                        <button type="button" class="btn btn-secondary" id="addDomainBtn"><?php echo htmlspecialchars(t('system.companies.domain_add')); ?></button>
                    </div>
                </div>
            </div>
            <div class="co-modal-footer">
                <button class="btn btn-secondary" id="cancelModalBtn" type="button"><?php echo htmlspecialchars(t('system.companies.cancel')); ?></button>
                <button class="btn btn-primary" id="saveCompanyBtn" type="button"><?php echo htmlspecialchars(t('system.companies.save')); ?></button>
            </div>
        </div>
    </div>

    <script>window.translations = <?php echo json_encode(I18n::exportForJs($translationNamespaces), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;</script>
    <script src="../../assets/js/i18n.js"></script>
    <script>
    const API = '<?php echo $path_prefix; ?>api/';
    let companies = [];

    function esc(s) { return (s ?? '').toString().replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }

    // ---------- Companies list ----------
    async function loadCompanies() {
        try {
            const r = await fetch(API + 'system/get_tenants.php');
            const d = await r.json();
            companies = d.success ? d.companies : [];
        } catch (e) { companies = []; }
        renderCompanies();
    }

    function renderDomainCell(domains) {
        if (!domains || !domains.length) {
            return '<span class="domains-none">' + esc(window.t('system.companies.domains_dash')) + '</span>';
        }
        return domains.map(d => '<span class="domain-chip">' + esc(d) + '</span>').join('');
    }

    function renderCompanies() {
        const body = document.getElementById('companiesBody');
        if (!companies.length) {
            body.innerHTML = '<tr class="empty-row"><td colspan="4">' + window.t('system.companies.no_companies', { add: '<strong>' + window.t('system.companies.add_strong') + '</strong>' }) + '</td></tr>';
            return;
        }
        body.innerHTML = companies.map(c => `
            <tr>
                <td><strong>${esc(c.name)}</strong>${c.is_default ? '<span class="badge-default">' + window.t('system.companies.default') + '</span>' : ''}</td>
                <td>${renderDomainCell(c.domains)}</td>
                <td><span class="status-badge ${c.is_active ? 'on' : 'off'}">${c.is_active ? window.t('system.companies.active') : window.t('system.companies.inactive')}</span></td>
                <td style="text-align:right;">
                    <button class="table-action-btn" data-edit="${c.id}">${window.t('system.companies.edit')}</button>
                </td>
            </tr>`).join('');
    }

    // ---------- Modal ----------
    const modal = document.getElementById('companyModal');
    function openModal(c) {
        document.getElementById('modalTitle').textContent = c ? window.t('system.companies.modal_edit_title') : window.t('system.companies.modal_add_title');
        document.getElementById('companyId').value = c ? c.id : '';
        document.getElementById('fName').value = c ? c.name : '';
        const active = document.getElementById('fActive');
        active.checked = c ? !!c.is_active : true;
        // The default company is always active and can't be deactivated.
        active.disabled = !!(c && c.is_default);

        // Email domains: only when editing an existing company on a multi-company
        // install (shared-intake routing is meaningless with a single company).
        const domainsSection = document.getElementById('domainsSection');
        document.getElementById('domainInput').value = '';
        if (c && c.id && companies.length > 1) {
            domainsSection.style.display = '';
            loadDomains(c.id);
        } else {
            domainsSection.style.display = 'none';
            document.getElementById('domainsList').innerHTML = '';
        }

        modal.classList.add('open');
        document.getElementById('fName').focus();
    }
    function closeModal() { modal.classList.remove('open'); }

    // ---------- Company email domains ----------
    let currentDomains = [];
    async function loadDomains(tenantId) {
        document.getElementById('domainsList').innerHTML = '';
        try {
            const r = await fetch(API + 'system/get_tenant_domains.php?tenant_id=' + tenantId);
            const d = await r.json();
            currentDomains = d.success ? d.domains : [];
        } catch (e) { currentDomains = []; }
        renderDomains();
    }
    function renderDomains() {
        const list = document.getElementById('domainsList');
        if (!currentDomains.length) {
            list.innerHTML = '<div style="color:#aaa; font-size:12px; font-style:italic; padding:6px 0;">' + esc(window.t('system.companies.domains_none')) + '</div>';
            return;
        }
        list.innerHTML = currentDomains.map(d => `
            <div style="display:flex; align-items:center; justify-content:space-between; padding:6px 10px; border:1px solid #eee; border-radius:5px; margin-bottom:6px; font-size:13px;">
                <span>${esc(d.domain)}</span>
                <button type="button" class="table-action-btn" data-remove-domain="${d.id}">${esc(window.t('system.companies.domain_remove'))}</button>
            </div>`).join('');
    }
    async function addDomain() {
        const tenantId = document.getElementById('companyId').value;
        const input = document.getElementById('domainInput');
        const domain = input.value.trim();
        if (!tenantId || !domain) return;
        const btn = document.getElementById('addDomainBtn');
        btn.disabled = true;
        try {
            const r = await fetch(API + 'system/add_tenant_domain.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tenant_id: tenantId, domain })
            });
            const d = await r.json();
            if (d.success) {
                input.value = '';
                showToast(window.t('system.companies.domain_added'), 'success');
                loadDomains(tenantId);
                loadCompanies(); // keep the list's domain chips in sync
            } else {
                showToast(d.error || window.t('system.companies.domain_add_failed'), 'error');
            }
        } catch (e) { showToast(window.t('system.companies.domain_add_failed'), 'error'); }
        btn.disabled = false;
    }
    async function removeDomain(id) {
        try {
            const r = await fetch(API + 'system/delete_tenant_domain.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const d = await r.json();
            if (d.success) {
                showToast(window.t('system.companies.domain_removed'), 'success');
                loadDomains(document.getElementById('companyId').value);
                loadCompanies(); // keep the list's domain chips in sync
            } else {
                showToast(d.error || window.t('system.companies.domain_remove_failed'), 'error');
            }
        } catch (e) { showToast(window.t('system.companies.domain_remove_failed'), 'error'); }
    }
    document.getElementById('addDomainBtn').addEventListener('click', addDomain);
    document.getElementById('domainInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); addDomain(); }
    });
    document.getElementById('domainsList').addEventListener('click', e => {
        const rm = e.target.getAttribute('data-remove-domain');
        if (rm) removeDomain(parseInt(rm, 10));
    });

    document.getElementById('addCompanyBtn').addEventListener('click', () => openModal(null));
    document.getElementById('cancelModalBtn').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

    document.getElementById('companiesBody').addEventListener('click', function (e) {
        const editId = e.target.getAttribute('data-edit');
        if (editId) openModal(companies.find(c => c.id == editId));
    });

    // ---------- Save company ----------
    document.getElementById('saveCompanyBtn').addEventListener('click', async function () {
        const payload = {
            id: document.getElementById('companyId').value || 0,
            name: document.getElementById('fName').value.trim(),
            is_active: document.getElementById('fActive').checked ? 1 : 0
        };
        if (!payload.name) {
            showToast(window.t('system.companies.required_name'), 'error');
            return;
        }
        this.disabled = true;
        try {
            const r = await fetch(API + 'system/save_tenant.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const d = await r.json();
            if (d.success) {
                showToast(window.t('system.companies.company_saved'), 'success');
                closeModal();
                loadCompanies();
            } else {
                showToast(window.t('system.companies.error', { error: d.error }), 'error');
            }
        } catch (e) { showToast(window.t('system.companies.save_failed'), 'error'); }
        this.disabled = false;
    });

    loadCompanies();
    </script>
</body>
</html>
