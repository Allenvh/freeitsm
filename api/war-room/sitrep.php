<?php
/**
 * API: War room — the situation report.
 *
 * POST JSON { since: '<HH:MM>'|'<hours>h', channel_id?: <int> }
 *
 * WHAT THIS IS FOR. During an incident somebody has to send an update to the
 * business every half hour, and they cannot read four hundred messages first.
 * This reads the chat since a time they choose and drafts the briefing: where
 * things stand, what changed, who is doing what, what is still unknown, and a
 * paragraph they could send as-is.
 *
 * ⚠️ THE IRONY IS ACKNOWLEDGED. The war room exists for when the internet is
 * down, and this is the one part of it that needs the internet. So it is strictly
 * additive: the chat never calls it, never waits for it, and does not degrade
 * without it. When the provider is unreachable this returns a plain "could not
 * reach the AI provider" and the room carries on exactly as before. That is also
 * why it is a panel you open rather than something on the page by default.
 *
 * 🔒 The transcript is scoped to the channels the CALLER can read, in SQL — so a
 * service delivery manager's summary can never quote a DM they are not in, which
 * would be a novel way to leak one.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';
require_once '../../includes/ai_settings.php';
require_once '../../includes/warroom.php';

header('Content-Type: application/json');

requireModuleAccessJson('war-room');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'POST required']);
    exit;
}

try {
    $conn      = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];
    I18n::initFromSession();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) $input = [];

    // "Since" is sent as a number of hours rather than a wall-clock time, because
    // the browser and the server can be in different time zones and an off-by-one
    // hour in an incident briefing is worse than no briefing.
    $hours = (float) ($input['hours'] ?? 4);
    if ($hours <= 0 || $hours > 168) $hours = 4;

    $sinceTs    = time() - (int) round($hours * 3600);
    $sinceUtc   = gmdate('Y-m-d H:i:s', $sinceTs);
    // This label goes into the prompt and is quoted back in the report's own
    // headings, so it has to read as English rather than as "1 hours ago".
    if ($hours < 1) {
        $mins       = max(1, (int) round($hours * 60));
        $sinceLabel = $mins === 1 ? '1 minute ago' : $mins . ' minutes ago';
    } elseif (abs($hours - 1.0) < 0.001) {
        $sinceLabel = '1 hour ago';
    } else {
        $sinceLabel = (fmod($hours, 1.0) === 0.0 ? (int) $hours : $hours) . ' hours ago';
    }

    $channelId = (isset($input['channel_id']) && $input['channel_id'] !== '' && $input['channel_id'] !== null)
        ? (int) $input['channel_id'] : null;
    if ($channelId !== null && !warRoomCanAccessChannel($conn, $analystId, $channelId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'No access to that channel']);
        exit;
    }

    $transcript = warRoomTranscriptSince($conn, $analystId, $sinceUtc, $channelId);

    // Nothing to summarise is a normal answer, not a failure — and answering it
    // here saves paying for a call that can only say the same thing.
    if ($transcript['messages'] === 0) {
        echo json_encode([
            'success'  => true,
            'empty'    => true,
            'since'    => $sinceLabel,
            'messages' => 0,
        ]);
        exit;
    }

    $cfg = aiSettingsLoad($conn, 'warroom_ai');
    if (!aiConfigured($cfg)) {
        echo json_encode([
            'success' => false,
            'error'   => 'not_configured',
        ]);
        exit;
    }

    $result = aiProviderChat($cfg, [
        'system'     => 'You write incident briefings for IT service delivery managers. '
                      . 'You are careful, concise and never invent detail.',
        'user'       => warRoomSitrepPrompt($transcript['lines'], $sinceLabel),
        'max_tokens' => 1400,
    ]);

    echo json_encode([
        'success'  => true,
        'report'   => trim((string) ($result['content'] ?? '')),
        'since'    => $sinceLabel,
        'messages' => $transcript['messages'],
        'channels' => $transcript['channels'],
        'model'    => $result['model'] ?? '',
    ]);
} catch (RuntimeException $e) {
    // A provider/network failure. Reported as itself rather than as a 500, so the
    // panel can say "could not reach the AI provider" — which during an outage is
    // very likely the true and expected answer, not a bug in FreeITSM.
    echo json_encode(['success' => false, 'error' => 'provider_unreachable', 'detail' => $e->getMessage()]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not build the report']);
}
