# mfaThrottleThreshold()

How many wrong codes before the code step locks?

Follows the administrator's account-lockout policy so there is one number to
reason about — but with a floor, because max_failed_logins = 0 means "do not
lock accounts on bad passwords", and reading that as "allow unlimited MFA code
guessing" would reinstate the bug through a settings screen.

```php
function mfaThrottleThreshold(PDO $conn): int
```

Defined in [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), line 95.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Calls

[mfaThrottleSetting()](/code/functions/mfaThrottleSetting.md)

## Called from

- [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md) - in [mfaThrottleRecordFailure()](/code/functions/mfaThrottleRecordFailure.md)

