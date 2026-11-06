# provided()

Treat blank or all-asterisk values as "unchanged" so masked secrets aren't wiped.

```php
function provided($v): bool
```

Defined in [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md), line 43.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | a submitted secret field; blank or all asterisks (the masked value) means unchanged |

**Returns:** `bool`

## Called from

- [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md)

