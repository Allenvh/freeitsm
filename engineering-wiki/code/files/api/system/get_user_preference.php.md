# api/system/get_user_preference.php

> API endpoint - 40 lines

API: Get a single user preference for the current analyst GET ?key=preference_key Returns: { success, key, value } (value is null if not set)

## The endpoint

| | |
|---|---|
| URL | `api/system/get_user_preference.php` |
| Protected by | Signed-in analyst (session check) |
| Reads | `GET key` |
| Called from | [assets/js/knowledge.js](/code/files/assets/js/knowledge.js.md), [assets/js/lms-my-courses.js](/code/files/assets/js/lms-my-courses.js.md), [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md), [assets/js/process-mapper.js](/code/files/assets/js/process-mapper.js.md), [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

