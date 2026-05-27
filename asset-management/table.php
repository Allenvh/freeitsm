<?php
/**
 * Asset Management - Full-screen table view
 *
 * Companion to the split-pane landing page. Excel-style table with:
 *   - Column show/hide + drag-reorder (persisted per-user via user_preferences)
 *   - Click-to-sort on any column
 *   - Search box (matches across every visible column)
 *   - Per-column tickbox filter (distinct values from the loaded data)
 *   - Export to CSV
 *   - Export to PDF (selectable text via jsPDF + autotable, same approach as
 *     morning-checks)
 *
 * The column catalogue lives in one JS const (COLUMNS) so adding a new column
 * later is just: append to that array + ensure the API returns the field.
 * Sort / filter / search / CSV / PDF all read from the catalogue and "just work".
 */
session_start();
require_once '../config.php';

if (!isset($_SESSION['analyst_id'])) {
    header('Location: ../login.php');
    exit;
}

$current_page = 'table';
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - Asset table</title>
    <link rel="stylesheet" href="../assets/css/inbox.css?v=22">
    <!-- jsPDF + autotable (same versions as morning-checks/index.php #362 era) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <style>
        .container {
            height: calc(100vh - 48px);
            overflow: hidden;
            max-width: none;
            margin: 0;
            padding: 16px 24px;
            display: flex;
            flex-direction: column;
        }

        .at-toolbar {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 12px;
            flex-shrink: 0;
        }
        .at-toolbar .at-search {
            flex: 1 1 280px;
            max-width: 360px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .at-toolbar .at-search:focus {
            outline: none;
            border-color: #0078d4;
            box-shadow: 0 0 0 2px rgba(0,120,212,0.15);
        }
        .at-toolbar .at-count {
            color: #666;
            font-size: 13px;
            margin-left: auto;
        }
        .at-toolbar .at-btn {
            padding: 8px 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            color: #333;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .at-toolbar .at-btn:hover {
            border-color: #0078d4;
            color: #0078d4;
        }
        .at-toolbar .at-btn svg {
            flex-shrink: 0;
        }

        /* The table itself sits in a flex-grow, scrolling region so the
           toolbar stays put while only the grid scrolls. */
        .at-wrap {
            flex-grow: 1;
            overflow: auto;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            min-height: 0;
        }
        table.at-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }
        .at-table thead th {
            position: sticky;
            top: 0;
            background: #f5f7fa;
            z-index: 2;
            border-bottom: 2px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            padding: 0;
            text-align: left;
            color: #333;
            font-weight: 600;
            white-space: nowrap;
        }
        .at-table thead th:last-child { border-right: none; }
        .at-th-content {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 12px;
            cursor: pointer;
            user-select: none;
        }
        .at-th-content:hover { background: #eef2f7; }
        .at-th-label { flex: 1; }
        .at-sort-arrow {
            font-size: 11px;
            color: #999;
            line-height: 1;
        }
        .at-th-content.sorted .at-sort-arrow { color: #0078d4; }
        /* Filter button on header — opens the per-column Excel-style dropdown */
        .at-filter-btn {
            width: 18px;
            height: 18px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #999;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
        }
        .at-filter-btn:hover { background: #dde4ee; color: #333; }
        .at-filter-btn.active { color: #0078d4; }
        .at-table tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0f0f0;
            border-right: 1px solid #f5f5f5;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 320px;
        }
        .at-table tbody td:last-child { border-right: none; }
        .at-table tbody tr:hover { background: #fafbfc; }
        .at-table tbody tr.at-clickable { cursor: pointer; }

        /* Drag-reorder affordance */
        .at-table thead th.at-dragging { opacity: 0.4; }
        .at-table thead th.at-drag-over { background: #e8f0fe; }

        /* Per-column filter dropdown */
        .at-filter-pop {
            position: absolute;
            z-index: 1000;
            background: #fff;
            border: 1px solid #d0d7e1;
            border-radius: 6px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            min-width: 220px;
            max-width: 320px;
            padding: 10px 12px;
            font-size: 13px;
        }
        .at-filter-pop .at-pop-search {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 8px;
        }
        .at-filter-pop .at-pop-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
        }
        .at-filter-pop .at-pop-actions a {
            color: #0078d4;
            cursor: pointer;
            text-decoration: none;
        }
        .at-filter-pop .at-pop-actions a:hover { text-decoration: underline; }
        .at-filter-pop .at-pop-list {
            max-height: 280px;
            overflow-y: auto;
            margin-bottom: 10px;
            border-top: 1px solid #eee;
            padding-top: 6px;
        }
        .at-filter-pop .at-pop-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 2px;
            cursor: pointer;
        }
        .at-filter-pop .at-pop-item:hover { background: #f5f7fa; }
        .at-filter-pop .at-pop-item input { margin: 0; }
        .at-filter-pop .at-pop-item .at-pop-value {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .at-filter-pop .at-pop-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 6px;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
        .at-filter-pop .at-pop-buttons button {
            padding: 5px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            font-size: 12px;
            cursor: pointer;
        }
        .at-filter-pop .at-pop-buttons .at-pop-apply {
            background: #0078d4;
            color: #fff;
            border-color: #0078d4;
        }

        /* Columns customisation drawer */
        .at-cols-pop {
            position: absolute;
            z-index: 1000;
            background: #fff;
            border: 1px solid #d0d7e1;
            border-radius: 6px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            min-width: 240px;
            max-height: 70vh;
            overflow-y: auto;
            padding: 12px 14px;
            font-size: 13px;
        }
        .at-cols-pop h4 {
            margin: 0 0 8px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        .at-cols-pop .at-cols-hint {
            color: #888;
            font-size: 11px;
            margin-bottom: 10px;
        }
        .at-cols-pop .at-cols-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 6px;
            border-radius: 3px;
            cursor: grab;
        }
        .at-cols-pop .at-cols-item:hover { background: #f5f7fa; }
        .at-cols-pop .at-cols-item.dragging { opacity: 0.4; }
        .at-cols-pop .at-cols-item.drag-over { background: #e8f0fe; }
        .at-cols-pop .at-cols-drag {
            color: #aaa;
            cursor: grab;
            font-size: 14px;
            line-height: 1;
        }

        /* Empty state */
        .at-empty {
            padding: 60px 20px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="at-toolbar">
            <input type="text" id="atSearch" class="at-search" placeholder="Search across visible columns..." autocomplete="off">

            <button type="button" class="at-btn" id="atColumnsBtn" title="Choose visible columns and drag to reorder">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
                Columns
            </button>

            <button type="button" class="at-btn" id="atResetBtn" title="Clear all filters, sort and search">Reset</button>

            <button type="button" class="at-btn" onclick="atExportCSV()" title="Download visible rows as CSV">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                CSV
            </button>

            <button type="button" class="at-btn" onclick="atExportPDF()" title="Download visible rows as PDF (selectable text)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                PDF
            </button>

            <span class="at-count" id="atCount"></span>
        </div>

        <div class="at-wrap">
            <table class="at-table" id="atTable">
                <thead id="atHead"></thead>
                <tbody id="atBody">
                    <tr><td colspan="20" class="at-empty">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../assets/js/asset-table.js?v=1"></script>
</body>
</html>
