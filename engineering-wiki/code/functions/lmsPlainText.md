# lmsPlainText()

HTML (a knowledge article, a lesson body) down to the plain text the model should read.

```php
function lmsPlainText(?string $html, int $limit = 12000): string
```

Defined in [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$html` | `?string` |  | the HTML to flatten; null is treated as empty |
| `$limit` | `int` | `12000` | the most characters to return |

**Returns:** `string`

## Called from

- [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md)

