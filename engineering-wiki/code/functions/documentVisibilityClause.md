# documentVisibilityClause()

SQL constraining a document query to what this analyst may see.

Builds one EXISTS per accessible entity type, OR'd together — which is
literally the agreed rule: visible if you can see AT LEAST ONE parent. The
database evaluates it as a join and stops at the first match; nothing is
fetched and then discarded in PHP.

⚠️ Returns ' AND 0=1' when the analyst can reach no entity type at all. An
empty string would mean "no constraint", i.e. every document in the system.

```php
function documentVisibilityClause(
    PDO $conn,
    int $analystId,
    ?array $allowedModules,
    string $docAlias = 'd'
): array
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 428.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$allowedModules` | `?array` |  | the analyst's allowed module keys (`$_SESSION`['allowed_modules']); null means all |
| `$docAlias` | `string` | `'d'` | the alias the calling query gives the documents table; '' for no alias |

**Returns:** array [sqlFragment, params] — the [sql, params] shape every other filter in includes/tenancy.php uses.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 443 | `documentVisibilityClause` | read [document_links](/code/tables/document_links.md) |  |

## Calls

[documentAccessibleTypes()](/code/functions/documentAccessibleTypes.md), [documentEntityDef()](/code/functions/documentEntityDef.md)

## Called from

- [api/documents/find.php](/code/files/api/documents/find.php.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentSearchVisibilityClause()](/code/functions/documentSearchVisibilityClause.md)

