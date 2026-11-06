# managerLinesEditable()

May the signed-in analyst add or remove management lines? System -> Managers decides: administrators only (the default), or anyone who can edit people - the caller has already required Tickets or Assets. Being able to reach the manager (analystCanAccessUser) is checked by the caller as well.

```php
function managerLinesEditable(PDO $conn): bool
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 306.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[managersSettings()](/code/functions/managersSettings.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md)

## Called from

- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)

