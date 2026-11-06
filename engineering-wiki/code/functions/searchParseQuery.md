# searchParseQuery()

Turn what a person typed into a MySQL boolean-mode query.

The user-facing language is deliberately tiny — words, "quoted phrases" and
-exclusions — so that it can be translated for a different engine later
without the syntax having leaked into the UI.

⚠️ Terms too short to be indexed are DROPPED, not passed through. Requiring
one would make the whole query match nothing. They come back in `dropped` so
the caller can say so rather than silently returning an empty page.

```php
function searchParseQuery(string $raw, int $minToken = 3): array
```

Defined in [includes/search/search.php](/code/files/includes/search/search.php.md), line 65.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the search text as the user typed it - untrusted |
| `$minToken` | `int` | `3` | the shortest word MySQL indexes (innodb_ft_min_token_size); shorter words are dropped |

**Returns:** array{expr:string,terms:array,phrases:array,excluded:array,dropped:array}

## Called from

- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchCorpusQuery()](/code/functions/searchCorpusQuery.md)

