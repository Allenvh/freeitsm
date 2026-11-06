# rfpAiLogAction()

Insert a row into rfp_processing_log. Silently no-ops if rfp_id is null (the column is NOT NULL in the schema; callers should always have an rfp_id by the time they're calling AI).

```php
function rfpAiLogAction(
    PDO $conn,
    ?int $rfpId,
    ?int $documentId,
    ?int $sectionId,
    string $action,
    string $status,
    ?string $details,
    ?int $tokensIn,
    ?int $tokensOut
): void
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 493.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `?int` |  | the rfps id; null logs nothing |
| `$documentId` | `?int` |  | the rfp_documents id the call was about; null when it was not about one document |
| `$sectionId` | `?int` |  | the section id the call was about; null when it was not about one section |
| `$action` | `string` |  | what was done: 'extract', 'consolidate', 'generate_section', 'generate_framing' or 'restyle_section' |
| `$status` | `string` |  | 'success' or 'error' |
| `$details` | `?string` |  | JSON with the figures for a success, or the error message |
| `$tokensIn` | `?int` |  | input tokens billed; null when unknown |
| `$tokensOut` | `?int` |  | output tokens billed; null when unknown |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 506 | `rfpAiLogAction` | **insert** [rfp_processing_log](/code/tables/rfp_processing_log.md) (`rfp_id`, `document_id`, `section_id`, `action`, `status`, `details`, `tokens_in`, `tokens_out`) |  |

## Called from

- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiExtractRequirements()](/code/functions/rfpAiExtractRequirements.md), [rfpAiConsolidate()](/code/functions/rfpAiConsolidate.md), [rfpAiConsolidateStreaming()](/code/functions/rfpAiConsolidateStreaming.md), [rfpAiGenerateSectionStreaming()](/code/functions/rfpAiGenerateSectionStreaming.md), [rfpAiGenerateFramingStreaming()](/code/functions/rfpAiGenerateFramingStreaming.md), [rfpAiRestyleStreaming()](/code/functions/rfpAiRestyleStreaming.md)

