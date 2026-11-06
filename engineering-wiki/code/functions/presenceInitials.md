# presenceInitials()

"Sarah Williams" -> "SW". Mirrors the header avatar in includes/waffle-menu.php.

```php
function presenceInitials(string $fullName): string
```

Defined in [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fullName` | `string` |  | the analyst's display name; blank gives '?' |

**Returns:** `string`

## Called from

- [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md) - in [presenceOthers()](/code/functions/presenceOthers.md)

