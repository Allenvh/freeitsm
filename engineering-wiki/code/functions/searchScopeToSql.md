# searchScopeToSql()

Translate the scope structure into SQL. The ONLY place this happens.

```php
function searchScopeToSql(array $scope, string $alias = 'sd'): array
```

Defined in [includes/search/search.php](/code/files/includes/search/search.php.md), line 192.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$scope` | `array` |  | the scope structure built by `searchScopeForAnalyst()` |
| `$alias` | `string` | `'sd'` | the alias the calling query gives search_documents |

**Returns:** array{0:string,1:array} [sql fragment, params]

## Called from

- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchCorpusQuery()](/code/functions/searchCorpusQuery.md)

