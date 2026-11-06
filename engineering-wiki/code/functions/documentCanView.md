# documentCanView()

May this analyst see this document? The download/preview boundary.

⚠️ THIS IS THE CHECK THAT MATTERS. Search deciding what to LIST is a
convenience; this decides what somebody may HAVE. It must be called on every
download, preview and metadata read, and must never trust that the caller
found the id through a filtered list — /download.php?id=12345 is the attack,
and guessing an integer is not hard.

```php
function documentCanView(PDO $conn, int $analystId, ?array $allowedModules, int $documentId): bool
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 484.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$allowedModules` | `?array` |  | the analyst's allowed module keys (`$_SESSION`['allowed_modules']); null means all |
| `$documentId` | `int` |  | the documents.id asked for - untrusted, often straight from the URL |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 489 | `documentCanView` | read [document_links](/code/tables/document_links.md) |  |

## Calls

[documentCanViewParent()](/code/functions/documentCanViewParent.md)

## Called from

- [api/documents/attach.php](/code/files/api/documents/attach.php.md)
- [api/documents/download.php](/code/files/api/documents/download.php.md)
- [api/documents/help_centre.php](/code/files/api/documents/help_centre.php.md)
- [api/documents/links.php](/code/files/api/documents/links.php.md)
- [api/documents/unlink.php](/code/files/api/documents/unlink.php.md)
- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)

