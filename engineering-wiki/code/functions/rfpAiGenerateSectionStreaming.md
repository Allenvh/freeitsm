# rfpAiGenerateSectionStreaming()

Run Pass 3 (generate one section, streaming) for a single category.

Pulls the consolidated requirements for the category, joins out to
the department info via the M:N source-link table, builds the user
prompt, and streams the AI response. The caller (typically an SSE
endpoint) is responsible for relaying the streaming events to the
browser via the supplied callback.

Returns:
  content       string  Full generated HTML
  tokens_in     int|null
  tokens_out    int|null
  cache_read    int|null
  cache_write   int|null
  duration_ms   int
  inputs_hash   string  md5 of the input requirements (used for skip-if-unchanged)

Throws if the category has no consolidated requirements assigned.

```php
function rfpAiGenerateSectionStreaming(
    PDO $conn,
    int $rfpId,
    int $categoryId,
    callable $onEvent
): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 1009.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id |
| `$categoryId` | `int` |  | the rfp_categories id the section is written for (must belong to the RFP) |
| `$onEvent` | `callable` |  | fn(string `$eventType`, array `$data`) - passed to `rfpAiCallAnthropicStreaming()` |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1012 | `rfpAiGenerateSectionStreaming` | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 1021 | `rfpAiGenerateSectionStreaming` | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 1037 | `rfpAiGenerateSectionStreaming` | read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |

## Calls

[rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md), [rfpAiLogAction()](/code/functions/rfpAiLogAction.md), [rfpAiResolveStyleGuide()](/code/functions/rfpAiResolveStyleGuide.md)

## Called from

- [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md)

