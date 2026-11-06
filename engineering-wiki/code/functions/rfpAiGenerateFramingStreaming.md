# rfpAiGenerateFramingStreaming()

Streaming generator for one framing section.

Uses the shared RFP_AI_FRAMING_SYSTEM so all three calls (intro,
scope, response_instructions) within an RFP hit the same cached
prefix — the system prompt + style guide is identical, only the
user message differs by section_key.

Inputs come from rfp metadata + category list. We do NOT send the
raw consolidated requirements — too many tokens, and framing
sections shouldn't get into specifics anyway. Categories are
enough.

Returns the same shape as `rfpAiGenerateSectionStreaming()`:
  content, tokens_in, tokens_out, cache_read, cache_write,
  duration_ms, inputs_hash

```php
function rfpAiGenerateFramingStreaming(
    PDO $conn,
    int $rfpId,
    string $sectionKey,
    callable $onEvent
): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 1240.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id |
| `$sectionKey` | `string` |  | which framing section: a key of RFP_AI_FRAMING_KEYS (introduction, scope, response_instructions) |
| `$onEvent` | `callable` |  | fn(string `$eventType`, array `$data`) - passed to `rfpAiCallAnthropicStreaming()` |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1247 | `rfpAiGenerateFramingStreaming` | read [rfps](/code/tables/rfps.md) |  |
| 1256 | `rfpAiGenerateFramingStreaming` | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 1272 | `rfpAiGenerateFramingStreaming` | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |

## Calls

[rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md), [rfpAiLogAction()](/code/functions/rfpAiLogAction.md), [rfpAiResolveStyleGuide()](/code/functions/rfpAiResolveStyleGuide.md)

## Called from

- [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md)

