<?php
/**
 * Forms Module - View Submissions
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
    <title>Service Desk - Form Submissions</title>
    <link rel="stylesheet" href="../assets/css/inbox.css">
    <style>
        .subs-container {
            flex: 1;
            overflow-y: auto;
            background-color: #f5f7fa;
        }

        .subs-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px;
        }

        .subs-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .subs-toolbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .subs-toolbar-left h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .subs-toolbar-left .sub-count {
            font-size: 13px;
            color: #888;
            background: #f0f0f0;
            padding: 3px 10px;
            border-radius: 12px;
        }

        .subs-toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #666;
        }

        .filter-group input[type="date"] {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
        }

        .filter-group input[type="date"]:focus {
            outline: none;
            border-color: #00897b;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background-color 0.15s;
        }

        .btn-secondary { background: #f5f7fa; color: #333; border: 1px solid #ddd; }
        .btn-secondary:hover { background: #eef0f2; }
        .btn-primary { background: #00897b; color: white; }
        .btn-primary:hover { background: #00695c; }
        .btn-export { background: #1565c0; color: white; }
        .btn-export:hover { background: #0d47a1; }

        .subs-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .subs-table-wrap {
            overflow-x: auto;
        }

        .subs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .subs-table th {
            background: #f8f9fa;
            padding: 10px 14px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e8e8e8;
            white-space: nowrap;
            position: sticky;
            top: 0;
        }

        .subs-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .subs-table tr:hover td {
            background: #f8fbff;
        }

        .subs-table tr {
            cursor: pointer;
        }

        .subs-table .cb-value {
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 3px;
            text-align: center;
            line-height: 18px;
            font-size: 11px;
            font-weight: 700;
        }

        .cb-yes { background: #e8f5e9; color: #2e7d32; }
        .cb-no { background: #f5f5f5; color: #999; }

        .subs-table .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #d32f2f;
            padding: 4px 6px;
            border-radius: 3px;
        }

        .subs-table .delete-btn:hover {
            background: #ffebee;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state h3 {
            margin: 15px 0 8px;
            font-size: 16px;
            color: #666;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        /* Detail modal */
        .detail-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .detail-overlay.open { display: flex; }

        .detail-box {
            background: #fff;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }

        .detail-header {
            padding: 18px 22px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: #fff;
            border-radius: 8px 8px 0 0;
        }

        .detail-header h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .detail-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #999;
            font-size: 20px;
            line-height: 1;
        }

        .detail-close:hover { color: #333; }

        .detail-body {
            padding: 22px;
        }

        .detail-meta {
            display: flex;
            gap: 20px;
            font-size: 13px;
            color: #888;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-field {
            margin-bottom: 16px;
        }

        .detail-field-label {
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .detail-field-value {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .detail-field-value.empty {
            color: #ccc;
            font-style: italic;
        }

        /* Confirm delete overlay */
        .confirm-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1100;
            justify-content: center;
            align-items: center;
        }

        .confirm-overlay.open { display: flex; }

        .confirm-box {
            background: #fff;
            border-radius: 8px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }

        .confirm-box h3 { margin: 0 0 8px; font-size: 16px; }
        .confirm-box p { margin: 0 0 20px; font-size: 14px; color: #666; }

        .confirm-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-cancel { background: #f5f5f5; color: #333; border: 1px solid #ddd; }
        .btn-danger { background: #d32f2f; color: white; }
        .btn-danger:hover { background: #b71c1c; }

        @media print {
            .header, .subs-toolbar-right, .subs-table .delete-btn,
            .detail-overlay, .confirm-overlay { display: none !important; }
            .subs-content { padding: 0; max-width: 100%; }
            .subs-card { box-shadow: none; }
            .subs-table td { max-width: none; white-space: normal; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="main-container subs-container">
        <div class="subs-content">
            <div class="subs-toolbar">
                <div class="subs-toolbar-left">
                    <a href="./" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        Back
                    </a>
                    <h2 id="pageTitle">Submissions</h2>
                    <span class="sub-count" id="subCount"></span>
                </div>
                <div class="subs-toolbar-right">
                    <div class="filter-group">
                        <label>From</label>
                        <input type="date" id="dateFrom" onchange="applyFilter()">
                    </div>
                    <div class="filter-group">
                        <label>To</label>
                        <input type="date" id="dateTo" onchange="applyFilter()">
                    </div>
                    <button class="btn btn-secondary" onclick="clearFilter()">Clear</button>
                    <button class="btn btn-export" onclick="exportCSV()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Export CSV
                    </button>
                </div>
            </div>

            <div class="subs-card">
                <div class="subs-table-wrap">
                    <div id="subsContent">
                        <div style="text-align:center;padding:40px;color:#888">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail modal -->
    <div class="detail-overlay" id="detailOverlay" onclick="if(event.target===this)closeDetail()">
        <div class="detail-box">
            <div class="detail-header">
                <h3>Submission Detail</h3>
                <button class="detail-close" onclick="closeDetail()">&times;</button>
            </div>
            <div class="detail-body" id="detailBody"></div>
        </div>
    </div>

    <!-- Confirm delete -->
    <div class="confirm-overlay" id="confirmOverlay" onclick="if(event.target===this)closeConfirm()">
        <div class="confirm-box">
            <h3>Delete Submission</h3>
            <p>This will permanently delete this submission and its data. Are you sure?</p>
            <div class="confirm-actions">
                <button class="btn btn-cancel" onclick="closeConfirm()">Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '../api/forms/';
        let formData = null;
        let allSubmissions = [];
        let filteredSubmissions = [];

        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            if (id) {
                loadSubmissions(id);
            } else {
                document.getElementById('subsContent').innerHTML = '<p style="color:#c00;text-align:center;padding:20px">No form ID specified</p>';
            }
        });

        async function loadSubmissions(formId) {
            try {
                const res = await fetch(API_BASE + 'get_submissions.php?form_id=' + formId);
                const data = await res.json();

                if (data.success) {
                    formData = data.form;
                    formData.fields = data.fields;
                    allSubmissions = data.submissions;
                    filteredSubmissions = allSubmissions;

                    document.getElementById('pageTitle').textContent = esc(formData.title) + ' — Submissions';
                    document.title = 'Service Desk - ' + formData.title + ' Submissions';

                    renderTable();
                } else {
                    document.getElementById('subsContent').innerHTML = '<p style="color:#c00;text-align:center;padding:20px">' + esc(data.error) + '</p>';
                }
            } catch (e) {
                console.error(e);
                document.getElementById('subsContent').innerHTML = '<p style="color:#c00;text-align:center;padding:20px">Failed to load submissions</p>';
            }
        }

        function renderTable() {
            const count = filteredSubmissions.length;
            document.getElementById('subCount').textContent = count + ' submission' + (count !== 1 ? 's' : '');

            if (count === 0) {
                document.getElementById('subsContent').innerHTML = `<div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    <h3>No submissions yet</h3>
                    <p><a href="fill.php?id=${formData.id}">Fill in this form</a> to create the first submission</p>
                </div>`;
                return;
            }

            let html = '<table class="subs-table"><thead><tr>';
            html += '<th>#</th>';
            html += '<th>Submitted By</th>';
            html += '<th>Date</th>';

            formData.fields.forEach(f => {
                html += `<th>${esc(f.label)}</th>`;
            });

            html += '<th></th>';
            html += '</tr></thead><tbody>';

            filteredSubmissions.forEach((sub, idx) => {
                html += `<tr onclick="showDetail(${idx})">`;
                html += `<td>${count - idx}</td>`;
                html += `<td>${esc(sub.submitted_by || 'Unknown')}</td>`;
                html += `<td>${esc(formatDate(sub.submitted_date))}</td>`;

                formData.fields.forEach(f => {
                    const val = sub.data[f.id] ?? '';
                    if (f.field_type === 'checkbox') {
                        const checked = val === '1';
                        html += `<td><span class="cb-value ${checked ? 'cb-yes' : 'cb-no'}">${checked ? '&#10003;' : '&#10007;'}</span></td>`;
                    } else if (f.field_type === 'checkboxes') {
                        // Stored as a JSON-encoded array; show as a
                        // comma-joined list. Title attr keeps the raw
                        // value visible on hover for long lists.
                        const list = decodeMultiValue(val);
                        const display = list.length ? list.join(', ') : '';
                        html += `<td title="${esc(display)}">${esc(display) || '<span style="color:#ccc">—</span>'}</td>`;
                    } else {
                        html += `<td title="${esc(val)}">${esc(val) || '<span style="color:#ccc">—</span>'}</td>`;
                    }
                });

                html += `<td><button class="delete-btn" onclick="event.stopPropagation();confirmDelete(${sub.id})" title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button></td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            document.getElementById('subsContent').innerHTML = html;
        }

        function showDetail(idx) {
            const sub = filteredSubmissions[idx];
            if (!sub) return;

            let html = `<div class="detail-meta">
                <span><strong>Submitted by:</strong> ${esc(sub.submitted_by || 'Unknown')}</span>
                <span><strong>Date:</strong> ${esc(formatDate(sub.submitted_date))}</span>
            </div>`;

            formData.fields.forEach(f => {
                const val = sub.data[f.id] ?? '';
                html += `<div class="detail-field">
                    <div class="detail-field-label">${esc(f.label)}</div>`;

                if (f.field_type === 'checkbox') {
                    const checked = val === '1';
                    html += `<div class="detail-field-value"><span class="cb-value ${checked ? 'cb-yes' : 'cb-no'}">${checked ? '&#10003;' : '&#10007;'}</span> ${checked ? 'Yes' : 'No'}</div>`;
                } else if (f.field_type === 'checkboxes') {
                    const list = decodeMultiValue(val);
                    if (list.length === 0) {
                        html += `<div class="detail-field-value empty">No response</div>`;
                    } else {
                        html += `<div class="detail-field-value"><ul style="margin:0; padding-left: 18px;">${list.map(v => `<li>${esc(v)}</li>`).join('')}</ul></div>`;
                    }
                } else {
                    html += `<div class="detail-field-value ${!val ? 'empty' : ''}">${esc(val) || 'No response'}</div>`;
                }

                html += '</div>';
            });

            document.getElementById('detailBody').innerHTML = html;
            document.getElementById('detailOverlay').classList.add('open');
        }

        function closeDetail() {
            document.getElementById('detailOverlay').classList.remove('open');
        }

        // Date filter
        function applyFilter() {
            const from = document.getElementById('dateFrom').value;
            const to = document.getElementById('dateTo').value;

            filteredSubmissions = allSubmissions.filter(sub => {
                const d = sub.submitted_date.substring(0, 10); // YYYY-MM-DD
                if (from && d < from) return false;
                if (to && d > to) return false;
                return true;
            });

            renderTable();
        }

        function clearFilter() {
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            filteredSubmissions = allSubmissions;
            renderTable();
        }

        // CSV export
        function exportCSV() {
            if (!formData || filteredSubmissions.length === 0) return;

            const headers = ['#', 'Submitted By', 'Date'];
            formData.fields.forEach(f => headers.push(f.label));

            const rows = [headers.map(h => csvCell(h)).join(',')];

            filteredSubmissions.forEach((sub, idx) => {
                const row = [
                    filteredSubmissions.length - idx,
                    sub.submitted_by || 'Unknown',
                    formatDate(sub.submitted_date)
                ];

                formData.fields.forEach(f => {
                    const val = sub.data[f.id] ?? '';
                    if (f.field_type === 'checkbox') {
                        row.push(val === '1' ? 'Yes' : 'No');
                    } else if (f.field_type === 'checkboxes') {
                        row.push(decodeMultiValue(val).join('; '));
                    } else {
                        row.push(val);
                    }
                });

                rows.push(row.map(c => csvCell(String(c))).join(','));
            });

            const csv = '\uFEFF' + rows.join('\r\n'); // BOM for Excel
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = (formData.title || 'submissions').replace(/[^a-zA-Z0-9 _-]/g, '') + '_submissions.csv';
            a.click();
            URL.revokeObjectURL(url);
        }

        function csvCell(text) {
            if (text.includes(',') || text.includes('"') || text.includes('\n')) {
                return '"' + text.replace(/"/g, '""') + '"';
            }
            return text;
        }

        // Delete submission
        let deleteSubId = null;

        function confirmDelete(id) {
            deleteSubId = id;
            document.getElementById('confirmOverlay').classList.add('open');
        }

        function closeConfirm() {
            document.getElementById('confirmOverlay').classList.remove('open');
            deleteSubId = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
            if (!deleteSubId) return;
            try {
                const res = await fetch(API_BASE + 'delete_submission.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: deleteSubId })
                });
                const data = await res.json();
                if (data.success) {
                    closeConfirm();
                    closeDetail();
                    // Remove from arrays
                    allSubmissions = allSubmissions.filter(s => s.id !== deleteSubId);
                    filteredSubmissions = filteredSubmissions.filter(s => s.id !== deleteSubId);
                    renderTable();
                }
            } catch (e) {
                console.error(e);
            }
        });

        // Keyboard
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetail();
                closeConfirm();
            }
        });

        // Helpers
        function formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })
                + ' ' + d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
        }

        function esc(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Decode the JSON-encoded array stored for multi-checkbox values.
        // Tolerant of empty / null / non-JSON garbage — never throws.
        function decodeMultiValue(raw) {
            if (raw == null || raw === '') return [];
            try {
                const parsed = JSON.parse(raw);
                return Array.isArray(parsed) ? parsed.map(v => String(v)) : [];
            } catch (e) {
                return [];
            }
        }
    </script>
</body>
</html>
