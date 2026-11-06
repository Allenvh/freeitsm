# mfaThrottleTable()

Guard the table name. Returns the safe literal or null. Interpolating a caller-supplied table into SQL is how a helper like this turns into an injection point, so the name never travels — only the choice does.

```php
function mfaThrottleTable(string $table): ?string
```

Defined in [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), line 65.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$table` | `string` |  | the account table asked for - only 'analysts' or 'users' pass |

**Returns:** `?string`

## Called from

- [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md) - in [mfaThrottleMinutesRemaining()](/code/functions/mfaThrottleMinutesRemaining.md), [mfaThrottleRecordFailure()](/code/functions/mfaThrottleRecordFailure.md), [mfaThrottleReset()](/code/functions/mfaThrottleReset.md)

