# documentHelpCentreReady()

Has Database Verification added document_links.show_in_help_centre yet?

Every writer of document_links asks this first, because naming a column that
is not there yet would break EVERY document upload on EVERY module between
pulling the code and running verification - not just the new feature.

```php
function documentHelpCentreReady(PDO $conn): bool
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 774.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 778 | `documentHelpCentreReady` | read [document_links](/code/tables/document_links.md) |  |

## Called from

- [api/documents/help_centre.php](/code/files/api/documents/help_centre.php.md)
- [api/documents/list.php](/code/files/api/documents/list.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentInsertLink()](/code/functions/documentInsertLink.md)
- [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md) - in [helpCentreArticleDocuments()](/code/functions/helpCentreArticleDocuments.md), [helpCentreDocumentCounts()](/code/functions/helpCentreDocumentCounts.md), [helpCentreDocumentForPortalUser()](/code/functions/helpCentreDocumentForPortalUser.md), [helpCentreSetShown()](/code/functions/helpCentreSetShown.md)

