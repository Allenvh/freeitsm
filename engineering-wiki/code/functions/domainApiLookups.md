# domainApiLookups()

Statuses, owners, registrars, accounts, purposes — for filters and forms.

```php
function domainApiLookups(PDO $conn, int $analystId): array
```

Defined in [includes/domains/read.php](/code/files/includes/domains/read.php.md), line 183.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 191 | `domainApiLookups` | read [contracts](/code/tables/contracts.md) |  |
| 195 | `domainApiLookups` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |
| 198 | `domainApiLookups` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 199 | `domainApiLookups` | read [analysts](/code/tables/analysts.md) |  |
| 200 | `domainApiLookups` | read [suppliers](/code/tables/suppliers.md) |  |
| 211 | `domainApiLookups` | read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md) |  |

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [domainPartiesReady()](/code/functions/domainPartiesReady.md), [domainPurposes()](/code/functions/domainPurposes.md), [domainRenewalModes()](/code/functions/domainRenewalModes.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/domains/list.php](/code/files/api/domains/list.php.md)
- [api/domains/lookups.php](/code/files/api/domains/lookups.php.md)

