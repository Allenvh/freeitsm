# self-service/includes/user-menu.php

> PHP - 995 lines

Self-Service Portal - User Menu (Avatar + Dropdown + Modals) Include this in the portal-header of every authenticated page. Requires `$ss_user_name` to be set (from auth.php).

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

**Loads scripts:** `self-service/assets/js/qrcode.min.js`

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [t()](/code/functions/t.md)

