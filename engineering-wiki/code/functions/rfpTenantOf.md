# rfpTenantOf()

The company an RFP belongs to (NULL read as Default), or null if there is no such RFP.

```php
function rfpTenantOf(PDO $conn, int $rfpId): ?int
```

Defined in [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `rfpTenantOf` | read [rfps](/code/tables/rfps.md) |  |
| 63 | `rfpTenantOf` | read [rfps](/code/tables/rfps.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [rfpTenantReady()](/code/functions/rfpTenantReady.md)

## Called from

- [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)

