<?php
/**
 * The case report for one ticket and its linked tasks (GH #204): ?id=<ticket id>.
 *
 * Gated on Tickets, then the ticket's company (a ticket the reader cannot see reads as not
 * found). Each task, asset, document and form answer inside is checked again by CaseReport.
 * Opening with ?print=1 records the export on the ticket's audit trail and opens the print
 * dialog; changing the choices on screen records nothing.
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
requireModuleAccess('tickets');

$conn = connectToDatabase();
$viewer = (int)$_SESSION['analyst_id'];
$r = CaseReport::forTicket($conn, (int)($_GET['id'] ?? 0), $viewer, CaseReport::options($_GET));
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
