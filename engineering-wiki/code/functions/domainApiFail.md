# domainApiFail()

Sends {success: false, error} as JSON, with an HTTP status when one other than 200 is given, and ends the request.

```php
function domainApiFail(string $message, int $status = 200): void
```

Defined in [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), line 60.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the error text, shown to the user as it stands |
| `$status` | `int` | `200` | HTTP status code; 200 leaves the status alone |

**Returns:** `void`

## Called from

- [api/domains/auth_code.php](/code/files/api/domains/auth_code.php.md)
- [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md)
- [api/domains/bulk_update.php](/code/files/api/domains/bulk_update.php.md)
- [api/domains/delete.php](/code/files/api/domains/delete.php.md)
- [api/domains/get.php](/code/files/api/domains/get.php.md)
- [api/domains/links.php](/code/files/api/domains/links.php.md)
- [api/domains/people.php](/code/files/api/domains/people.php.md)
- [api/domains/save.php](/code/files/api/domains/save.php.md)
- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [api/domains/watch.php](/code/files/api/domains/watch.php.md)
- [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md) - in [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md), [domainApiRun()](/code/functions/domainApiRun.md)

