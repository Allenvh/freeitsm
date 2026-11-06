# searchMinTokenSize()

The shortest word this server will index. Read from the server rather than assumed, because it is not the same everywhere — WAMP ships 0, stock MySQL is 3 — and requiring a term below it in boolean mode returns NOTHING AT ALL rather than ignoring it. That is the difference between "search works" and "search mysteriously returns nothing for that phrase".

```php
function searchMinTokenSize(PDO $conn): int
```

Defined in [includes/search/search.php](/code/files/includes/search/search.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Called from

- [api/system/search_status.php](/code/files/api/system/search_status.php.md)
- [api/tickets/search_content.php](/code/files/api/tickets/search_content.php.md)
- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchCorpusQuery()](/code/functions/searchCorpusQuery.md)

