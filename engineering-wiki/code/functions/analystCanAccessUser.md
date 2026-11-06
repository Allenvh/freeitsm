# analystCanAccessUser()

May this analyst act on this *end user / requester* (by their owning company)?

⚠️ This was the missing half of a real cross-company hole. api/tickets/save_user.php
validated the DESTINATION company an account was being moved into, but never
checked whether the account being edited was one the caller could reach at all —
so an analyst scoped to company A could set the email address and password on
company B's portal account, and then sign in as them. Guarding the destination
without guarding the subject guards nothing.

Same rules as `analystCanAccessTicket()`: single-company install → true; NULL
tenant is Default-owned; unknown id → false.

```php
function analystCanAccessUser(PDO $conn, int $analystId, $userId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 307.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$userId` |  |  | the portal user / requester (users.id), any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 316 | `analystCanAccessUser` | read [users](/code/tables/users.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/tickets/address_book_add.php](/code/files/api/tickets/address_book_add.php.md)
- [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md)
- [api/tickets/delete_user.php](/code/files/api/tickets/delete_user.php.md)
- [api/tickets/get_person.php](/code/files/api/tickets/get_person.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maManager()](/code/functions/maManager.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [api/tickets/unlink_user_signin.php](/code/files/api/tickets/unlink_user_signin.php.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [personDetail()](/code/functions/personDetail.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::assetsForUser`

