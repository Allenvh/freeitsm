# rpProjectChoices()

The projects offered by a block's "Projects" option: those the viewer can see, live first.

```php
function rpProjectChoices(PDO $conn, int $analystId): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 280.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 286 | `rpProjectChoices` | read [projects](/code/tables/projects.md) |  |

## Calls

[allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectCode()](/code/functions/projectCode.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md)

## Called from

- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpOptionSource()](/code/functions/rpOptionSource.md)

