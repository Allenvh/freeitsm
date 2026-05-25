<?php
/**
 * Morning Checks Settings Page
 */
session_start();
require_once '../../config.php';

if (!isset($_SESSION['analyst_id'])) {
    header('Location: ../../login.php');
    exit;
}

$analyst_name = $_SESSION['analyst_name'] ?? 'Analyst';
$current_page = 'settings';
$path_prefix = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Morning Checks Settings</title>
    <link rel="stylesheet" href="../../assets/css/inbox.css">
    <script src="../../assets/js/toast.js"></script>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { padding-top: 0; }
        /* Full-width settings page, matching the canonical padding used by
           the other modules' settings pages. */
        .settings-container {
            height: calc(100vh - 48px);
            overflow-y: auto;
            max-width: none;
            margin: 0;
            padding: 16px 30px 24px;
        }

        /* Blue theme for Morning Checks tabs */
        .tab:hover { color: #007bff; }
        .tab.active { color: #007bff; border-bottom-color: #007bff; }

        /* Section header with Add button */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .section-header h2 { margin: 0; font-size: 18px; color: #2c3e50; }
        .section-header .btn-primary {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 14px;
        }

        /* Check items list. Rendered as a flat list with thin separators
           rather than per-row cards — the outer .tab-content already
           provides the white-card surface so per-row cards were doubling
           up the visual nesting and wasting vertical space. */
        .checks-list {
            margin-top: 0;
            border-top: 1px solid #f0f0f0;
        }
        .check-item {
            padding: 10px 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.15s;
        }
        .check-item:hover { background: #fafafa; }

        /* Grip handle */
        .check-drag {
            cursor: grab;
            color: #bbb;
            padding: 4px;
            touch-action: none;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        .check-drag:active { cursor: grabbing; }
        .check-drag:hover { color: #888; }

        /* Check info */
        .check-info { flex: 1; min-width: 0; }
        .check-info strong { display: block; color: #333; font-size: 14px; margin-bottom: 2px; }
        .check-description {
            display: block;
            font-size: 12px;
            color: #888;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Drag-and-drop states. Blue 2px line above / below the drop
           target indicates where the row will land. */
        .check-item.dragging { opacity: 0.4; }
        .check-item.drag-over-top { box-shadow: inset 0 2px 0 0 #007bff; }
        .check-item.drag-over-bottom { box-shadow: inset 0 -2px 0 0 #007bff; }

        /* Check actions — icon buttons (pencil + trash). Overrides
           inbox.css's chunky .action-btn default for this page so the
           buttons sit tight at the end of each row. */
        .check-actions { display: flex; gap: 4px; flex-shrink: 0; }
        .action-btn {
            background: none;
            border: none;
            padding: 4px;
            color: #666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            gap: 0;
            cursor: pointer;
        }
        .action-btn:hover { background: none; border: none; color: #007bff; }
        .action-btn.delete:hover { color: #c62828; }
        .action-btn svg { width: 16px; height: 16px; }

        /* Empty / loading states */
        .checks-empty {
            text-align: center;
            padding: 40px 20px;
            color: #999;
            font-size: 14px;
        }

        /* Toggle switch */
        .toggle-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #ccc;
            border-radius: 24px;
            transition: background 0.2s;
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: transform 0.2s;
        }
        .toggle-switch input:checked + .toggle-slider { background: #007bff; }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); }
        .toggle-label { font-size: 14px; color: #333; cursor: pointer; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="settings-container">
        <div class="tabs">
            <button class="tab active" data-tab="checks" onclick="switchTab('checks')">Checks</button>
            <button class="tab" data-tab="chart" onclick="switchTab('chart')">Chart</button>
        </div>

        <div class="tab-content active" id="checks-tab">
            <div class="section-header">
                <h2>Checks</h2>
                <button class="btn-primary" onclick="openAddModal()">Add</button>
            </div>
            <div class="checks-list" id="checksList">
                <div class="checks-empty">Loading checks...</div>
            </div>
        </div>

        <!-- Chart tab: visual options for the dashboard trend chart.
             Saved per-analyst via the generic user-preference API so
             different analysts can choose different looks. -->
        <div class="tab-content" id="chart-tab">
            <div class="section-header">
                <h2>Chart</h2>
            </div>
            <p style="color: #666; margin-bottom: 16px;">Visual style for the trend chart on the dashboard.</p>

            <div class="form-group">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333; font-size: 13px;">Bar fill</label>
                <div style="display: flex; gap: 24px; margin-top: 4px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #333;">
                        <input type="radio" name="chartFill" value="plain" id="chartFillPlain">
                        Plain
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #333;">
                        <input type="radio" name="chartFill" value="gradient" id="chartFillGradient">
                        Gradient
                    </label>
                </div>
                <p style="font-size: 12px; color: #888; margin-top: 8px;">Plain uses a solid fill. Gradient fades from a lighter shade at the top of each bar segment down to the full colour.</p>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Add check</h2>
            <form id="addCheckForm">
                <div class="form-group">
                    <label for="addCheckName">Check name *</label>
                    <input type="text" id="addCheckName" required>
                </div>
                <div class="form-group">
                    <label for="addCheckDescription">Description</label>
                    <textarea id="addCheckDescription" rows="3"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Edit check</h2>
            <form id="editCheckForm">
                <input type="hidden" id="editCheckId">
                <div class="form-group">
                    <label for="editCheckName">Check name *</label>
                    <input type="text" id="editCheckName" required>
                </div>
                <div class="form-group">
                    <label for="editCheckDescription">Description</label>
                    <textarea id="editCheckDescription" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="toggle-group">
                        <span class="toggle-switch">
                            <input type="checkbox" id="editIsActive">
                            <span class="toggle-slider"></span>
                        </span>
                        <span class="toggle-label">Active</span>
                    </label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = '../../api/morning-checks/';
        let checks = [];
        let checkDragAllowed = false;
        let dragIndex = null;

        // Track mousedown for drag handle detection
        document.addEventListener('mousedown', function(e) {
            checkDragAllowed = !!e.target.closest('.check-drag');
        });

        // Tab switching
        function switchTab(tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            const btn = document.querySelector('.tab[data-tab="' + tab + '"]');
            if (btn) btn.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tab + '-tab').classList.add('active');
        }

        // Load checks
        async function loadChecks() {
            try {
                const response = await fetch(API_BASE + 'get_all_checks.php');
                checks = await response.json();
                if (checks.error) {
                    document.getElementById('checksList').innerHTML =
                        '<div class="checks-empty" style="color:#dc3545;">Error: ' + checks.error + '</div>';
                    return;
                }
                renderChecks();
            } catch (error) {
                document.getElementById('checksList').innerHTML =
                    '<div class="checks-empty" style="color:#dc3545;">Error loading checks: ' + error.message + '</div>';
            }
        }

        // SVG icons for the row action buttons. Same pencil + trash glyphs
        // used in change-management and calendar settings — centralised here
        // so future icon tweaks (size, stroke) touch one spot.
        const ICON_EDIT = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';
        const ICON_DELETE = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';

        // Render checks list
        function renderChecks() {
            const container = document.getElementById('checksList');

            if (checks.length === 0) {
                container.innerHTML = '<div class="checks-empty">No checks defined yet. Click Add to create one.</div>';
                return;
            }

            container.innerHTML = checks.map((check, i) => `
                <div class="check-item"
                     data-id="${check.CheckID}"
                     data-index="${i}"
                     draggable="true"
                     ondragstart="onDragStart(event, ${i})"
                     ondragend="onDragEnd(event)"
                     ondragover="onDragOver(event, ${i})"
                     ondrop="onDrop(event, ${i})">
                    <span class="check-drag" title="Drag to reorder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="8" cy="4" r="2"/><circle cx="16" cy="4" r="2"/>
                            <circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/>
                            <circle cx="8" cy="20" r="2"/><circle cx="16" cy="20" r="2"/>
                        </svg>
                    </span>
                    <div class="check-info">
                        <strong>${escapeHtml(check.CheckName)}</strong>
                        ${check.CheckDescription ? '<span class="check-description">' + escapeHtml(check.CheckDescription) + '</span>' : ''}
                    </div>
                    <span class="badge ${check.IsActive ? 'badge-active' : 'badge-inactive'}">
                        ${check.IsActive ? 'Active' : 'Inactive'}
                    </span>
                    <div class="check-actions">
                        <button class="action-btn" onclick="openEditModal(${check.CheckID})" title="Edit">${ICON_EDIT}</button>
                        <button class="action-btn delete" onclick="deleteCheck(${check.CheckID}, '${escapeHtml(check.CheckName).replace(/'/g, "\\'")}')" title="Delete">${ICON_DELETE}</button>
                    </div>
                </div>
            `).join('');
        }

        // --- Drag-and-drop ---

        function onDragStart(e, i) {
            if (!checkDragAllowed) {
                e.preventDefault();
                return;
            }
            dragIndex = i;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', 'check');
            requestAnimationFrame(() => {
                const item = document.querySelector('.check-item[data-index="' + i + '"]');
                if (item) item.classList.add('dragging');
            });
        }

        function onDragEnd(e) {
            dragIndex = null;
            document.querySelectorAll('.check-item').forEach(el => {
                el.classList.remove('dragging', 'drag-over-top', 'drag-over-bottom');
            });
        }

        function onDragOver(e, i) {
            if (dragIndex === null || dragIndex === i) return;
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            const rect = e.currentTarget.getBoundingClientRect();
            const midY = rect.top + rect.height / 2;
            document.querySelectorAll('.check-item').forEach(el => {
                el.classList.remove('drag-over-top', 'drag-over-bottom');
            });
            if (e.clientY < midY) {
                e.currentTarget.classList.add('drag-over-top');
            } else {
                e.currentTarget.classList.add('drag-over-bottom');
            }
        }

        function onDrop(e, i) {
            e.preventDefault();
            if (dragIndex === null || dragIndex === i) return;

            const rect = e.currentTarget.getBoundingClientRect();
            const midY = rect.top + rect.height / 2;
            let targetIndex = e.clientY < midY ? i : i + 1;
            if (dragIndex < targetIndex) targetIndex--;

            const [moved] = checks.splice(dragIndex, 1);
            checks.splice(targetIndex, 0, moved);

            dragIndex = null;
            renderChecks();
            saveOrder();
        }

        async function saveOrder() {
            const order = checks.map(c => c.CheckID);
            try {
                const response = await fetch(API_BASE + 'reorder_checks.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: order })
                });
                const data = await response.json();
                if (!data.success) {
                    showToast('Error saving order: ' + (data.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                showToast('Error saving order: ' + error.message, 'error');
            }
        }

        // --- Modals ---

        function openAddModal() {
            document.getElementById('addCheckForm').reset();
            document.getElementById('addModal').classList.add('active');
            document.getElementById('addCheckName').focus();
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
        }

        function openEditModal(checkId) {
            const check = checks.find(c => c.CheckID === checkId);
            if (!check) return;

            document.getElementById('editCheckId').value = check.CheckID;
            document.getElementById('editCheckName').value = check.CheckName;
            document.getElementById('editCheckDescription').value = check.CheckDescription || '';
            document.getElementById('editIsActive').checked = check.IsActive;
            document.getElementById('editModal').classList.add('active');
            document.getElementById('editCheckName').focus();
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        // Close modals on outside click
        window.addEventListener('click', function(event) {
            if (event.target.id === 'addModal') closeAddModal();
            if (event.target.id === 'editModal') closeEditModal();
        });

        // --- Form submissions ---

        document.getElementById('addCheckForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                checkName: document.getElementById('addCheckName').value.trim(),
                checkDescription: document.getElementById('addCheckDescription').value.trim(),
                sortOrder: checks.length
            };

            try {
                const response = await fetch(API_BASE + 'add_check.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                const data = await response.json();

                if (data.success) {
                    showToast('Check added successfully', 'success');
                    closeAddModal();
                    loadChecks();
                } else {
                    showToast('Error: ' + (data.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                showToast('Error adding check: ' + error.message, 'error');
            }
        });

        document.getElementById('editCheckForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const checkId = parseInt(document.getElementById('editCheckId').value);
            const check = checks.find(c => c.CheckID === checkId);

            const formData = {
                checkId: checkId,
                checkName: document.getElementById('editCheckName').value.trim(),
                checkDescription: document.getElementById('editCheckDescription').value.trim(),
                sortOrder: check ? check.SortOrder : 0,
                isActive: document.getElementById('editIsActive').checked
            };

            try {
                const response = await fetch(API_BASE + 'update_check.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                const data = await response.json();

                if (data.success) {
                    showToast('Check updated successfully', 'success');
                    closeEditModal();
                    loadChecks();
                } else {
                    showToast('Error: ' + (data.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                showToast('Error updating check: ' + error.message, 'error');
            }
        });

        // --- Delete ---

        async function deleteCheck(checkId, checkName) {
            if (!confirm('Are you sure you want to delete "' + checkName + '"?\n\nThis will also delete all associated results.')) {
                return;
            }

            try {
                const response = await fetch(API_BASE + 'delete_check.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ checkId: checkId })
                });
                const data = await response.json();

                if (data.success) {
                    showToast('Check deleted successfully', 'success');
                    loadChecks();
                } else {
                    showToast('Error: ' + (data.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                showToast('Error deleting check: ' + error.message, 'error');
            }
        }

        // --- Utilities ---

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ===== Chart tab — fill style preference =====
        // Per-analyst preference saved via the generic user-preference
        // API. Dashboard reads the same key on page load to decide
        // whether to render plain or gradient bars.
        const CHART_FILL_PREF = 'mc_chart_fill_style';

        async function loadChartFillSetting() {
            let v = 'plain';
            try {
                const res = await fetch('../../api/system/get_user_preference.php?key=' + CHART_FILL_PREF);
                const data = await res.json();
                if (data && data.success && data.value === 'gradient') v = 'gradient';
            } catch (e) {
                // Stick with default 'plain'
            }
            const radio = document.querySelector('input[name="chartFill"][value="' + v + '"]');
            if (radio) radio.checked = true;
        }

        function wireChartFillSetting() {
            document.querySelectorAll('input[name="chartFill"]').forEach(radio => {
                radio.addEventListener('change', async function() {
                    try {
                        const res = await fetch('../../api/system/set_user_preference.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ key: CHART_FILL_PREF, value: this.value })
                        });
                        const data = await res.json();
                        if (data && data.success) {
                            showToast('Saved', 'success');
                        } else {
                            showToast((data && data.error) || 'Failed to save', 'error');
                        }
                    } catch (e) {
                        showToast('Failed to save', 'error');
                    }
                });
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadChecks();
            loadChartFillSetting();
            wireChartFillSetting();
        });
    </script>
</body>
</html>
