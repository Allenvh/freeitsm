# api/system/get_carddav_write_log.php

> API endpoint - 83 lines

API: the write-back log for one CardDAV address book. GET ?provider_id=N[&limit=N]

Every attempt to send a contact back — succeeded, refused, or failed — newest
first, with the server's own response kept verbatim.

🔑 The raw response is returned deliberately. When an operator says "it is not
writing", the reason is almost always in the DAV server's error body, and
FreeITSM's paraphrase of it is no use to somebody debugging a server this
install cannot log in to. System admins only, like every other endpoint on
this screen.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_carddav_write_log.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET provider_id`, `GET limit` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 50 |  | read [carddav_write_log](/code/tables/carddav_write_log.md), read [analysts](/code/tables/analysts.md) |  |
| 72 |  | read [carddav_write_log](/code/tables/carddav_write_log.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

