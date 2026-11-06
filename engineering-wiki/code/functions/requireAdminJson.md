# requireAdminJson()

Hard gate for System JSON APIs: refuse non-admins with a 403. Authoritative (DB-checked) so a just-demoted analyst can't keep acting on a stale session. Call right after connecting, e.g. requireAdminJson(`$conn`);

```php
function requireAdminJson(PDO $conn): void
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 264.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[analystIsAdmin()](/code/functions/analystIsAdmin.md)

## Called from

- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)
- [api/workflow/save_payload_retention.php](/code/files/api/workflow/save_payload_retention.php.md)
- [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md)

