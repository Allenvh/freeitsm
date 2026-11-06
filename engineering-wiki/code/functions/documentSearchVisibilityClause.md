# documentSearchVisibilityClause()

The SQL that stops search returning a document you may not see.

⚠️ THIS IS THE HALF THAT MAKES THE FEATURE SAFE. Corpus rows carry a tenant and
an internal flag, which is enough for tickets. It is not enough for a document,
whose visibility depends on records in other tables entirely — so a document
row must additionally satisfy the same at-least-one-visible-parent rule the
download endpoint applies.

Expressed as "not a document, OR a visible document", so it can be ANDed onto
the existing search WHERE without disturbing any other source type.

```php
function documentSearchVisibilityClause(
    PDO $conn,
    int $analystId,
    ?array $allowedModules,
    string $alias = 'sd'
): array
```

Defined in [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), line 284.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$allowedModules` | `?array` |  | the analyst's allowed module keys (`$_SESSION`['allowed_modules']); null means all |
| `$alias` | `string` | `'sd'` | the alias the search query gives the search_documents table |

**Returns:** array [sqlFragment, params]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 293 | `documentSearchVisibilityClause` | read [documents](/code/tables/documents.md) |  |

## Calls

[documentVisibilityClause()](/code/functions/documentVisibilityClause.md)

## Called from

- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchScopeForAnalyst()](/code/functions/searchScopeForAnalyst.md)

