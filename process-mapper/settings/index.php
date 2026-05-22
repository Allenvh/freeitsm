<?php
/**
 * Process Mapper — Settings
 *
 * Manages the `process_step_types` palette (name + shape + colour). The
 * editor pulls this same list at page-load time so the toolbar, the detail-
 * panel Type dropdown and the right-click "Create new" submenu all reflect
 * whatever the user has configured here. Built-in types are protected from
 * deletion; deactivating a type hides it from the toolbar/context menu but
 * keeps it in the detail-panel dropdown (marked with a `*`) so existing
 * steps with that stored type are still selectable.
 */
session_start();
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';
I18n::initFromSession();

$current_page = 'settings';
$path_prefix  = '../../';

$translationNamespaces = ['common', 'process-mapper'];
$shapes = include '../includes/shapes.php';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(I18n::getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('process-mapper.title') . ' — ' . t('process-mapper.nav.settings')); ?></title>
    <link rel="stylesheet" href="../../assets/css/inbox.css">
    <link rel="stylesheet" href="../../assets/css/process-mapper.css?v=3">
    <script>window.translations = <?php echo json_encode(I18n::exportForJs($translationNamespaces), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;</script>
    <script src="../../assets/js/i18n.js"></script>
    <style>
        body { background: #f5f5f5; }
        .pms-wrap {
            max-width: 880px;
            margin: 0 auto;
            padding: 28px 24px 48px;
        }
        .pms-head h2 {
            margin: 0 0 6px;
            font-size: 20px;
            color: #333;
        }
        .pms-head p {
            margin: 0 0 20px;
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }
        .pms-toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .pms-btn {
            background: #f5f5f5;
            color: #333;
            border: 1px solid #d4d4d4;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s;
        }
        .pms-btn:hover { background: #ececec; }
        .pms-btn-primary {
            background: #4f46e5;
            color: #fff;
            border-color: #4f46e5;
        }
        .pms-btn-primary:hover { background: #4338ca; }

        .pms-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .pms-table th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            padding: 10px 14px;
            background: #fafafa;
            border-bottom: 1px solid #e8e8e8;
        }
        .pms-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            color: #444;
            vertical-align: middle;
        }
        .pms-table tr:last-child td { border-bottom: none; }
        .pms-name { font-weight: 600; color: #333; }
        .pms-col-order { width: 70px; }
        .pms-col-actions { width: 140px; text-align: right; }

        .pms-swatch {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 3px;
            border: 1px solid rgba(0,0,0,0.15);
            margin-right: 6px;
            vertical-align: -2px;
        }
        .pms-badge {
            display: inline-block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #eef2ff;
            color: #4338ca;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 6px;
            vertical-align: 1px;
        }
        .pms-yes { color: #2e7d32; font-weight: 600; }
        .pms-no  { color: #999; }

        .pms-iconbtn {
            background: none;
            border: 1px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            color: #555;
            padding: 3px 7px;
            line-height: 1;
        }
        .pms-iconbtn:hover { background: #f0f0f0; border-color: #ddd; }
        .pms-iconbtn:disabled { opacity: 0.3; cursor: default; }
        .pms-iconbtn:disabled:hover { background: none; border-color: transparent; }
        .pms-link {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 13px;
            color: #4f46e5;
            padding: 3px 6px;
        }
        .pms-link:hover { text-decoration: underline; }
        .pms-link.pms-danger { color: #c62828; }
        .pms-link:disabled { color: #bbb; cursor: default; text-decoration: none; }
        .pms-empty { text-align: center; color: #999; padding: 28px; }

        /* Modal */
        .pms-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .pms-modal-overlay.open { display: flex; }
        .pms-modal {
            background: #fff;
            border-radius: 10px;
            width: 90%;
            max-width: 480px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        }
        .pms-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-bottom: 1px solid #eee;
            background: #fafafa;
        }
        .pms-modal-header h3 { margin: 0; font-size: 16px; color: #333; }
        .pms-modal-close {
            background: none;
            border: none;
            font-size: 22px;
            line-height: 1;
            color: #888;
            cursor: pointer;
        }
        .pms-modal-close:hover { color: #333; }
        .pms-modal-body { padding: 18px; overflow-y: auto; }
        .pms-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 12px 18px;
            border-top: 1px solid #eee;
        }
        .pms-field { margin-bottom: 16px; }
        .pms-field > label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }
        .pms-field input[type="text"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 13px;
            box-sizing: border-box;
        }
        .pms-field input[type="text"]:focus { outline: none; border-color: #4f46e5; }
        .pms-field input[type="color"] {
            width: 56px;
            height: 34px;
            padding: 2px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .pms-field-inline label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #444;
            cursor: pointer;
        }

        /* Shape picker grid */
        .pms-shape-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        .pms-shape-opt {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 10px 4px 7px;
            background: #fafafa;
            border: 2px solid #eee;
            border-radius: 8px;
            cursor: pointer;
        }
        .pms-shape-opt:hover { border-color: #c7d2fe; }
        .pms-shape-opt.selected {
            border-color: #4f46e5;
            background: #eef2ff;
        }
        .pms-shape-name {
            font-size: 11px;
            color: #666;
            text-align: center;
        }
        .pms-shape-opt.selected .pms-shape-name { color: #4338ca; font-weight: 600; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="pms-wrap">
        <div class="pms-head">
            <h2><?php echo htmlspecialchars(t('process-mapper.settings.title')); ?></h2>
            <p><?php echo htmlspecialchars(t('process-mapper.settings.intro')); ?></p>
        </div>

        <div class="pms-toolbar">
            <button class="pms-btn pms-btn-primary" onclick="PMS.openAdd()"><?php echo htmlspecialchars(t('process-mapper.settings.add_type')); ?></button>
        </div>

        <table class="pms-table">
            <thead>
                <tr>
                    <th class="pms-col-order"><?php echo htmlspecialchars(t('process-mapper.settings.col_order')); ?></th>
                    <th><?php echo htmlspecialchars(t('process-mapper.settings.col_shape')); ?></th>
                    <th><?php echo htmlspecialchars(t('process-mapper.settings.col_name')); ?></th>
                    <th><?php echo htmlspecialchars(t('process-mapper.settings.col_colour')); ?></th>
                    <th><?php echo htmlspecialchars(t('process-mapper.settings.col_active')); ?></th>
                    <th class="pms-col-actions"><?php echo htmlspecialchars(t('process-mapper.settings.col_actions')); ?></th>
                </tr>
            </thead>
            <tbody id="pmsRows">
                <tr><td colspan="6" class="pms-empty">…</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Add / Edit modal -->
    <div class="pms-modal-overlay" id="pmsModal" onclick="if (event.target === this) PMS.closeModal()">
        <div class="pms-modal">
            <div class="pms-modal-header">
                <h3 id="pmsModalTitle"></h3>
                <button class="pms-modal-close" onclick="PMS.closeModal()" title="<?php echo htmlspecialchars(t('common.close')); ?>">&times;</button>
            </div>
            <div class="pms-modal-body">
                <div class="pms-field">
                    <label for="pmsName"><?php echo htmlspecialchars(t('process-mapper.settings.field_name')); ?></label>
                    <input type="text" id="pmsName" maxlength="100" placeholder="<?php echo htmlspecialchars(t('process-mapper.settings.name_placeholder')); ?>">
                </div>
                <div class="pms-field">
                    <label><?php echo htmlspecialchars(t('process-mapper.settings.field_shape')); ?></label>
                    <div class="pms-shape-grid" id="pmsShapeGrid">
                        <?php foreach ($shapes as $key => $dim): ?>
                        <button type="button" class="pms-shape-opt" data-shape-key="<?php echo htmlspecialchars($key); ?>" onclick="PMS.pickShape('<?php echo htmlspecialchars($key); ?>')">
                            <span class="pm-shape-preview" data-shape="<?php echo htmlspecialchars($key); ?>"></span>
                            <span class="pms-shape-name"><?php echo htmlspecialchars(t('process-mapper.shapes.' . $key)); ?></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="pms-field">
                    <label for="pmsColor"><?php echo htmlspecialchars(t('process-mapper.settings.field_colour')); ?></label>
                    <input type="color" id="pmsColor" value="#0078d4">
                </div>
                <div class="pms-field pms-field-inline">
                    <label><input type="checkbox" id="pmsActive" checked> <?php echo htmlspecialchars(t('process-mapper.settings.field_active')); ?></label>
                </div>
            </div>
            <div class="pms-modal-footer">
                <button class="pms-btn" onclick="PMS.closeModal()"><?php echo htmlspecialchars(t('common.cancel')); ?></button>
                <button class="pms-btn pms-btn-primary" onclick="PMS.save()"><?php echo htmlspecialchars(t('common.save')); ?></button>
            </div>
        </div>
    </div>

    <!-- Cloud shape clip-path (objectBoundingBox so it scales with the element) -->
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <defs>
            <clipPath id="pmShapeCloud" clipPathUnits="objectBoundingBox">
                <ellipse cx="0.30" cy="0.62" rx="0.27" ry="0.33"/>
                <ellipse cx="0.50" cy="0.42" rx="0.30" ry="0.40"/>
                <ellipse cx="0.70" cy="0.60" rx="0.28" ry="0.34"/>
                <ellipse cx="0.50" cy="0.73" rx="0.40" ry="0.26"/>
            </clipPath>
        </defs>
    </svg>

    <div class="toast" id="toast"></div>

    <script>
    const PMS = (() => {
        const API = '../../api/process-mapper/';
        const SHAPE_KEYS = <?php echo json_encode(array_keys($shapes)); ?>;
        let types = [];
        let editingId = null;
        let pickedShape = 'rounded';

        function esc(s) {
            const d = document.createElement('div');
            d.textContent = s == null ? '' : String(s);
            return d.innerHTML;
        }

        function toast(msg, type = 'success') {
            const el = document.getElementById('toast');
            el.textContent = msg;
            el.className = 'toast show ' + type;
            setTimeout(() => { el.className = 'toast'; }, 2500);
        }

        async function loadTypes() {
            try {
                const r = await fetch(API + 'get_step_types.php', { credentials: 'same-origin' });
                const d = await r.json();
                if (d.success) { types = d.types || []; render(); }
                else toast(d.error || 'Failed to load', 'error');
            } catch (e) { toast('Failed to load', 'error'); }
        }

        function render() {
            const tb = document.getElementById('pmsRows');
            if (!types.length) {
                tb.innerHTML = '<tr><td colspan="6" class="pms-empty">' + esc(window.t('process-mapper.settings.none')) + '</td></tr>';
                return;
            }
            tb.innerHTML = types.map((t, i) => {
                const builtin = t.is_builtin ? ' <span class="pms-badge">' + esc(window.t('process-mapper.settings.builtin')) + '</span>' : '';
                const active = t.is_active
                    ? '<span class="pms-yes">' + esc(window.t('process-mapper.settings.yes')) + '</span>'
                    : '<span class="pms-no">' + esc(window.t('process-mapper.settings.no')) + '</span>';
                return `<tr>
                    <td class="pms-col-order">
                        <button class="pms-iconbtn" ${i === 0 ? 'disabled' : ''} onclick="PMS.move(${t.id},-1)" title="&#9650;">&#9650;</button>
                        <button class="pms-iconbtn" ${i === types.length - 1 ? 'disabled' : ''} onclick="PMS.move(${t.id},1)" title="&#9660;">&#9660;</button>
                    </td>
                    <td><span class="pm-shape-preview" data-shape="${esc(t.shape)}" style="background:${esc(t.color)}"></span></td>
                    <td class="pms-name">${esc(t.name)}${builtin}</td>
                    <td><span class="pms-swatch" style="background:${esc(t.color)}"></span>${esc(t.color)}</td>
                    <td>${active}</td>
                    <td class="pms-col-actions">
                        <button class="pms-link" onclick="PMS.openEdit(${t.id})">${esc(window.t('common.edit'))}</button>
                        <button class="pms-link pms-danger" ${t.is_builtin ? 'disabled' : ''} onclick="PMS.del(${t.id})">${esc(window.t('common.delete'))}</button>
                    </td>
                </tr>`;
            }).join('');
        }

        function pickShape(key) {
            pickedShape = key;
            document.querySelectorAll('.pms-shape-opt').forEach(b => {
                b.classList.toggle('selected', b.dataset.shapeKey === key);
            });
        }

        function showModal()  { document.getElementById('pmsModal').classList.add('open'); }
        function closeModal() { document.getElementById('pmsModal').classList.remove('open'); }

        function openAdd() {
            editingId = null;
            document.getElementById('pmsModalTitle').textContent = window.t('process-mapper.settings.add_title');
            document.getElementById('pmsName').value = '';
            document.getElementById('pmsColor').value = '#0078d4';
            document.getElementById('pmsActive').checked = true;
            pickShape('rounded');
            showModal();
            document.getElementById('pmsName').focus();
        }

        function openEdit(id) {
            const t = types.find(x => x.id === id);
            if (!t) return;
            editingId = id;
            document.getElementById('pmsModalTitle').textContent = window.t('process-mapper.settings.edit_title');
            document.getElementById('pmsName').value = t.name || '';
            document.getElementById('pmsColor').value = /^#[0-9a-fA-F]{6}$/.test(t.color) ? t.color : '#0078d4';
            document.getElementById('pmsActive').checked = !!t.is_active;
            pickShape(SHAPE_KEYS.includes(t.shape) ? t.shape : 'rounded');
            showModal();
            document.getElementById('pmsName').focus();
        }

        async function save() {
            const name = document.getElementById('pmsName').value.trim();
            if (!name) { toast(window.t('process-mapper.settings.name_required'), 'error'); return; }
            const payload = {
                id: editingId,
                name: name,
                shape: pickedShape,
                color: document.getElementById('pmsColor').value,
                is_active: document.getElementById('pmsActive').checked ? 1 : 0
            };
            try {
                const r = await fetch(API + 'save_step_type.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const d = await r.json();
                if (d.success) { closeModal(); toast(window.t('process-mapper.settings.saved')); loadTypes(); }
                else toast(d.error || 'Save failed', 'error');
            } catch (e) { toast('Save failed', 'error'); }
        }

        async function del(id) {
            if (!confirm(window.t('process-mapper.settings.delete_confirm'))) return;
            try {
                const r = await fetch(API + 'delete_step_type.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const d = await r.json();
                if (d.success) { toast(window.t('process-mapper.settings.deleted')); loadTypes(); }
                else toast(d.error || 'Delete failed', 'error');
            } catch (e) { toast('Delete failed', 'error'); }
        }

        async function move(id, dir) {
            const i = types.findIndex(t => t.id === id);
            const j = i + dir;
            if (i < 0 || j < 0 || j >= types.length) return;
            [types[i], types[j]] = [types[j], types[i]];
            render();
            try {
                await fetch(API + 'reorder_step_types.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: types.map(t => t.id) })
                });
            } catch (e) { toast('Reorder failed', 'error'); }
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
        document.addEventListener('DOMContentLoaded', loadTypes);

        return { openAdd, openEdit, pickShape, save, del, move, closeModal };
    })();
    </script>
</body>
</html>
