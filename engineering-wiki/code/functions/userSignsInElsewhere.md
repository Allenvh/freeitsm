# userSignsInElsewhere()

Does this person sign in through their linked provider rather than with a FreeITSM password?

🔴 `auth_provider_id > 0` IS NOT THE QUESTION. Five portal paths (sign-in,
register, email confirmation, forgotten password, reset) used to treat any
linked provider as "signs in elsewhere" - which was true while only LDAP and
OIDC could be linked. A CardDAV address book is linked too, and it is a
source of contact details, NOT a way to sign in. So every contact imported
from an address book was told "this account signs in with single sign-on"
and could never use the portal at all, whatever password they were given.

Only a CardDAV link counts as local. Anything else - LDAP, OIDC, a provider
this code does not recognise, or one that has since been deleted - keeps the
old, safe answer: the person signs in elsewhere, and no local password may
be planted on the account.

```php
function userSignsInElsewhere(PDO $conn, $providerId): bool
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 175.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` |  |  | the person's users.auth_provider_id; null or 0 means not linked |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 180 | `userSignsInElsewhere` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Called from

- [api/self-service/login.php](/code/files/api/self-service/login.php.md)
- [api/self-service/register.php](/code/files/api/self-service/register.php.md)
- [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md)
- [self-service/verify-email.php](/code/files/self-service/verify-email.php.md)

