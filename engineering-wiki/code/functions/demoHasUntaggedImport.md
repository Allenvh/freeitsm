# demoHasUntaggedImport()

Does this installation hold demo data from BEFORE the rows were tagged?

Installations that imported demo data prior to #1297 have is_demo = 0 on all
of it, so the importer cannot recognise it and a re-import would lay a second
copy alongside the first rather than replacing it. There is no way to tag it
retrospectively — nothing recorded which rows came from where — so the honest
move is to detect the case and say so.

The probe is the demo analyst 'jsmith', which is what the demo data page used
as its "core has been imported" test before this existed. Present but not
tagged means an untagged import.

```php
function demoHasUntaggedImport(PDO $conn): bool
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 221.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 223 | `demoHasUntaggedImport` | read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/system/check_demo_core.php](/code/files/api/system/check_demo_core.php.md)
- [includes/demo_data.php](/code/files/includes/demo_data.php.md) - in [demoDuplicateExplanation()](/code/functions/demoDuplicateExplanation.md)

