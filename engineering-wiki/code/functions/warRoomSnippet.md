# warRoomSnippet()

A window of the message around the first term that matched, so a result is readable without opening it. Plain text — the page inserts it with textContent and highlights client-side, so nothing here can emit markup.

```php
function warRoomSnippet(string $body, array $terms, int $len = 200): string
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1121.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `string` |  | the message text |
| `$terms` | `array` |  | the search words; the window centres near the first one found ([] for the start) |
| `$len` | `int` | `200` | the most characters to return, before the ellipses |

**Returns:** `string`

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomMyMentions()](/code/functions/warRoomMyMentions.md), [warRoomSearch()](/code/functions/warRoomSearch.md)

