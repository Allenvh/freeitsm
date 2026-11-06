# mfaThrottleRecordFailure()

Record one wrong code. Returns: ['locked' => bool, 'minutes' => int, 'attempts' => int, 'threshold' => int, 'tracked' => bool]

`tracked` is false when the columns are missing, so the caller can tell
"counted" from "could not count" rather than reading 0 as a clean slate.

```php
function mfaThrottleRecordFailure(PDO $conn, string $table, int $accountId): array
```

Defined in [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), line 147.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the account table: 'analysts' or 'users' |
| `$accountId` | `int` |  | the account's id in that table; 0 or less counts nothing |

**Returns:** `array`

## Calls

[mfaThrottleLockoutMinutes()](/code/functions/mfaThrottleLockoutMinutes.md), [mfaThrottleTable()](/code/functions/mfaThrottleTable.md), [mfaThrottleThreshold()](/code/functions/mfaThrottleThreshold.md), [mfaThrottleWarnOnce()](/code/functions/mfaThrottleWarnOnce.md)

## Called from

- [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md)
- [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)

