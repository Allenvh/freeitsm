# api/system/import_demo_data.php

> API endpoint - 456 lines

API Endpoint: Import Demo Data Imports sample data for a specific module from database/demo-data/{module}.json Accepts POST parameter: module (required)

## The endpoint

| | |
|---|---|
| URL | `api/system/import_demo_data.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `POST module` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getPrimaryKeyColumn()](/code/functions/getPrimaryKeyColumn.md) | 28 | Helper: get primary key column name for a table |
| [resolveReferences()](/code/functions/resolveReferences.md) | 40 | Helper: resolve `@table`.ref references to real IDs |
| [resolveTokens()](/code/functions/resolveTokens.md) | 55 | Helper: resolve special tokens |
| [processMapperDefaultSize()](/code/functions/processMapperDefaultSize.md) | 88 | Helper: pick canvas-default size for a step type |
| [autoLayoutProcessMapper()](/code/functions/autoLayoutProcessMapper.md) | 102 | Auto-layout for the process-mapper module: writes x/y/width/height onto each step in `$demoData`['tier2']['process_steps'] using a left-to-right layered placement. Each process is laid out independently. Edges that point "backwards" in JSON authoring order are treated as cycle-closing back-edges and ignored for ranking. |
| [generateDemoTicketNumber()](/code/functions/generateDemoTicketNumber.md) | 208 | Helper: generate unique ticket number |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 214 | `generateDemoTicketNumber` | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/demo_data.php](/code/files/includes/demo_data.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [demoAfterImport()](/code/functions/demoAfterImport.md), [demoBlockingModules()](/code/functions/demoBlockingModules.md), [demoDuplicateExplanation()](/code/functions/demoDuplicateExplanation.md), [demoTablesWithInserts()](/code/functions/demoTablesWithInserts.md)

