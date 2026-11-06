# documentTextReadable()

Can ANYTHING read this file — either tier?

⚠️ `attTextSupports()` is TIER 1 ONLY: plain text and OOXML. PDFs, images and
scans need tier 2 (Tika). Using the tier-1 test alone marked every PDF
`unsupported` at upload, which reads as "we looked and there is nothing to
read" — permanently — when the truth was "we never asked the thing that could".

```php
function documentTextReadable(PDO $conn, string $filename): bool
```

Defined in [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), line 46.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$filename` | `string` |  | the file's name - only its extension is judged |

**Returns:** `bool`

## Calls

[attTextSupports()](/code/functions/attTextSupports.md), [tikaConfigured()](/code/functions/tikaConfigured.md), [tikaHandles()](/code/functions/tikaHandles.md)

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextEnqueue()](/code/functions/documentTextEnqueue.md)

