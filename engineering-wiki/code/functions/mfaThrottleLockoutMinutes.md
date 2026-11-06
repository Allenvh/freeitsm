# mfaThrottleLockoutMinutes()

How long the code step stays locked, in minutes.

```php
function mfaThrottleLockoutMinutes(PDO $conn): int
```

Defined in [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), line 102.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Calls

[mfaThrottleSetting()](/code/functions/mfaThrottleSetting.md)

## Called from

- [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md) - in [mfaThrottleRecordFailure()](/code/functions/mfaThrottleRecordFailure.md)

