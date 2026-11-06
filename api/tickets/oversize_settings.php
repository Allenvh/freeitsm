<?php
/**
 * API Endpoint: what an inbound email over the attachment size limits does - the
 * install DEFAULTS (GH #170). Tickets -> Settings -> Mailboxes.
 *
 *   GET   the defaults, plus the limits themselves (read-only here - they are set
 *         in System -> Security -> Attachments, and the screen says so)
 *   POST  {mode, strip_order, alert, alert_to, autoreply, autoreply_text}
 *
 * A mailbox's own rules are saved with the mailbox (save_mailbox.php,
 * oversize_policy). See includes/attachment_limits.php for what each one means.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/attachment_limits.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_MAILBOXES);   // the same tab as the mailboxes

try {
    $conn = connectToDatabase();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $in = json_decode(file_get_contents('php://input'), true) ?: [];
        $p = oversizeNormalisePolicy($in);
        $save = [
            'oversize_mode'           => $p['mode'],
            'oversize_strip_order'    => $p['strip_order'],
            'oversize_alert'          => $p['alert'] ? '1' : '0',
            'oversize_alert_to'       => $p['alert_to'],
            'oversize_autoreply'      => $p['autoreply'] ? '1' : '0',
            'oversize_autoreply_text' => $p['autoreply_text'],
        ];
        if ($p['alert'] && $p['alert_to'] === '') {
            echo json_encode(['success' => false, 'error' => 'Give at least one valid address for the alert, or switch the alert off.']);
            exit;
        }
        $st = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)
                              ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        foreach ($save as $k => $v) $st->execute([$k, $v]);
        echo json_encode(['success' => true]);
        exit;
    }

    $r = oversizeRules($conn, null);
    echo json_encode(['success' => true, 'settings' => [
        'mode'            => $r['mode'],
        'strip_order'     => $r['strip_order'],
        'alert'           => $r['alert'],
        'alert_to'        => $r['alert_to'],
        'autoreply'       => $r['autoreply'],
        'autoreply_text'  => $r['autoreply_text'],
        'default_reply'   => oversizeDefaultReplyText(),
        // The limits, for display only - System -> Security -> Attachments owns them.
        'max_mb'          => (int)round($r['max_bytes'] / 1048576),
        'max_total_mb'    => (int)round($r['max_total_bytes'] / 1048576),
    ]]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
