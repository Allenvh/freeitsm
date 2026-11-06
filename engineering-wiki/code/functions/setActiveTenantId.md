# setActiveTenantId()

Set the analyst's active company context. The caller must have a writable session (i.e. `session_start()` without read_and_close).

```php
function setActiveTenantId(int $tenantId): void
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 643.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Called from

- [api/system/set_active_tenant.php](/code/files/api/system/set_active_tenant.php.md)

