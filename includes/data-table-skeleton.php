<?php
/**
 * Shared data-table skeleton (toolbar + table) for the full-screen table views.
 *
 * Renders the standard markup that assets/js/data-table.js drives (fixed dt-*
 * element IDs). Pair with assets/css/data-table.css. A standalone page wraps
 * this in <div class="dt-page">; the tasks module nests it inside its own
 * flex layout instead.
 *
 * Options (set before include):
 *   $dtShowPdf           bool   — show the PDF export button (default false)
 *   $dtShowCsv           bool   — show the CSV export button (default true)
 *   $dtSearchPlaceholder string — search box placeholder
 */
$dtShowPdf = $dtShowPdf ?? false;
$dtShowCsv = $dtShowCsv ?? true;
$dtSearchPlaceholder = $dtSearchPlaceholder ?? 'Search across visible columns...';
?>
<div class="dt-layout">
    <div class="dt-toolbar">
        <input type="text" id="dtSearch" class="dt-search" placeholder="<?php echo htmlspecialchars($dtSearchPlaceholder); ?>" autocomplete="off">
        <button type="button" class="dt-btn" id="dtColumnsBtn" title="Choose visible columns and drag to reorder">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
            Columns
        </button>
        <?php /* Saved views (#96). Present for every table; data-table.js hides
                 it on any table that has not been given a viewsKey, because a
                 button that does nothing is worse than no button. */ ?>
        <button type="button" class="dt-btn" id="dtViewsBtn" title="Saved views: your own, your teams', and shared ones">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
            </svg>
            <span class="dt-views-label">Views</span>
        </button>
        <?php /* Saving is a verb about the table you are looking at; the library
                 is a place you browse. Putting the commoner action behind the
                 rarer one made you open a modal to save (Ed). */ ?>
        <button type="button" class="dt-btn" id="dtSaveViewBtn" title="Save the current columns, sort and filters as a view">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Save view
        </button>
        <button type="button" class="dt-btn" id="dtResetBtn" title="Clear all filters, sort and search">Reset</button>
        <?php if ($dtShowCsv): ?>
        <button type="button" class="dt-btn" id="dtCsvBtn" title="<?php echo htmlspecialchars($dtCsvTitle ?? 'Download visible rows as CSV'); ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            CSV
        </button>
        <?php endif; ?>
        <?php if ($dtShowPdf): ?>
        <button type="button" class="dt-btn" id="dtPdfBtn" title="Download visible rows as PDF (selectable text)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            PDF
        </button>
        <?php endif; ?>
        <span class="dt-count" id="dtCount"></span>
    </div>

    <div class="dt-wrap">
        <table class="dt-table" id="dtTable">
            <thead id="dtHead"></thead>
            <tbody id="dtBody">
                <tr><td colspan="20" class="dt-empty">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>
