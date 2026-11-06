# mfaThrottleMinutesRemaining()

Is this account's code step locked right now? Returns whole minutes remaining, or 0 when it is free to try. Degrades to 0 when the columns are absent.

```php
function mfaThrottleMinutesRemaining(PDO $conn, string $table, int $accountId): int
```

Defined in [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), line 115.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the account table: 'analysts' or 'users' |
| `$accountId` | `int` |  | the account's id in that table; 0 or less always gives 0 |

**Returns:** `int`

## Calls

[mfaThrottleTable()](/code/functions/mfaThrottleTable.md), [mfaThrottleWarnOnce()](/code/functions/mfaThrottleWarnOnce.md)

## Called from

- [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md)
- [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)

