# dsyncDnList()

Split a stored DN list into an array. One per line, blanks dropped.

Lower-cased, because a DN is case-insensitive but string comparison is not,
and every comparison in this file (is X under Y?) is a string comparison.

```php
function dsyncDnList(?string $raw): array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `?string` |  | the stored newline-separated DN list, e.g. sync_ou_includes; null gives [] |

**Returns:** `array`

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncScopes()](/code/functions/dsyncScopes.md)

