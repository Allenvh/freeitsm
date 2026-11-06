# mfaThrottleSetting()

One system_settings read, with its own guard. Mirrors `getSecuritySetting()` in auth/login.php.

```php
function mfaThrottleSetting(PDO $conn, string $key): ?string
```

Defined in [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings setting_key to read |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `mfaThrottleSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md) - in [mfaThrottleThreshold()](/code/functions/mfaThrottleThreshold.md), [mfaThrottleLockoutMinutes()](/code/functions/mfaThrottleLockoutMinutes.md)

