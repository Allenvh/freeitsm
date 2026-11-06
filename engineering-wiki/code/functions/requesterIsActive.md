# requesterIsActive()

Is this requester still active? "Mark as left" (Assets -> Users, Tickets -> Users) and PATCH /api/v1/users/{id} clear users.is_active. A requester who has left cannot sign in to the portal, by any route, and is signed out of an open portal session on their next page (self-service/includes/auth.php). 3.5.0. NULL counts as active, as everywhere else.

```php
function requesterIsActive(PDO $conn, int $userId): bool
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 231.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the requester's users.id; 0 or less, or an unknown id, gives false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 233 | `requesterIsActive` | read [users](/code/tables/users.md) |  |

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) - in [completeSelfServiceSso()](/code/functions/completeSelfServiceSso.md)
- [api/self-service/get_article_document.php](/code/files/api/self-service/get_article_document.php.md)
- [api/self-service/login.php](/code/files/api/self-service/login.php.md)
- [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)
- [self-service/includes/auth.php](/code/files/self-service/includes/auth.php.md)

