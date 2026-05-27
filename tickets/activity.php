<?php
/**
 * Mailbox Activity Log - Full screen view
 */
session_start();
require_once '../config.php';

if (!isset($_SESSION['analyst_id'])) {
    header('Location: ../login.php');
    exit;
}

$analyst_name = $_SESSION['analyst_name'] ?? 'Analyst';
$current_page = 'settings';
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Mailbox Activity</title>
    <link rel="stylesheet" href="../assets/css/inbox.css">
    <style>
        .activity-container {
            display: flex;
            height: calc(100vh - 48px);
            background: #f5f5f5;
        }

        /* Sidebar */
        .activity-sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin: 0 0 12px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-header .search-box input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            box-sizing: border-box;
        }

        .sidebar-header .search-box input:focus {
            outline: none;
            border-color: #0078d4;
        }

        .mailbox-list {
            flex: 1;
            padding: 8px 0;
        }

        .mailbox-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            transition: all 0.15s;
        }

        .mailbox-item:hover {
            background: #e8f4fd;
        }

        .mailbox-item.active {
            background: #0078d4;
            color: white;
        }

        .mailbox-item .mailbox-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        .mailbox-item .mailbox-count {
            font-size: 12px;
            font-weight: 600;
            background: rgba(0,0,0,0.08);
            padding: 2px 8px;
            border-radius: 10px;
            min-width: 24px;
            text-align: center;
            margin-left: 8px;
        }

        .mailbox-item.active .mailbox-count {
            background: rgba(255,255,255,0.25);
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #0078d4;
            text-decoration: none;
            font-size: 13px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Main content */
        .activity-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .activity-header {
            padding: 20px 30px 0;
            background: #f5f5f5;
        }

        .activity-header h2 {
            margin: 0 0 15px 0;
            font-size: 20px;
            color: #333;
        }

        .activity-header .search-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .activity-header .search-row input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .activity-header .search-row input:focus {
            outline: none;
            border-color: #0078d4;
        }

        .activity-table-wrap {
            flex: 1;
            overflow-y: auto;
            padding: 0 30px;
        }

        .activity-table-wrap table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .activity-table-wrap thead th {
            position: sticky;
            top: 0;
            background: #f5f5f5;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #ddd;
            white-space: nowrap;
        }

        .activity-table-wrap tbody tr {
            cursor: pointer;
            transition: background 0.1s;
        }

        .activity-table-wrap tbody tr:hover {
            background: #e8f4fd;
        }

        .activity-table-wrap tbody tr.selected {
            background: #d0e8f7;
        }

        .activity-table-wrap tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }

        .activity-footer {
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #666;
            border-top: 1px solid #ddd;
            background: white;
        }

        /* Processing log panel */
        .log-panel {
            border-top: 1px solid #ddd;
            background: white;
            max-height: 280px;
            display: flex;
            flex-direction: column;
        }

        .log-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 30px;
            border-bottom: 1px solid #eee;
        }

        .log-panel-header strong {
            font-size: 14px;
        }

        .log-panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 12px 30px;
        }

        .log-panel-body pre {
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 12px;
            font-size: 12px;
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .badge-imported {
            display: inline-block;
            padding: 2px 8px;
            background: #d4edda;
            color: #155724;
            border-radius: 10px;
            font-size: 11px;
        }

        .badge-rejected {
            display: inline-block;
            padding: 2px 8px;
            background: #f8d7da;
            color: #721c24;
            border-radius: 10px;
            font-size: 11px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 15px;
            stroke: #ccc;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="activity-container">
        <!-- Sidebar -->
        <div class="activity-sidebar">
            <div class="sidebar-header">
                <h3>Mailboxes</h3>
                <div class="search-box">
                    <input type="text" id="mailboxSearch" placeholder="Filter mailboxes..." oninput="filterMailboxList()">
                </div>
            </div>
            <div class="mailbox-list" id="mailboxList"></div>
            <div class="sidebar-footer">
                <a href="settings/" class="back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Settings
                </a>
            </div>
        </div>

        <!-- Main content -->
        <div class="activity-main">
            <div class="activity-header">
                <h2 id="activityTitle">All Activity</h2>
                <div class="search-row">
                    <input type="text" id="activitySearch" placeholder="Search by sender, name, or subject..." oninput="debounceSearch()">
                </div>
            </div>

            <div class="activity-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>Mailbox</th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Action</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody id="activityBody">
                        <tr><td colspan="6" class="empty-state">Select a mailbox to view activity</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="activity-footer" id="activityFooter">
                <span id="activityCount"></span>
                <div id="activityPagination"></div>
            </div>

            <div class="log-panel" id="logPanel" style="display: none;">
                <div class="log-panel-header">
                    <strong>Processing Log</strong>
                    <button class="btn btn-secondary" style="padding: 3px 10px; font-size: 12px;" onclick="closeLogPanel()">Close</button>
                </div>
                <div class="log-panel-body">
                    <pre id="logContent"></pre>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '../api/tickets/';
        let mailboxes = [];
        let selectedMailboxId = null;
        let currentPage = 1;
        let searchTimer = null;

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load mailboxes for sidebar
        async function loadMailboxes() {
            try {
                const res = await fetch(API_BASE + 'get_mailboxes.php');
                const data = await res.json();
                if (data.success) {
                    mailboxes = data.mailboxes;
                    renderMailboxList();
                    // Load counts for each mailbox
                    loadMailboxCounts();
                }
            } catch (err) {
                console.error('Failed to load mailboxes:', err);
            }
        }

        async function loadMailboxCounts() {
            for (const mb of mailboxes) {
                try {
                    const res = await fetch(API_BASE + 'get_mailbox_activity.php?mailbox_id=' + mb.id + '&page=1');
                    const data = await res.json();
                    if (data.success) {
                        mb._count = data.total;
                    }
                } catch (err) {
                    mb._count = 0;
                }
            }
            renderMailboxList();
        }

        function renderMailboxList() {
            const container = document.getElementById('mailboxList');
            const filter = document.getElementById('mailboxSearch').value.toLowerCase();

            const filtered = mailboxes.filter(mb =>
                !filter || mb.name.toLowerCase().includes(filter) || mb.target_mailbox.toLowerCase().includes(filter)
            );

            let html = `<div class="mailbox-item ${selectedMailboxId === null ? 'active' : ''}" onclick="selectMailbox(null)">
                <span class="mailbox-name">All Mailboxes</span>
            </div>`;

            html += filtered.map(mb => {
                const count = mb._count !== undefined ? mb._count : '';
                return `<div class="mailbox-item ${selectedMailboxId === mb.id ? 'active' : ''}" onclick="selectMailbox(${mb.id})">
                    <span class="mailbox-name">${escapeHtml(mb.name)}</span>
                    ${count !== '' ? '<span class="mailbox-count">' + count + '</span>' : ''}
                </div>`;
            }).join('');

            container.innerHTML = html;
        }

        function filterMailboxList() {
            renderMailboxList();
        }

        function selectMailbox(id) {
            selectedMailboxId = id;
            currentPage = 1;
            renderMailboxList();
            closeLogPanel();

            if (id === null) {
                document.getElementById('activityTitle').textContent = 'All Activity';
            } else {
                const mb = mailboxes.find(m => m.id === id);
                document.getElementById('activityTitle').textContent = mb ? mb.name : 'Activity';
            }

            loadActivity();
        }

        function debounceSearch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                currentPage = 1;
                loadActivity();
            }, 300);
        }

        async function loadActivity() {
            if (selectedMailboxId === null) {
                loadAllActivity();
                return;
            }

            const tbody = document.getElementById('activityBody');
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #999;">Loading...</td></tr>';

            const search = document.getElementById('activitySearch').value;

            try {
                let url = API_BASE + 'get_mailbox_activity.php?mailbox_id=' + selectedMailboxId + '&page=' + currentPage;
                if (search) url += '&search=' + encodeURIComponent(search);

                const res = await fetch(url);
                const data = await res.json();

                if (!data.success) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">' + escapeHtml(data.error) + '</td></tr>';
                    return;
                }

                if (data.entries.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #999;">No activity found</td></tr>';
                    document.getElementById('activityCount').textContent = '';
                    document.getElementById('activityPagination').innerHTML = '';
                    return;
                }

                const mbName = (() => {
                    const mb = mailboxes.find(m => m.id === selectedMailboxId);
                    return mb ? mb.name : '';
                })();

                window._logs = data.entries.map(e => e.processing_log || null);

                tbody.innerHTML = data.entries.map((e, idx) => {
                    const dt = new Date(e.created_datetime + 'Z').toLocaleString();
                    const badge = e.action === 'imported'
                        ? '<span class="badge-imported">Imported</span>'
                        : '<span class="badge-rejected">Rejected</span>';
                    const from = escapeHtml(e.from_name ? e.from_name + ' <' + e.from_address + '>' : e.from_address);
                    return `<tr onclick="showLog(${idx})">
                        <td style="white-space: nowrap;">${dt}</td>
                        <td>${escapeHtml(mbName)}</td>
                        <td>${from}</td>
                        <td>${escapeHtml(e.subject || '')}</td>
                        <td>${badge}</td>
                        <td>${escapeHtml(e.reason || '')}</td>
                    </tr>`;
                }).join('');

                renderPagination(data.total, data.per_page, currentPage);

            } catch (err) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">Failed to load activity</td></tr>';
            }
        }

        async function loadAllActivity() {
            const tbody = document.getElementById('activityBody');
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #999;">Loading...</td></tr>';

            const search = document.getElementById('activitySearch').value;

            try {
                // Fetch from all mailboxes and merge
                const allEntries = [];
                for (const mb of mailboxes) {
                    let url = API_BASE + 'get_mailbox_activity.php?mailbox_id=' + mb.id + '&page=1';
                    if (search) url += '&search=' + encodeURIComponent(search);

                    const res = await fetch(url);
                    const data = await res.json();
                    if (data.success) {
                        data.entries.forEach(e => {
                            e._mailbox_name = mb.name;
                            allEntries.push(e);
                        });
                    }
                }

                // Sort by datetime desc
                allEntries.sort((a, b) => new Date(b.created_datetime) - new Date(a.created_datetime));

                // Limit to 100 most recent
                const entries = allEntries.slice(0, 100);

                if (entries.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #999;">No activity found</td></tr>';
                    document.getElementById('activityCount').textContent = '';
                    document.getElementById('activityPagination').innerHTML = '';
                    return;
                }

                window._logs = entries.map(e => e.processing_log || null);

                tbody.innerHTML = entries.map((e, idx) => {
                    const dt = new Date(e.created_datetime + 'Z').toLocaleString();
                    const badge = e.action === 'imported'
                        ? '<span class="badge-imported">Imported</span>'
                        : '<span class="badge-rejected">Rejected</span>';
                    const from = escapeHtml(e.from_name ? e.from_name + ' <' + e.from_address + '>' : e.from_address);
                    return `<tr onclick="showLog(${idx})">
                        <td style="white-space: nowrap;">${dt}</td>
                        <td>${escapeHtml(e._mailbox_name || '')}</td>
                        <td>${from}</td>
                        <td>${escapeHtml(e.subject || '')}</td>
                        <td>${badge}</td>
                        <td>${escapeHtml(e.reason || '')}</td>
                    </tr>`;
                }).join('');

                document.getElementById('activityCount').textContent = 'Showing ' + entries.length + ' most recent entries';
                document.getElementById('activityPagination').innerHTML = '';

            } catch (err) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">Failed to load activity</td></tr>';
            }
        }

        function renderPagination(total, perPage, page) {
            const totalPages = Math.ceil(total / perPage);
            document.getElementById('activityCount').textContent = total + ' entries';

            if (totalPages <= 1) {
                document.getElementById('activityPagination').innerHTML = '';
                return;
            }

            let html = '';
            if (page > 1) {
                html += `<button class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px; margin-right: 4px;" onclick="goToPage(${page - 1})">Prev</button>`;
            }
            html += `<span style="margin: 0 8px;">Page ${page} of ${totalPages}</span>`;
            if (page < totalPages) {
                html += `<button class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px; margin-left: 4px;" onclick="goToPage(${page + 1})">Next</button>`;
            }
            document.getElementById('activityPagination').innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            closeLogPanel();
            loadActivity();
        }

        function showLog(idx) {
            const logJson = window._logs[idx];
            const panel = document.getElementById('logPanel');
            const content = document.getElementById('logContent');

            // Highlight selected row
            document.querySelectorAll('.activity-table-wrap tbody tr').forEach((tr, i) => {
                tr.classList.toggle('selected', i === idx);
            });

            if (!logJson) {
                content.textContent = 'No processing log available for this entry.';
            } else {
                try {
                    const parsed = typeof logJson === 'string' ? JSON.parse(logJson) : logJson;
                    content.textContent = JSON.stringify(parsed, null, 2);
                } catch (e) {
                    content.textContent = logJson;
                }
            }
            panel.style.display = '';
        }

        function closeLogPanel() {
            document.getElementById('logPanel').style.display = 'none';
            document.querySelectorAll('.activity-table-wrap tbody tr.selected').forEach(tr => tr.classList.remove('selected'));
        }

        // Check URL params for pre-selected mailbox
        function init() {
            const params = new URLSearchParams(window.location.search);
            const mbId = params.get('mailbox_id');
            if (mbId) {
                selectedMailboxId = parseInt(mbId);
            }

            loadMailboxes().then(() => {
                if (selectedMailboxId !== null) {
                    const mb = mailboxes.find(m => m.id === selectedMailboxId);
                    if (mb) {
                        document.getElementById('activityTitle').textContent = mb.name;
                    }
                    renderMailboxList();
                }
                loadActivity();
            });
        }

        init();
    </script>
</body>
</html>
