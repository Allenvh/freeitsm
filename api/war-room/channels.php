<?php
/**
 * API: War room — create, rename, archive a channel, or open a DM.
 *
 * POST JSON { action: 'create'|'update'|'archive'|'restore'|'dm', … }
 *
 * 🔑 ANY ANALYST MAY CREATE A CHANNEL OR OPEN A DM. Needing an administrator to
 * make you a room is precisely the wrong dependency during an incident, and the
 * whole module exists for the moment the usual tools are unavailable. What is
 * gated is only the retention decision, over in settings.
 *
 * Renaming and archiving are limited to the person who created the channel or to
 * war_room.manage, so an incident room cannot be renamed out from under the
 * people using it. Team and all-hands channels cannot be renamed or archived at
 * all: their identity is derived, and the way to remove one is to remove the team.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';
require_once '../../includes/rbac.php';
require_once '../../includes/warroom.php';

header('Content-Type: application/json');

requireModuleAccessJson('war-room');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'POST required']);
    exit;
}

/**
 * Who may reshape this channel: its creator, or someone with war_room.manage.
 *
 * @param array $channel  the channel row as loaded by warRoomChannel() - only created_by is read here
 */
function warRoomMayManage(PDO $conn, int $analystId, array $channel): bool
{
    if ((int) ($channel['created_by'] ?? 0) === $analystId) return true;
    return analystHasCapability($conn, $analystId, Cap::WAR_ROOM_MANAGE);
}

try {
    $conn      = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];
    I18n::initFromSession();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) $input = [];
    $action = (string) ($input['action'] ?? '');

    switch ($action) {
        case 'create':
            $id = warRoomCreateChannel(
                $conn,
                $analystId,
                (string) ($input['name'] ?? ''),
                (string) ($input['topic'] ?? ''),
                !empty($input['is_private']),
                is_array($input['members'] ?? null) ? $input['members'] : []
            );
            echo json_encode(['success' => true, 'channel_id' => $id]);
            break;

        case 'dm':
            $id = warRoomOpenDm($conn, $analystId, (int) ($input['analyst_id'] ?? 0));
            echo json_encode(['success' => true, 'channel_id' => $id]);
            break;

        case 'update':
        case 'archive':
        case 'restore':
            $channelId = (int) ($input['channel_id'] ?? 0);
            $channel   = warRoomChannel($conn, $channelId);

            // Access first, then authority. Answering "you may not manage that"
            // for a channel you cannot even see would confirm it exists.
            if ($channel === null || !warRoomCanAccessChannel($conn, $analystId, $channelId)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'No access to that channel']);
                exit;
            }
            if ($channel['kind'] !== WARROOM_KIND_CUSTOM) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'That channel cannot be changed']);
                exit;
            }
            if (!warRoomMayManage($conn, $analystId, $channel)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Not yours to change']);
                exit;
            }

            if ($action === 'update') {
                warRoomUpdateChannel($conn, $channelId, (string) ($input['name'] ?? ''), (string) ($input['topic'] ?? ''));
            } else {
                warRoomSetArchived($conn, $channelId, $action === 'archive');
            }
            echo json_encode(['success' => true]);
            break;

        case 'list':
            echo json_encode([
                'success'   => true,
                'channels'  => warRoomChannelList($conn, $analystId, !empty($input['include_archived'])),
                'directory' => warRoomDirectory($conn, $analystId),
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
} catch (InvalidArgumentException $e) {
    // The message is a key, not prose — the page turns it into a sentence in the
    // reader's language rather than showing an English string from the server.
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not update channels']);
}
