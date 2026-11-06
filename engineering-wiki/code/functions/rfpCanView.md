# rfpCanView()

May this analyst see RFP `$id`? False for one that does not exist - the same answer, deliberately.

```php
function rfpCanView(PDO $conn, int $analystId, int $rfpId): bool
```

Defined in [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id; 0 or less gives false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `rfpCanView` | read [rfps](/code/tables/rfps.md) |  |

## Calls

[rfpVisibilitySql()](/code/functions/rfpVisibilitySql.md)

## Called from

- [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md)
- [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md)
- [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md)
- [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md)
- [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md)
- [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md)
- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpGate()](/code/functions/rfpGate.md)

