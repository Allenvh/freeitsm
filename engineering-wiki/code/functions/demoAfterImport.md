# demoAfterImport()

After a module's rows are in, inside the import's transaction (3.3.0).

tasks: a demo project baseline is a snapshot of the plan (includes/projects/
control.php) holding stage and milestone IDS, which only exist once inserted,
so the JSON gives each baseline a recipe instead -
  snapshot: {"_demo": {"target_shift": -14, "budget_delta": -4500, "stage_shift": -10}}
- and this takes the project's plan as imported and moves it by that much:
the plan as it stood before the change request that moved it.

```php
function demoAfterImport(PDO $conn, string $module): void
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$module` | `string` |  | the DEMO_MODULES key just imported, e.g. 'tasks'; only 'tasks' does anything |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 | `demoAfterImport` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 45 | `demoAfterImport` | **update** [project_baselines](/code/tables/project_baselines.md) (`snapshot`, `start_date`, `target_end_date`, `budget_planned`, `task_count`, `estimate_hours`, `must_count`) |  |

## Calls

[projectPlanSnapshot()](/code/functions/projectPlanSnapshot.md)

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)

