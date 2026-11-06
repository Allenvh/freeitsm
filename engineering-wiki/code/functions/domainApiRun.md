# domainApiRun()

Run a service call and turn any failure into the UI's {success:false} shape.

```php
function domainApiRun(callable $fn): void
```

Defined in [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), line 110.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fn` | `callable` |  | the endpoint's work - takes no arguments and normally ends with `domainApiOk()` |

**Returns:** `void`

## Calls

[domainApiFail()](/code/functions/domainApiFail.md)

## Called from

- [api/domains/accounts.php](/code/files/api/domains/accounts.php.md)
- [api/domains/auth_code.php](/code/files/api/domains/auth_code.php.md)
- [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md)
- [api/domains/bulk_update.php](/code/files/api/domains/bulk_update.php.md)
- [api/domains/dashboard.php](/code/files/api/domains/dashboard.php.md)
- [api/domains/delete.php](/code/files/api/domains/delete.php.md)
- [api/domains/get.php](/code/files/api/domains/get.php.md)
- [api/domains/links.php](/code/files/api/domains/links.php.md)
- [api/domains/list.php](/code/files/api/domains/list.php.md)
- [api/domains/lookups.php](/code/files/api/domains/lookups.php.md)
- [api/domains/people.php](/code/files/api/domains/people.php.md)
- [api/domains/process.php](/code/files/api/domains/process.php.md)
- [api/domains/save.php](/code/files/api/domains/save.php.md)
- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [api/domains/statuses.php](/code/files/api/domains/statuses.php.md)
- [api/domains/tick.php](/code/files/api/domains/tick.php.md)
- [api/domains/watch.php](/code/files/api/domains/watch.php.md)

