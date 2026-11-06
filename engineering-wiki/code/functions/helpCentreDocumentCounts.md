# helpCentreDocumentCounts()

How many documents each article shows in the Help Centre - for the "N documents" note on the list. Hidden ones are not counted: the list must not promise a file the article will not offer. Same caveat as above: the caller passes only ids the requester may already read.

```php
function helpCentreDocumentCounts(PDO $conn, array $articleIds): array
```

Defined in [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md), line 85.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$articleIds` | `array` |  | knowledge_articles.id values from a scoped list |

**Returns:** array<int,int> article id => count; articles with none are absent

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 | `helpCentreDocumentCounts` | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |

## Calls

[documentHelpCentreReady()](/code/functions/documentHelpCentreReady.md)

## Called from

- [api/self-service/get_knowledge_articles.php](/code/files/api/self-service/get_knowledge_articles.php.md)

