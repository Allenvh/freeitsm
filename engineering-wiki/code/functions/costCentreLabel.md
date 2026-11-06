# costCentreLabel()

"CODE Name", or '' - one display form everywhere.

```php
function costCentreLabel(?array $ref): string
```

Defined in [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ref` | `?array` |  | a cost centre as `costCentreRef()` returns it; null gives '' |

**Returns:** `string`

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseGet()](/code/functions/leaseGet.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::auditDisplay`

