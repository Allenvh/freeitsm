<?php
/**
 * Debug Tools registry — single source of truth.
 *
 * Each entry powers a searchable card on the Debug Tools landing
 * (system/debug-tools/index.php) and its own dedicated page
 * (system/debug-tools/<slug>/index.php). The diagnostic itself is a
 * self-contained script under api/system/debug-tools/<file> that outputs a
 * single plain-text report.
 *
 * To add a new diagnostic:
 *   1. Drop api/system/debug-tools/Dnnn_short_name.php (plain text, === SECTION === headers).
 *   2. Add an entry below (id, slug, file, title, category, desc, keywords, icon,
 *      when, checks, duration, persists, optional input).
 *   3. Create system/debug-tools/<slug>/index.php (two lines — see d001/index.php).
 */

/** @return array<int,array<string,mixed>> Ordered list of debug tools. */
function getDebugTools() {
    return [
        [
            'id'       => 'D001',
            'slug'     => 'd001',
            'file'     => 'D001_demo_core_import.php',
            'title'    => 'Demo Core Data Import',
            'category' => 'Demo Data',
            'icon'     => 'demo',
            'desc'     => 'Diagnose a failing "Import Core Data" on the Demo Data screen.',
            'keywords' => 'demo data import core seed sample fixtures populate setup d001',
            'when'     => 'Run this when you click "Import Core Data" on the Demo Data screen and it fails, hangs, or appears to do nothing.',
            'checks'   => [
                'PHP version, OS, loaded extensions, session state, memory & post limits',
                'config.php and db_config.php presence + DB credentials defined',
                'Required files: import_demo_data.php, core.json, functions.php',
                'core.json parses and how many records it would import per table',
                'Database connection — server version, database name, character set',
                'Each of the 9 core tables: exists, row count, actual columns vs expected',
                'Write probe — inserts one sentinel row per table inside a rolled-back transaction',
                'Live import attempt — runs the real import in-process and captures the response + any PHP warnings',
            ],
            'duration' => '~2 seconds',
            'persists' => 'The live-import step will populate demo data if it succeeds. Otherwise nothing persists.',
            'input'    => null,
        ],
        [
            'id'       => 'D002',
            'slug'     => 'd002',
            'file'     => 'D002_delete_ticket.php',
            'title'    => 'Delete Ticket (with full SQL trace)',
            'category' => 'Tickets',
            'icon'     => 'ticket',
            'desc'     => 'Delete a ticket the same way the app does, showing every SQL statement — for foreign-key delete errors.',
            'keywords' => 'ticket delete foreign key fk constraint 1451 email_attachments sql trace destructive d002',
            'when'     => 'Run this when deleting a ticket fails with a foreign-key error (e.g. "1451 Cannot delete or update a parent row" on email_attachments). Enter the ticket reference, and it deletes the ticket the same way the app does — but shows every SQL statement and row count so you can see exactly what happened.',
            'input'    => ['name' => 'ref', 'label' => 'Ticket reference', 'placeholder' => 'e.g. the ticket number shown on the ticket'],
            'checks'   => [
                'Resolves the ticket from the reference (ticket_number, or raw id as a fallback)',
                'Audits every table the delete touches: exists, key columns, and each foreign key + its ON DELETE rule',
                'Pinpoints the fk_email_attachments_email constraint (present? blocking?) and lists the exact email ids + attachment ids / filenames / paths that trigger the error',
                'Counts the child rows that will be removed (attachments, emails, notes, audit, time entries, plus the cascade children)',
                'Performs the delete inside a transaction, echoing every DELETE statement, its parameters and rows affected, then COMMIT',
                'Verifies the ticket and its children are gone, and removes the orphaned attachment files from disk',
            ],
            'duration'    => '~1 second',
            'persists'    => 'DESTRUCTIVE — on success the ticket and all its data are permanently deleted. On any error the transaction is rolled back and nothing changes.',
            'destructive' => true,
        ],
    ];
}

/** Find one tool by its slug (e.g. 'd001'). Returns null if not found. */
function getDebugToolBySlug($slug) {
    foreach (getDebugTools() as $tool) {
        if ($tool['slug'] === $slug) return $tool;
    }
    return null;
}

/**
 * Inline SVG markup for a debug-tool icon key. Unknown keys render a neutral
 * wrench so a typo never breaks the page. Sizing/colour come from CSS.
 */
function debugToolIcon($key) {
    $icons = [
        'demo'   => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line>',
        'ticket' => '<polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>',
    ];
    $inner = $icons[$key] ?? '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' . $inner . '</svg>';
}
