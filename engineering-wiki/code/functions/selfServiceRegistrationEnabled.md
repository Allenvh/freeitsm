# selfServiceRegistrationEnabled()

Is self-service self-registration allowed? Defaults to FALSE (disabled) — an install must deliberately turn it on under System → Security. Off by default because open sign-up is an attack surface most desks don't need; when on, the email-confirmation flow (register.php) still applies.

```php
function selfServiceRegistrationEnabled(PDO $conn): bool
```

Defined in [includes/self_service.php](/code/files/includes/self_service.php.md), line 12.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 13 | `selfServiceRegistrationEnabled` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/self-service/register.php](/code/files/api/self-service/register.php.md)
- [self-service/login.php](/code/files/self-service/login.php.md)
- [self-service/register.php](/code/files/self-service/register.php.md)

