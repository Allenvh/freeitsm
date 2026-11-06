# includes/demo_data.php

> PHP - 230 lines

Demo data — the one place that knows what a demo module is made of.

Two endpoints need the same answer to "which tables does module X put rows
into?": the importer, to clear its own previous rows before inserting fresh
ones, and the demo data page, to say whether a module has been imported.
They used to answer it separately, and the second answer was wrong — it
asked "does this table have ANY rows?", so an installation with real tickets
or real assets was reported as already having the demo data. Same family of
fault as the importer's own: nothing distinguished demo rows from real ones.

The distinguishing mark is now the `is_demo` column, set on every row the
importer inserts and present on all of the tables listed here.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [demoAfterImport()](/code/functions/demoAfterImport.md) | 38 | After a module's rows are in, inside the import's transaction (3.3.0). |
| [demoDataPath()](/code/functions/demoDataPath.md) | 67 | Returns the path of a module's demo data file, database/demo-data/{module}.json. |
| [demoTablesWithInserts()](/code/functions/demoTablesWithInserts.md) | 82 | The tables a parsed demo module actually INSERTS into. |
| [demoModuleTables()](/code/functions/demoModuleTables.md) | 103 | As above, but reads the module's JSON from disk. Returns [] if unreadable. |
| [demoRowCount()](/code/functions/demoRowCount.md) | 119 | How many demo rows a module currently has in the database. |
| [demoBlockingModules()](/code/functions/demoBlockingModules.md) | 147 | Which other demo modules are holding a table down. |
| [demoDuplicateExplanation()](/code/functions/demoDuplicateExplanation.md) | 189 | Explain a unique-key collision during an import in terms of its actual cause. |
| [demoHasUntaggedImport()](/code/functions/demoHasUntaggedImport.md) | 221 | Does this installation hold demo data from BEFORE the rows were tagged? |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 | `demoAfterImport` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 45 | `demoAfterImport` | **update** [project_baselines](/code/tables/project_baselines.md) (`snapshot`, `start_date`, `target_end_date`, `budget_planned`, `task_count`, `estimate_hours`, `must_count`) |  |
| 223 | `demoHasUntaggedImport` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/projects/control.php](/code/files/includes/projects/control.php.md)

**Included by:** [api/system/check_demo_core.php](/code/files/api/system/check_demo_core.php.md), [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)

**Calls:** [projectPlanSnapshot()](/code/functions/projectPlanSnapshot.md)

