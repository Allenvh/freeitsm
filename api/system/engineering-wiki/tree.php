<?php
/**
 * API: one folder of the Engineering wiki page tree (the sidebar opens folder by folder).
 * GET ?dir=code/tables   (empty = the top: handbook, code)
 * Returns: { success, dirs: [{path, name}], pages: [{page, title}] }
 *
 * @input  dir  a folder path from the wiki root
 */
session_start(['read_and_close' => true]);
require_once '../../../config.php';
require_once '../../../includes/admin_api_guard.php';   // administrators only
require_once '../../../includes/engineering_wiki/store.php';

header('Content-Type: application/json');
echo json_encode(['success' => true] + ewListDir((string)($_GET['dir'] ?? '')));
