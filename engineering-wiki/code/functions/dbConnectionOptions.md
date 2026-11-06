# dbConnectionOptions()

Returns the PDO options every connection uses - the session time zone set to UTC.

```php
function dbConnectionOptions(): array
```

Defined in [includes/db.php](/code/files/includes/db.php.md), line 64.

**Returns:** `array`

## Called from

- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [api/auth/reset_password.php](/code/files/api/auth/reset_password.php.md)
- [api/system/debug-tools/D001_demo_core_import.php](/code/files/api/system/debug-tools/D001_demo_core_import.php.md)
- [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md)
- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md)
- [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md)
- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)
- [includes/functions.php](/code/files/includes/functions.php.md) - in [connectToDatabase()](/code/functions/connectToDatabase.md)

