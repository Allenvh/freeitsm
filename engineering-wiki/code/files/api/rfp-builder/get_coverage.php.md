# api/rfp-builder/get_coverage.php

> API endpoint - 156 lines

Coverage heatmap data: which departments contributed requirements to which categories. The cell value is the number of CONSOLIDATED requirements in (category, department) — i.e. consolidated rows with at least one source extracted requirement that came from a document tagged with that department.

One consolidated row can count toward multiple departments if its
source items spanned departments — that's the entire point of the
consolidation pass and the heatmap reflects it.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_coverage.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 49 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 63 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 92 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 109 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 136 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

