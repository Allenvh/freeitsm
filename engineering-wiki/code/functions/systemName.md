# systemName()

The system's name from System -> Branding, or "Service Desk" when it is not set. Cached per request.

```php
function systemName(): string
```

Defined in [includes/system_name.php](/code/files/includes/system_name.php.md), line 19.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `systemName` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [api/mcp/index.php](/code/files/api/mcp/index.php.md)
- [includes/system_name.php](/code/files/includes/system_name.php.md) - in [pageTitle()](/code/functions/pageTitle.md)
- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksContractorEmail()](/code/functions/tasksContractorEmail.md)
- [lms/test.php](/code/files/lms/test.php.md)

