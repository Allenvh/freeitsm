# documentTextEnqueue()

Queue a document for text extraction.

Called after an upload. A link, or a file type nothing can read, is recorded
with a terminal status rather than left out — "we looked and there is nothing
to read" and "we have not looked yet" must not be the same row.

```php
function documentTextEnqueue(PDO $conn, int $documentId, string $kind, ?string $filename): void
```

Defined in [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), line 63.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$documentId` | `int` |  | the documents id |
| `$kind` | `string` |  | the document kind: 'file' or 'link' (DOCUMENT_KIND_*); a link is marked unsupported |
| `$filename` | `?string` |  | the file's original name; null when there is none |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 74 | `documentTextEnqueue` | **insert** [document_text](/code/tables/document_text.md) (`document_id`, `status`, `extracted_datetime`) |  |

## Calls

[documentTextReadable()](/code/functions/documentTextReadable.md)

## Called from

- [api/documents/save.php](/code/files/api/documents/save.php.md)

