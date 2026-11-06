# api/assets/leases/due_back.php

> API endpoint - 31 lines

Assets -> Leases -> Due back (3.5.0): every asset still out on lease whose end date falls within the next ?days= (0 = everything still out; overdue ones always included), in the active company or all of them. ?lease_id= narrows it to one lease. ?format=csv downloads it instead.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/due_back.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET format`, `GET days`, `GET lease_id` |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md), [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [leaseDueBack()](/code/functions/leaseDueBack.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseReady()](/code/functions/leaseReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [spreadsheetWriteCsv()](/code/functions/spreadsheetWriteCsv.md)

