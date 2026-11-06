# api/war-room/message.php

> API endpoint - 74 lines

API: War room — edit or delete one message.

POST JSON { action: 'edit'|'delete', id: <int>, body?: "<text>" }

WHO MAY DO WHAT, AND WHY THEY DIFFER:

  edit    the AUTHOR only. Not an administrator, not the channel's creator.
          Nobody should be able to put words in somebody else's mouth in the
          record of an incident.
  delete  the author, OR somebody with war_room.manage. Removal is a different
          act from rewriting: the case for it is a pasted password or customer
          data, and waiting for the author to come back from lunch is not an
          acceptable answer to that.

Both are recorded rather than silent — see warRoomEditMessage / warRoomDelete-
Message. The content of a deleted message really is destroyed; what survives is
the fact that it was removed, and by whom.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/message.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warRoomDeleteMessage()](/code/functions/warRoomDeleteMessage.md), [warRoomEditMessage()](/code/functions/warRoomEditMessage.md)

