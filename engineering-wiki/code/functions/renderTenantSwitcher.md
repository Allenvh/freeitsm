# renderTenantSwitcher()

Render the company switcher (button + dropdown) for the header.

```php
function renderTenantSwitcher(PDO $conn, int $analystId): void
```

Defined in [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md), line 24.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | a database connection |
| `$analystId` | `int` |  | the logged-in analyst |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `renderTenantSwitcher` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [getTenantById()](/code/functions/getTenantById.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) - in [renderHeaderRight()](/code/functions/renderHeaderRight.md)

