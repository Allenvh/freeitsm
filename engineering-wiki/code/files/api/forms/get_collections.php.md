# api/forms/get_collections.php

> API endpoint - 45 lines

API: List form collections, with their paired forms.

Also reports whether the schema is present at all, so the settings tab can
say "run DB Verification" rather than showing an empty list that looks like
a working feature nobody has used yet.

## The endpoint

| | |
|---|---|
| URL | `api/forms/get_collections.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

