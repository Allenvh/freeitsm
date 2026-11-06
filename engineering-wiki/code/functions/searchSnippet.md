# searchSnippet()

A readable fragment of the body around the first matching term.

MySQL has no highlighting function, so this is done here. Returns PLAIN TEXT —
the caller escapes it. Never build HTML in this file; a snippet is user
content and the one place it must not be trusted is on its way to a page.

```php
function searchSnippet(string $body, array $parsed, int $len = 220): string
```

Defined in [includes/search/search.php](/code/files/includes/search/search.php.md), line 366.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `string` |  | the matched document's plain-text body |
| `$parsed` | `array` |  | the parsed query from `searchParseQuery()`; its phrases and terms locate the excerpt |
| `$len` | `int` | `220` | the most characters to return, before any ellipses |

**Returns:** `string`

## Called from

- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchCorpusQuery()](/code/functions/searchCorpusQuery.md)

