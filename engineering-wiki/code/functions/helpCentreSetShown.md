# helpCentreSetShown()

Show or hide one article attachment in the Help Centre. Only ever touches a knowledge_article link. Returns false when there is no such link (or the column does not exist yet).

```php
function helpCentreSetShown(PDO $conn, int $documentId, int $articleId, bool $show): bool
```

Defined in [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$documentId` | `int` |  | documents.id |
| `$articleId` | `int` |  | knowledge_articles.id the document is attached to |
| `$show` | `bool` |  | true = downloadable in the Help Centre, false = hidden |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 148 | `helpCentreSetShown` | read [document_links](/code/tables/document_links.md) |  |
| 152 | `helpCentreSetShown` | **update** [document_links](/code/tables/document_links.md) (`show_in_help_centre`) |  |

## Calls

[documentHelpCentreReady()](/code/functions/documentHelpCentreReady.md)

## Called from

- [api/documents/help_centre.php](/code/files/api/documents/help_centre.php.md)

