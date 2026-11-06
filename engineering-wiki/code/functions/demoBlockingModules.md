# demoBlockingModules()

Which other demo modules are holding a table down.

Clearing a module's rows runs with foreign key checks ON (turning them off is
what orphaned the SSO links in #1296), so a demo row another module still
points at cannot be deleted — re-importing Core while the Tickets demo data
exists fails, because demo tickets reference demo requesters.

Refusing is correct; refusing without saying what is in the way is not. This
finds the demo tables that reference `$table` and still hold demo rows, and
names the modules they belong to so the message can tell the administrator
what to deal with first.

```php
function demoBlockingModules(PDO $conn, string $table, string $exceptModule): array
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 147.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the table whose demo rows could not be cleared |
| `$exceptModule` | `string` |  | the DEMO_MODULES key being imported, left out of the answer |

**Returns:** `array`

## Calls

[demoModuleTables()](/code/functions/demoModuleTables.md)

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)

