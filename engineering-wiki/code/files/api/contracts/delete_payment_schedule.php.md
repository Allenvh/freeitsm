# api/contracts/delete_payment_schedule.php

> API endpoint - 45 lines

API Endpoint: Delete payment schedule

## The endpoint

| | |
|---|---|
| URL | `api/contracts/delete_payment_schedule.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_PAYMENT_SCHEDULES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **update** [contracts](/code/tables/contracts.md) (`payment_schedule_id`) |  |
| 33 |  | read [payment_schedules](/code/tables/payment_schedules.md) |  |
| 34 |  | **delete** [payment_schedules](/code/tables/payment_schedules.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

