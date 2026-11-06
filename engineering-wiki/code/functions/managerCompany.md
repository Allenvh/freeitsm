# managerCompany()

The company a row belongs to, where NO company (NULL) means the Default company - the convention every company-owned row follows. A ticket nobody has routed and a person nobody has filed are both Default's.

```php
function managerCompany(PDO $conn, $tenantId): int
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 263.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` |  |  | a tenant_id as stored (int, numeric string, null or ''); null or '' means the Default company |

**Returns:** `int`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerReachSql()](/code/functions/managerReachSql.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)

