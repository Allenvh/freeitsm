<?php
/**
 * API: search every Engineering wiki page.
 * GET ?q=lease outcome
 * Returns: { success, results: [{page, title, snippet}] }   (every word must appear; titles rank first)
 *
 * @input  q  the words to look for (2 characters or more)
 */
session_start(['read_and_close' => true]);
require_once '../../../config.php';
require_once '../../../includes/admin_api_guard.php';   // administrators only
require_once '../../../includes/engineering_wiki/store.php';

header('Content-Type: application/json');
echo json_encode(['success' => true, 'results' => ewSearch((string)($_GET['q'] ?? ''))]);
