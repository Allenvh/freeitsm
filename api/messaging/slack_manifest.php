<?php
/**
 * API Endpoint: the Slack app manifest for one channel.
 *
 * GET slack_manifest.php?channel=<id>
 *
 * Returns the JSON a person pastes into Slack's "Create an app → From a
 * manifest" flow, the webhook URL baked into it, and — if this install is not
 * reachable from the internet — a plain sentence saying so BEFORE they spend ten
 * minutes discovering it from Slack's own error message.
 *
 * ⚠️ Read-only and returns NO secrets. It contains the public webhook URL and
 * the scope list, both of which are visible in Slack afterwards anyway.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/messaging/messaging.php';
require_once '../../includes/messaging/slack_manifest.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();
    $channelId = (int) ($_GET['channel'] ?? 0);

    // Same two doors as the other channel endpoints — see
    // messagingAdminMayAdministerChannel().
    if (!messagingAdminMayAdministerChannel($conn, $channelId)) {
        requireModuleAccessJson('tickets');
        requireCapabilityJson(Cap::TICKETS_MESSAGING);
    }

    $channel = loadMessagingChannel($conn, $channelId);
    if (!$channel || MessagingProviderKind::tryFrom((string)($channel['provider'] ?? '')) !== MessagingProviderKind::Slack) {
        echo json_encode(['success' => false, 'error' => 'Slack channel not found']);
        exit;
    }

    $webhookUrl = messagingWebhookUrl($conn, $channelId);

    echo json_encode([
        'success'     => true,
        'webhook_url' => $webhookUrl,
        'manifest'    => slackManifestJson($webhookUrl, (string) ($channel['name'] ?? 'FreeITSM')),
        // '' when it looks usable; otherwise the reason Slack will reject it.
        'url_problem' => slackWebhookUrlProblem($webhookUrl),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
