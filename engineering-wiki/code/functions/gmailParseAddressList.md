# gmailParseAddressList()

Parse a comma-separated address list into Graph-like format.

```php
function gmailParseAddressList(string $raw): array
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 504.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | a To or Cc header value, e.g. '"Smith, J" <j@example.com>, k@example.com' |

**Returns:** `array`

## Called from

- [includes/gmail.php](/code/files/includes/gmail.php.md) - in [gmailGetMessage()](/code/functions/gmailGetMessage.md)

