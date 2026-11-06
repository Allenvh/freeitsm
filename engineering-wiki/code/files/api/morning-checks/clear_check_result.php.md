# api/morning-checks/clear_check_result.php

> API endpoint - 46 lines

API: put a check back to "not checked" (discussion #64).

For the everyday case of clicking the wrong status button. See
MorningChecksService::clearResult() for why this removes the row rather than
nulling its status, and for what else goes with it.

⚠️ No ownership check, deliberately — consistent with saving a result. Who a
check is routed to is guidance, so correcting somebody else's mis-click is
exactly as allowed as doing their check for them while they are off.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/clear_check_result.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

