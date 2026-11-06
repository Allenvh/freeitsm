<?php
/**
 * API: one Engineering wiki page, rendered.
 * GET ?page=handbook/start
 * Returns: { success, page, title, html, toc, modified, section, generated }
 *
 * @input  page  the page path from the wiki root, without ".md"
 * @output the page rendered to HTML (raw HTML in the file is always escaped)
 */
session_start(['read_and_close' => true]);
require_once '../../../config.php';
require_once '../../../includes/admin_api_guard.php';   // administrators only
require_once '../../../includes/engineering_wiki/store.php';

header('Content-Type: application/json');
$page = ewSafePath((string)($_GET['page'] ?? ''));
$data = $page !== null ? ewReadPage($page) : null;
if ($data === null) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Page not found.']);
    exit;
}
echo json_encode(['success' => true] + $data);
