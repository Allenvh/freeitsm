# documentTextQueueDepth()

How many documents are waiting for their text. Drives the "indexing" state.

```php
function documentTextQueueDepth(PDO $conn): int
```

Defined in [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), line 86.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 89 | `documentTextQueueDepth` | read [document_text](/code/tables/document_text.md) |  |

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextDrain()](/code/functions/documentTextDrain.md)

