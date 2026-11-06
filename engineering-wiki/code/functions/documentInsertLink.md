# documentInsertLink()

Insert one document_links row, setting show_in_help_centre when the column exists. The one place a link is written, so the default above cannot be forgotten by a new caller.

```php
function documentInsertLink(
    PDO $conn,
    int $documentId,
    string $parentType,
    int $parentId,
    ?int $linkedById,
    bool $ignoreDuplicate = false
): void
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 811.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$documentId` | `int` |  | documents.id |
| `$parentType` | `string` |  | the record type, a key of `documentEntityRegistry()` |
| `$parentId` | `int` |  | that record's id |
| `$linkedById` | `?int` |  | the analyst making the link; null for the system |
| `$ignoreDuplicate` | `bool` | `false` | true = INSERT IGNORE (attaching twice is a no-op) |

**Returns:** `void`

## Calls

[documentHelpCentreReady()](/code/functions/documentHelpCentreReady.md), [documentLinkHelpCentreDefault()](/code/functions/documentLinkHelpCentreDefault.md)

## Called from

- [api/documents/attach.php](/code/files/api/documents/attach.php.md)
- [api/documents/save.php](/code/files/api/documents/save.php.md)

