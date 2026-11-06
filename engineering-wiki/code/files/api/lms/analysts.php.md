# api/lms/analysts.php

> API endpoint - 22 lines

LMS API: Get all active analysts (for group member selection)

## The endpoint

| | |
|---|---|
| URL | `api/lms/analysts.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

