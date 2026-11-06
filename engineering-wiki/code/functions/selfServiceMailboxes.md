# selfServiceMailboxes()

The active mailboxes a self-service user may raise a ticket through - their own company's and the shared ones. Used for the dropdown and to re-check the posted choice.

```php
function selfServiceMailboxes(PDO $conn, int $userId): array
```

Defined in [includes/self_service_mailboxes.php](/code/files/includes/self_service_mailboxes.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the signed-in self-service user (users.id); their company decides the list |

**Returns:** array<int,array{id:int,name:string,target_mailbox:string,shared:bool}> keyed by mailbox id, the user's own company's first, then shared.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `selfServiceMailboxes` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 40 | `selfServiceMailboxes` | read [users](/code/tables/users.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/get_mailboxes.php](/code/files/api/self-service/get_mailboxes.php.md)

