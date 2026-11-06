<?php
/**
 * API Endpoint: the public web address used in links FreeITSM sends out.
 *
 * Read by the Email Templates settings tab, which needs three different facts and
 * not one:
 *   - `base_url`      what an administrator typed, so the field can show it;
 *   - `is_configured` whether anything was typed AT ALL, which is the only thing
 *                     that can be trusted from cron — this is what decides whether
 *                     [ticket_url] gets a warning;
 *   - `effective_url` what a link would actually come out as right now, which on
 *                     this request includes the host header and so looks fine even
 *                     when nothing is configured. Shown as an example, never used
 *                     to decide whether to warn.
 *
 * The third one is the trap this endpoint exists to avoid: an admin looking at a
 * perfectly good sample URL would reasonably conclude everything is set up, when
 * the same link built at 3am by the mail collector would have no host in it.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/public_url.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// The setting is install-wide, but this is the tab it is surfaced on, and editing
// email templates is already an administrative capability. Nothing here is secret:
// it is the address the install answers on, which every recipient of a link can see.
requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_EMAIL_TEMPLATES);

try {
    $conn = connectToDatabase();

    $stmt = $conn->prepare("SELECT setting_key, setting_value FROM system_settings
                             WHERE setting_key IN ('public_base_url','public_base_url_warning_dismissed','template_scope_warning_dismissed')");
    $stmt->execute();
    $rows = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $rows[$r['setting_key']] = trim((string)$r['setting_value']);
    }

    // Show what is actually in force, not just what this key holds. An install that
    // configured web chat before this setting existed IS configured — reporting an
    // empty field there would say "nothing is set" about an install where links
    // work perfectly, which is the same wrong answer in the opposite direction to
    // the warning this endpoint exists to drive.
    $own       = $rows['public_base_url'] ?? '';
    $effective = publicBaseUrlSetting($conn);

    echo json_encode([
        'success'           => true,
        'base_url'          => $effective,
        // True when the value on show came from the messaging setting rather than
        // this one, so the screen can say where it is coming from instead of
        // appearing to have a value it does not own.
        'inherited'         => $own === '' && $effective !== '',
        'is_configured'     => $effective !== '',
        'warning_dismissed' => ($rows['public_base_url_warning_dismissed'] ?? '') === '1',
        'scope_warning_dismissed' => ($rows['template_scope_warning_dismissed'] ?? '') === '1',
        'effective_url'     => publicAbsoluteUrl($conn, 'self-service/tickets.php?id=1'),
        // The root on its own, so the body preview can show a sample link on THIS
        // install's address rather than an invented one. Every other sample value is
        // fiction and can be; the host is the one fact the preview exists to confirm.
        'effective_root'    => publicBaseUrl($conn),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
