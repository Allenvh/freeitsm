# leaseEndpoint()

Runs one Leases endpoint: checks sign-in, Assets module access, the HTTP method and that the lease tables exist, then calls `$fn` and echoes its result as JSON.

A ServiceError is shown to the caller as its message; any other exception is logged
and reported as a generic error.

```php
function leaseEndpoint(string $method, callable $fn): void
```

Defined in [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md), line 30.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | 'GET' or 'POST' - anything else is refused with HTTP 405 |
| `$fn` | `callable` |  | function (PDO `$conn`, ActorContext `$ctx`, array `$in`): ?array - its array is merged into {success: true} |

**Returns:** `void`

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [leaseReady()](/code/functions/leaseReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

## Called from

- [api/assets/leases/asset_remove.php](/code/files/api/assets/leases/asset_remove.php.md)
- [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md)
- [api/assets/leases/asset_update.php](/code/files/api/assets/leases/asset_update.php.md)
- [api/assets/leases/assets_add.php](/code/files/api/assets/leases/assets_add.php.md)
- [api/assets/leases/delete.php](/code/files/api/assets/leases/delete.php.md)
- [api/assets/leases/due_back.php](/code/files/api/assets/leases/due_back.php.md)
- [api/assets/leases/for_asset.php](/code/files/api/assets/leases/for_asset.php.md)
- [api/assets/leases/get.php](/code/files/api/assets/leases/get.php.md)
- [api/assets/leases/list.php](/code/files/api/assets/leases/list.php.md)
- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/assets/leases/save.php](/code/files/api/assets/leases/save.php.md)
- [api/assets/leases/schedule_delete.php](/code/files/api/assets/leases/schedule_delete.php.md)
- [api/assets/leases/schedule_save.php](/code/files/api/assets/leases/schedule_save.php.md)

