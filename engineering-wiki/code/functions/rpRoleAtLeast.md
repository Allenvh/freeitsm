# rpRoleAtLeast()

True if `$role` is at least `$need` ('view' < 'edit' < 'owner').

```php
function rpRoleAtLeast(?string $role, string $need): bool
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 119.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$role` | `?string` |  | the viewer's role from `rpRole()`: 'owner', 'edit', 'view' or null |
| `$need` | `string` |  | the least role required: 'view', 'edit' or 'owner' |

**Returns:** `bool`

## Called from

- [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md)

