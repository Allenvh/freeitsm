# backLinkHtml()

The link's HTML for a header ('portal' or 'analyst'), or '' when it is not set up, not meant for this side, or its address is not a plain http(s) one.

```php
function backLinkHtml(PDO $conn, string $side): string
```

Defined in [includes/back_link.php](/code/files/includes/back_link.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$side` | `string` |  | which header is asking - 'portal' or 'analyst' (also used in the link's class name) |

**Returns:** `string`

## Calls

[backLinkSettings()](/code/functions/backLinkSettings.md), [backLinkUrlValid()](/code/functions/backLinkUrlValid.md)

## Called from

- [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) - in [renderHeaderRight()](/code/functions/renderHeaderRight.md)
- [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

