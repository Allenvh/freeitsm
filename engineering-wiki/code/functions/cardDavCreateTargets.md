# cardDavCreateTargets()

Address books this person could be added to right now, and why not if none.

```php
function cardDavCreateTargets(PDO $conn, array $user): array
```

Defined in [includes/carddav_create.php](/code/files/includes/carddav_create.php.md), line 52.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$user` | `array` |  | the users row - auth_provider_id, email and tenant_id are read |

**Returns:** array ['books' => [provider rows], 'reason' => '' | why nothing is offered]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 | `cardDavCreateTargets` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 67 | `cardDavCreateTargets` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Called from

- [api/tickets/address_book_add.php](/code/files/api/tickets/address_book_add.php.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)

