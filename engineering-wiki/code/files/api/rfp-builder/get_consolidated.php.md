# api/rfp-builder/get_consolidated.php

> API endpoint - 112 lines

Return the full consolidated tree for an RFP — categories, consolidated requirements with their source quotes/departments, and conflicts.

Powers the consolidate.php page. The page renders flat in 3a (no
grouping/expansion) and is enhanced in 3b (group by category, expand
source quotes inline, badges, etc.). Same shape works for both.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_consolidated.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 46 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 63 |  | read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 87 |  | read [rfp_conflicts](/code/tables/rfp_conflicts.md), read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

