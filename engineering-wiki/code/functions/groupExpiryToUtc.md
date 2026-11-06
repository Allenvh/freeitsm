# groupExpiryToUtc()

"Access until 14 September" → the UTC instant that day ends.

🔑 `expires_at` IS AN INSTANT, not a bare date: visibility.php compares it to
`UTC_TIMESTAMP()`. So a picked calendar day is the fourth kind of stored date —
a wall clock that has to be converted before it is stored (GH #116, GH #126).
The zone is the installation's, set by config.php, which is the same frame
`naive_now()` reads local "now" in.

End of the chosen day, not the start of it: an admin who types the 14th means
the 14th is their last day, and expiring them at midnight would take the access
away a day early — on the morning of the day they were told they still had.

```php
function groupExpiryToUtc(string $date): ?string
```

Defined in [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md), line 450.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$date` | `string` |  | the last day of access as YYYY-MM-DD in the install's time zone; empty means no expiry |

**Returns:** string|null NULL for "no expiry" (a blank box), and also for a date that cannot be read — the caller separates the two.

## Called from

- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md) - in [handleAddMember()](/code/functions/handleAddMember.md)

