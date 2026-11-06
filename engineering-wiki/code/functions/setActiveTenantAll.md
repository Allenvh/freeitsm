# setActiveTenantAll()

Turn the consolidated view on or off. Needs a writable session.

```php
function setActiveTenantAll(bool $on): void
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 679.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$on` | `bool` |  | true for the "All companies" view, false for one company |

**Returns:** `void`

## Called from

- [api/system/set_active_tenant.php](/code/files/api/system/set_active_tenant.php.md)

