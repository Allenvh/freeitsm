# ewSearch()

Search every page for words. Titles count more than bodies; up to `$limit` hits.

```php
function ewSearch(string $q, int $limit = 60): array
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 189.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$q` | `string` |  | the search text as typed; split on spaces, every word must match, under 2 characters gives [] |
| `$limit` | `int` | `60` | the most hits to return |

**Returns:** array<int,array{page:string,title:string,snippet:string}>

## Calls

[ewRoot()](/code/functions/ewRoot.md), [ewSafePath()](/code/functions/ewSafePath.md), [ewTitleOf()](/code/functions/ewTitleOf.md)

*Nothing calls this function by name.*

