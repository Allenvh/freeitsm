# api/contracts/get_payment_schedules.php

> API endpoint - 30 lines

API Endpoint: Get payment schedules

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_payment_schedules.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | read [payment_schedules](/code/tables/payment_schedules.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

