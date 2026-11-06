# api/rfp-builder/delete_rfp.php

> API endpoint - 59 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Contracts](https://github.com/edmozley/freeitsm/wiki/Contracts)

API: delete an RFP and everything under it.

Child rows go by ON DELETE CASCADE; the uploaded document files are deleted from disk
afterwards. `rfpGate()` answers "not found" for an RFP in a company the analyst cannot
reach.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/delete_rfp.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Input | id  the RFP (JSON body) |
| Output | {success} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [rfp_documents](/code/tables/rfp_documents.md) |  |
| 46 |  | **delete** [rfps](/code/tables/rfps.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

