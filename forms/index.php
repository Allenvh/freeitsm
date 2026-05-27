<?php
/**
 * Forms Module - Forms list (dashboard)
 *
 * Full-width table view. Editing happens on /forms/edit/ (#437) which
 * is also where AI Assist + versioning + the field builder all live.
 * This page is purely the list / dashboard now — click a row to edit,
 * or use the row icons for Fill / Submissions / Delete.
 */
session_start();
require_once '../config.php';

$current_page = 'forms';
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Forms</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/inbox.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/forms.css?v=<?= time() ?>">
    <style>
        /* Full-width forms dashboard — matches the canonical layout
           used by other modules' settings + reporting pages. */
        .forms-list-container {
            height: calc(100vh - 48px);
            display: flex;
            flex-direction: column;
            padding: 16px 30px 0;
            background: #f5f5f5;
        }
        .forms-list-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            gap: 16px;
            flex-shrink: 0;
        }
        .forms-list-toolbar h1 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }
        .forms-list-toolbar .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .forms-list-search {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            min-width: 260px;
        }
        .forms-list-search:focus { outline: none; border-color: #00897b; }
        .new-form-btn {
            background: #00897b;
            color: white;
            padding: 9px 16px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .new-form-btn:hover { background: #00695c; color: white; }

        /* The table card itself — scrolls internally so the toolbar
           stays pinned at the top of the page. */
        .forms-table-card {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
            margin-bottom: 24px;
        }
        .forms-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .forms-table thead th {
            position: sticky;
            top: 0;
            background: #fafafa;
            text-align: left;
            font-weight: 600;
            color: #666;
            padding: 12px 14px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
        }
        .forms-table thead th:hover { background: #f0f0f0; }
        .forms-table thead th .sort-arrow {
            display: inline-block;
            margin-left: 4px;
            opacity: 0.4;
            font-size: 10px;
        }
        .forms-table thead th.sort-asc .sort-arrow,
        .forms-table thead th.sort-desc .sort-arrow { opacity: 1; }
        .forms-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        .forms-table tbody tr {
            cursor: pointer;
            transition: background 0.1s;
        }
        .forms-table tbody tr:hover { background: #f5fbfa; }

        .forms-table td.col-title strong {
            display: block;
            color: #111;
            font-size: 14px;
        }
        .forms-table td.col-title small {
            display: block;
            color: #888;
            font-size: 12px;
            margin-top: 2px;
            max-width: 480px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ft-pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        .ft-pill.active    { background: #e0f2f1; color: #00695c; }
        .ft-pill.inactive  { background: #fafafa; color: #999; }
        .ft-pill.version   { background: #00897b; color: white; font-size: 11px; }

        /* Icon-only row actions — matches the canonical settings table
           pattern from #401 / #403 / etc. */
        .forms-table td.col-actions {
            white-space: nowrap;
            width: 1%;
            text-align: right;
        }
        .ft-action-btn {
            background: none;
            border: none;
            padding: 4px;
            margin-left: 2px;
            color: #666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
        }
        .ft-action-btn:hover { background: #f0f0f0; color: #00897b; }
        .ft-action-btn.danger:hover { color: #c62828; }
        .ft-action-btn svg { width: 16px; height: 16px; }

        .forms-empty {
            padding: 60px 30px;
            text-align: center;
            color: #888;
        }
        .forms-empty svg { color: #ccc; margin-bottom: 14px; }
        .forms-empty h3 {
            color: #555;
            font-weight: 600;
            margin: 0 0 6px;
            font-size: 16px;
        }
        .forms-empty p {
            margin: 0 0 16px;
            font-size: 13px;
        }

        /* Delete confirmation overlay */
        .confirm-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .confirm-overlay.open { display: flex; }
        .confirm-box {
            background: white;
            border-radius: 8px;
            padding: 24px 26px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        }
        .confirm-box h3 { margin: 0 0 8px; font-size: 17px; color: #333; }
        .confirm-box p { margin: 0 0 18px; color: #666; font-size: 14px; line-height: 1.5; }
        .confirm-actions { display: flex; justify-content: flex-end; gap: 10px; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="forms-list-container">
        <div class="forms-list-toolbar">
            <h1>Forms</h1>
            <div class="toolbar-actions">
                <input type="text" id="formSearch" class="forms-list-search" placeholder="Search by title or description..." oninput="filterForms()">
                <a href="<?php echo BASE_URL; ?>forms/edit/" class="new-form-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    New form
                </a>
            </div>
        </div>

        <div class="forms-table-card">
            <table class="forms-table" id="formsTable">
                <thead>
                    <tr>
                        <th data-sort="title">Title <span class="sort-arrow">&#9650;&#9660;</span></th>
                        <th data-sort="version" style="width: 80px;">Version <span class="sort-arrow">&#9650;&#9660;</span></th>
                        <th data-sort="status" style="width: 100px;">Status <span class="sort-arrow">&#9650;&#9660;</span></th>
                        <th data-sort="fields" style="width: 80px; text-align: right;">Fields <span class="sort-arrow">&#9650;&#9660;</span></th>
                        <th data-sort="submissions" style="width: 110px; text-align: right;">Submissions <span class="sort-arrow">&#9650;&#9660;</span></th>
                        <th data-sort="modified" style="width: 200px;">Last modified <span class="sort-arrow">&#9650;&#9660;</span></th>
                        <th>Modified by</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="formsTableBody">
                    <tr><td colspan="8" class="forms-empty">Loading forms&hellip;</td></tr>
                </tbody>
            </table>
        </div>
    </div>


    <script>
        const API_BASE = '<?php echo BASE_URL; ?>api/forms/';
        const EDIT_BASE = '<?php echo BASE_URL; ?>forms/edit/';

        let allForms = [];
        let filteredForms = [];
        let sortKey = 'modified';
        let sortDir = 'desc';   // start with newest-modified at the top

        // SVG icons used in the action column
        const ICON_FILL    = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';
        const ICON_SUBS    = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
        const ICON_DELETE  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';

        document.addEventListener('DOMContentLoaded', function() {
            loadForms();

            // Wire column sorting
            document.querySelectorAll('.forms-table thead th[data-sort]').forEach(th => {
                th.addEventListener('click', () => {
                    const key = th.dataset.sort;
                    if (sortKey === key) {
                        sortDir = sortDir === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortKey = key;
                        sortDir = 'asc';
                    }
                    render();
                });
            });
        });

        async function loadForms() {
            try {
                const res = await fetch(API_BASE + 'get_forms.php');
                const data = await res.json();
                if (data.success) {
                    allForms = data.forms || [];
                    filteredForms = allForms.slice();
                    render();
                } else {
                    document.getElementById('formsTableBody').innerHTML =
                        '<tr><td colspan="8" class="forms-empty">Error loading forms</td></tr>';
                }
            } catch (e) {
                document.getElementById('formsTableBody').innerHTML =
                    '<tr><td colspan="8" class="forms-empty">Error loading forms</td></tr>';
            }
        }

        function filterForms() {
            const q = document.getElementById('formSearch').value.trim().toLowerCase();
            if (!q) {
                filteredForms = allForms.slice();
            } else {
                filteredForms = allForms.filter(f =>
                    (f.title || '').toLowerCase().includes(q) ||
                    (f.description || '').toLowerCase().includes(q)
                );
            }
            render();
        }

        function render() {
            // Update sort indicator on the right column header
            document.querySelectorAll('.forms-table thead th').forEach(th => {
                th.classList.remove('sort-asc', 'sort-desc');
                if (th.dataset.sort === sortKey) {
                    th.classList.add(sortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                    const arrow = th.querySelector('.sort-arrow');
                    if (arrow) arrow.textContent = sortDir === 'asc' ? '▲' : '▼';
                } else {
                    const arrow = th.querySelector('.sort-arrow');
                    if (arrow) arrow.textContent = '▲▼';
                }
            });

            // Sort
            const sorted = filteredForms.slice().sort((a, b) => {
                const va = sortValue(a, sortKey);
                const vb = sortValue(b, sortKey);
                if (va === vb) return 0;
                const cmp = va > vb ? 1 : -1;
                return sortDir === 'asc' ? cmp : -cmp;
            });

            const tbody = document.getElementById('formsTableBody');
            if (sorted.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8"><div class="forms-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <h3>${allForms.length === 0 ? 'No forms yet' : 'No matching forms'}</h3>
                    <p>${allForms.length === 0
                        ? 'Click <strong>New form</strong> to create your first one.'
                        : 'Try a different search term, or clear the search box.'}</p>
                </div></td></tr>`;
                return;
            }

            tbody.innerHTML = sorted.map(f => {
                const desc = f.description ? `<small>${esc(f.description)}</small>` : '';
                const statusPill = f.is_active == 1
                    ? '<span class="ft-pill active">Active</span>'
                    : '<span class="ft-pill inactive">Inactive</span>';
                return `<tr onclick="openEdit(${f.id})">
                    <td class="col-title">
                        <strong>${esc(f.title)}</strong>
                        ${desc}
                    </td>
                    <td><span class="ft-pill version">v${f.version_number || 1}</span></td>
                    <td>${statusPill}</td>
                    <td style="text-align: right;">${f.field_count}</td>
                    <td style="text-align: right;">${f.submission_count}</td>
                    <td title="${esc(f.modified_date || '')}">${esc(relativeDate(f.modified_date))}</td>
                    <td>${esc(f.modified_by_name || f.created_by_name || '—')}</td>
                    <td class="col-actions" onclick="event.stopPropagation()">
                        <a class="ft-action-btn" href="<?php echo BASE_URL; ?>forms/fill.php?id=${f.id}" title="Fill in this form">${ICON_FILL}</a>
                        <a class="ft-action-btn" href="<?php echo BASE_URL; ?>forms/submissions.php?id=${f.id}" title="View submissions">${ICON_SUBS}</a>
                        <button class="ft-action-btn danger" onclick="confirmDelete(${f.id})" title="Delete form">${ICON_DELETE}</button>
                    </td>
                </tr>`;
            }).join('');
        }

        // Pick the value used for sorting. Strings normalised to
        // lowercase; counts and dates come back numeric/comparable.
        function sortValue(f, key) {
            switch (key) {
                case 'title':       return (f.title || '').toLowerCase();
                case 'version':     return Number(f.version_number) || 0;
                case 'status':      return f.is_active == 1 ? 1 : 0;
                case 'fields':      return Number(f.field_count) || 0;
                case 'submissions': return Number(f.submission_count) || 0;
                case 'modified':    return f.modified_date || '';
                default:            return '';
            }
        }

        function openEdit(id) {
            window.location.href = EDIT_BASE + '?id=' + id;
        }

        // Friendly "5 minutes ago" / "2 days ago" — falls back to the
        // ISO date once it's older than a week so dates stay readable.
        function relativeDate(iso) {
            if (!iso) return '';
            const d = new Date(iso.replace(' ', 'T') + 'Z');
            if (isNaN(d.getTime())) return iso;
            const now = new Date();
            const secs = Math.floor((now - d) / 1000);
            if (secs < 60)        return 'Just now';
            if (secs < 3600)      return Math.floor(secs / 60) + ' min ago';
            if (secs < 86400)     return Math.floor(secs / 3600) + ' hr ago';
            if (secs < 604800)    return Math.floor(secs / 86400) + ' days ago';
            return d.toLocaleDateString();
        }

        function esc(text) {
            if (text == null) return '';
            const div = document.createElement('div');
            div.textContent = String(text);
            return div.innerHTML;
        }

        // ===== Delete =====
        async function confirmDelete(id) {
            const ok = await showConfirm({
                title: 'Delete form',
                message: "This will permanently delete this form, every version in its chain, and all submissions across all versions. This can't be undone.",
                okLabel: 'Delete',
                okClass: 'danger'
            });
            if (!ok) return;
            try {
                const res = await fetch(API_BASE + 'delete_form.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const data = await res.json();
                if (data.success) {
                    await loadForms();
                    showToast('Form deleted', 'success');
                } else {
                    showToast(data.error || 'Failed to delete', 'error');
                }
            } catch (e) {
                showToast('Failed to delete', 'error');
            }
        }
    </script>
</body>
</html>
