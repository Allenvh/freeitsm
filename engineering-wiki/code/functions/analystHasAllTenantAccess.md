# analystHasAllTenantAccess()

Can this analyst reach EVERY company on the install?

The gate for anything that affects all companies at once, such as a shared
asset location: editing one changes what every client sees, so only someone
who can already see every client may do it. Compared against the real tenant
list rather than the all-access flag alone, because access can also arrive
through teams (see getAccessibleTenantIds).

```php
function analystHasAllTenantAccess(PDO $conn, int $analystId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 862.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md)
- [api/assets/delete_lease_outcome.php](/code/files/api/assets/delete_lease_outcome.php.md)
- [api/assets/get_asset_locations.php](/code/files/api/assets/get_asset_locations.php.md)
- [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md)
- [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md)

