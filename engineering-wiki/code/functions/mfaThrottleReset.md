# mfaThrottleReset()

A correct code was entered: clear the count and any lock.

⚠️ This is the ONLY thing that clears it. In particular the password step must
never call it — that is precisely the reset the attack relied on.

```php
function mfaThrottleReset(PDO $conn, string $table, int $accountId): void
```

Defined in [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), line 194.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the account table: 'analysts' or 'users' |
| `$accountId` | `int` |  | the account's id in that table |

**Returns:** `void`

## Calls

[mfaThrottleTable()](/code/functions/mfaThrottleTable.md), [mfaThrottleWarnOnce()](/code/functions/mfaThrottleWarnOnce.md)

## Called from

- [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md)
- [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)

