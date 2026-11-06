# contractTenantOf()

The company a contract belongs to (NULL resolved to Default), or null if there is no such contract.

```php
function contractTenantOf(PDO $conn, int $contractId): ?int
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | contracts.id to look up |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 | `contractTenantOf` | read [contracts](/code/tables/contracts.md) |  |

## Calls

[contractTenantReady()](/code/functions/contractTenantReady.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetLink()](/code/functions/contractAssetLink.md), [contractAssetSearch()](/code/functions/contractAssetSearch.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::assertContractVisible`

