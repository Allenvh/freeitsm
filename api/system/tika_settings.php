<?php
/**
 * API: read, save and test the Apache Tika connection (discussion #53, tier 2).
 *
 * Three actions on one endpoint, because they are one screen's worth of
 * behaviour: `get`, `save`, `test`.
 *
 * ⚠️ There are no credentials here, which is unusual for an integration and is
 * the whole reason Tika must not be exposed to a network. It has no
 * authentication of any kind: anything that can reach it will parse whatever
 * bytes it is sent. The screen says so; this endpoint stores a URL and nothing
 * more, so there is no secret to encrypt.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/search/tika.php';
require_once '../../includes/search/extract_queue.php';
require_once '../../includes/admin_api_guard.php';   // administrators only

header('Content-Type: application/json');

/**
 * Write one system_settings row.
 *
 * @param string $key  the setting_key to write, e.g. TIKA_SETTING_URL
 * @param string $value  the value to store
 */
function tikaSettingPut(PDO $conn, string $key, string $value): void {
    $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value)
         VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    )->execute([$key, $value]);
}

try {
    $conn   = connectToDatabase();
    $in     = json_decode(file_get_contents('php://input'), true) ?: [];
    $action = (string)($in['action'] ?? $_GET['action'] ?? 'get');

    if ($action === 'get') {
        echo json_encode([
            'success'  => true,
            'url'      => tikaUrl($conn),
            'timeout'  => tikaTimeout($conn),
            'pending'  => extractQueueDepth($conn),
            'defaults' => ['timeout' => TIKA_TIMEOUT_DEFAULT,
                           'min' => TIKA_TIMEOUT_MIN, 'max' => TIKA_TIMEOUT_MAX],
        ]);
        exit;
    }

    if ($action === 'test') {
        // Tests what is TYPED, not what is saved, so an administrator can check
        // an address before committing to it.
        $res = tikaPing($conn, (string)($in['url'] ?? ''));
        echo json_encode(['success' => true, 'ok' => $res['ok'], 'detail' => $res['detail']]);
        exit;
    }

    if ($action === 'save') {
        // ?action=save on a GET would save an empty address - which switches Tika
        // off - for any administrator who followed a link (csrf.php).
        csrfRequireWriteMethod();
        $url = trim((string)($in['url'] ?? ''));

        // An empty address is a legitimate save: it switches tier 2 off.
        if ($url !== '') {
            if (!preg_match('~^https?://~i', $url)) {
                echo json_encode(['success' => false, 'error' => 'The address must start with http:// or https://']);
                exit;
            }
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                echo json_encode(['success' => false, 'error' => 'That does not look like a valid address']);
                exit;
            }
        }

        $timeout = (int)($in['timeout'] ?? TIKA_TIMEOUT_DEFAULT);
        $timeout = max(TIKA_TIMEOUT_MIN, min(TIKA_TIMEOUT_MAX, $timeout ?: TIKA_TIMEOUT_DEFAULT));

        tikaSettingPut($conn, TIKA_SETTING_URL, rtrim($url, '/'));
        tikaSettingPut($conn, TIKA_SETTING_TIMEOUT, (string)$timeout);

        // Turning an extractor ON makes previously unreadable files readable.
        // Rather than leave them marked `unsupported` until something happens to
        // touch their ticket, hand them to the queue so the normal draining
        // picks them up. Nothing is re-read that was read successfully.
        //
        // ⚠️ ONLY the ones this extractor actually handles. Requeueing every
        // `unsupported` row indiscriminately puts files Tika is never asked
        // about — a .ogg voice recording, an .html file — into a queue that can
        // never clear them, where they sit forever making the depth look wrong.
        // The extension test has to happen in PHP because the supported list is
        // a PHP constant, not something SQL can be asked about.
        $requeued = 0;
        if ($url !== '') {
            try {
                $cand = $conn->prepare(
                    "SELECT t.attachment_id, a.filename
                       FROM attachment_text t
                       JOIN email_attachments a ON a.id = t.attachment_id
                      WHERE t.status = ?"
                );
                $cand->execute([ATT_TEXT_UNSUPPORTED]);

                $ids = [];
                foreach ($cand->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    if (tikaHandles((string)$row['filename'])) $ids[] = (int)$row['attachment_id'];
                }
                if ($ids) {
                    $in = implode(',', array_fill(0, count($ids), '?'));
                    $up = $conn->prepare("UPDATE attachment_text SET status = ? WHERE attachment_id IN ($in)");
                    $up->execute(array_merge([ATT_TEXT_PENDING], $ids));
                    $requeued = $up->rowCount();
                }
            } catch (Exception $e) { /* table absent — nothing to requeue */ }
        }

        echo json_encode([
            'success'  => true,
            'url'      => rtrim($url, '/'),
            'timeout'  => $timeout,
            'requeued' => $requeued,
            'pending'  => extractQueueDepth($conn),
        ]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
