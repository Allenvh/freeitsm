# api/war-room/channels.php

> API endpoint - 125 lines

API: War room — create, rename, archive a channel, or open a DM.

POST JSON { action: 'create'|'update'|'archive'|'restore'|'dm', … }

🔑 ANY ANALYST MAY CREATE A CHANNEL OR OPEN A DM. Needing an administrator to
make you a room is precisely the wrong dependency during an incident, and the
whole module exists for the moment the usual tools are unavailable. What is
gated is only the retention decision, over in settings.

Renaming and archiving are limited to the person who created the channel or to
war_room.manage, so an incident room cannot be renamed out from under the
people using it. Team and all-hands channels cannot be renamed or archived at
all: their identity is derived, and the way to remove one is to remove the team.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/channels.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [warRoomMayManage()](/code/functions/warRoomMayManage.md) | 39 | Who may reshape this channel: its creator, or someone with war_room.manage. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomChannel()](/code/functions/warRoomChannel.md), [warRoomChannelList()](/code/functions/warRoomChannelList.md), [warRoomCreateChannel()](/code/functions/warRoomCreateChannel.md), [warRoomDirectory()](/code/functions/warRoomDirectory.md), [warRoomOpenDm()](/code/functions/warRoomOpenDm.md), [warRoomSetArchived()](/code/functions/warRoomSetArchived.md), [warRoomUpdateChannel()](/code/functions/warRoomUpdateChannel.md)

