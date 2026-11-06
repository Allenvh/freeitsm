# managerAccessRender()

Print the page body (below the module header). Call once, inside <body>.

```php
function managerAccessRender(int $managerId, string $backUrl): void
```

Defined in [includes/manager_access_page.php](/code/files/includes/manager_access_page.php.md), line 26.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$managerId` | `int` |  | the manager's users.id (the portal user whose access lines are shown) |
| `$backUrl` | `string` |  | where the Back button goes; escaped when printed |

**Returns:** `void`

## Calls

[t()](/code/functions/t.md)

## Called from

- [asset-management/manager-access.php](/code/files/asset-management/manager-access.php.md)
- [tickets/manager-access.php](/code/files/tickets/manager-access.php.md)

