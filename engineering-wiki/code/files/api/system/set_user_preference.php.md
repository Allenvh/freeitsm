# api/system/set_user_preference.php

> API endpoint - 56 lines

API: Set a single user preference for the current analyst POST { key, value } Upserts on (analyst_id, preference_key). Pass null/empty value to clear.

## The endpoint

| | |
|---|---|
| URL | `api/system/set_user_preference.php` |
| Protected by | Signed-in analyst (session check) (via includes/csrf.php) |
| Called from | [assets/js/calendar.js](/code/files/assets/js/calendar.js.md), [assets/js/knowledge.js](/code/files/assets/js/knowledge.js.md), [assets/js/lms-my-courses.js](/code/files/assets/js/lms-my-courses.js.md), [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md), [assets/js/process-mapper.js](/code/files/assets/js/process-mapper.js.md), [assets/js/tasks-calendar.js](/code/files/assets/js/tasks-calendar.js.md), [assets/js/tasks.js](/code/files/assets/js/tasks.js.md), [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/landing.php](/code/files/includes/landing.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [landingOnPreferenceSaved()](/code/functions/landingOnPreferenceSaved.md)

