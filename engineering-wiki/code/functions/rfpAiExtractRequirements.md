# rfpAiExtractRequirements()

Run Pass 1 extraction on a single document and return parsed items. Callers are responsible for inserting the items into rfp_extracted_requirements; this function only calls the AI and logs the call.

Returns:
  items        array of parsed requirement objects from the AI
  tokens_in    int
  tokens_out   int
  cache_read   int|null
  cache_write  int|null
  duration_ms  int

```php
function rfpAiExtractRequirements(
    PDO $conn,
    int $rfpId,
    int $documentId,
    string $rawText,
    string $departmentName
): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 559.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id, for the log |
| `$documentId` | `int` |  | the rfp_documents id being read, for the log |
| `$rawText` | `string` |  | the document's text, as extracted from the upload |
| `$departmentName` | `string` |  | the department that wrote the document, given to the model as context |

**Returns:** `array`

## Calls

[rfpAiCall()](/code/functions/rfpAiCall.md), [rfpAiLogAction()](/code/functions/rfpAiLogAction.md)

## Called from

- [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md)

