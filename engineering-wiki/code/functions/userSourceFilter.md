# userSourceFilter()

The SQL for the Source filter. Expects auth_providers joined as `ap`.

```php
function userSourceFilter($source): array
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 202.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$source` |  |  | '' = everyone, 'local' = linked to nothing, or a provider id |

**Returns:** array{0:string,1:array}

## Called from

- [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::people`

