# projectApiFail()

Sends a failure JSON reply ({success: false, error}) with an optional HTTP status, and ends the request.

```php
function projectApiFail(string $message, int $status = 200): void
```

Defined in [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), line 59.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the error text sent as 'error' |
| `$status` | `int` | `200` | the HTTP status to send; 200 leaves it alone |

**Returns:** `void`

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/projects/links.php](/code/files/api/projects/links.php.md)
- [api/projects/reports.php](/code/files/api/projects/reports.php.md)
- [api/projects/settings.php](/code/files/api/projects/settings.php.md)
- [api/projects/templates.php](/code/files/api/projects/templates.php.md)
- [api/projects/tools.php](/code/files/api/projects/tools.php.md)
- [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md) - in [projectApiRequirePost()](/code/functions/projectApiRequirePost.md), [projectApiTenantForCreate()](/code/functions/projectApiTenantForCreate.md), [projectApiRun()](/code/functions/projectApiRun.md)

