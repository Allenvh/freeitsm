# api/self-service/save_theme.php

> API endpoint - 45 lines

API: save the signed-in portal user's colour palette.

Analysts keep their palette in `user_preferences`, which is keyed by
analyst_id and therefore unusable for portal users — so a portal user's
choice lives on their own row (`users.theme_preference`). Theme::active()
reads it, with the session as a per-request cache.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/save_theme.php` |
| Protected by | Signed-in analyst (session check) (via includes/theme.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | **update** [users](/code/tables/users.md) (`theme_preference`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

