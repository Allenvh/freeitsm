<?php
/**
 * The case report for one task on its own, with its subtasks (GH #204): ?id=<task id>.
 * For work that has no ticket - a ticket's tasks are reported with the ticket.
 *
 * Gated on Tasks, then the task's company (a task the reader cannot see reads as not found).
 * Opening with ?print=1 records the export on the task's history and opens the print dialog.
 *
 * @wiki Case-Report
 */
session_start();
require_once '../config.php';
require_once '../includes/functions.php';
require_once '../includes/i18n.php';
require_once '../includes/timezone.php';
require_once '../includes/rbac.php';
require_once '../includes/branding.php';
require_once '../includes/system_name.php';
require_once '../includes/case_report.php';
require_once '../includes/case_report_page.php';
I18n::initFromSession();
Tz::init();
requireModuleAccess('tasks');

$conn = connectToDatabase();
$viewer = (int)$_SESSION['analyst_id'];
$r = CaseReport::forTask($conn, (int)($_GET['id'] ?? 0), $viewer, CaseReport::options($_GET));
if (!$r) http_response_code(404);

$print = $r && !empty($_GET['print']);
$recordError = null;
if ($print) {
    try {
        CaseReport::recordExport($conn, $r, $viewer);
    } catch (Throwable $e) {
        $recordError = $e->getMessage();
    }
}
caseReportPage($r, $print, $recordError);
