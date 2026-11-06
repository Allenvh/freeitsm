# war-room/index.php

> PHP - 228 lines

War room — chat that still works when Teams, Slack and the internet do not.

⚠️ NO EXTERNAL ASSETS ON THIS PAGE, EVER. Everything it loads must come from
this server. A page whose whole purpose is "the internet is down" cannot pull
a script from a CDN, a font from Google, or anything else that needs a name
to resolve beyond the LAN. Four other pages in the app do use cdnjs; this one
must not join them. The situation report is the single exception and it is an
outbound API call from the SERVER, made only when somebody asks for it — the
page itself still loads nothing.

The channel list is rendered server-side for first paint and then refreshed by
the same poll that fetches messages. See includes/warroom.php for what a
channel actually is, which differs per kind.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 |  | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md), [war-room/includes/header.php](/code/files/war-room/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/war-room.js](/code/files/assets/js/war-room.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md), [warRoomChannelList()](/code/functions/warRoomChannelList.md), [warRoomDirectory()](/code/functions/warRoomDirectory.md)

