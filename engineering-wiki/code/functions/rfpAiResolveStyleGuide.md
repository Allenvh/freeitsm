# rfpAiResolveStyleGuide()

Resolve the style guide for an RFP. Order of precedence: 1. The RFP's own `rfps.style_guide` override (per-RFP) 2. The system-wide `system_settings.rfp_default_style_guide` 3. A safe hardcoded fallback

```php
function rfpAiResolveStyleGuide(PDO $conn, int $rfpId): string
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `rfpAiResolveStyleGuide` | read [rfps](/code/tables/rfps.md) |  |
| 49 | `rfpAiResolveStyleGuide` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiGenerateSectionStreaming()](/code/functions/rfpAiGenerateSectionStreaming.md), [rfpAiGenerateFramingStreaming()](/code/functions/rfpAiGenerateFramingStreaming.md), [rfpAiRestyleStreaming()](/code/functions/rfpAiRestyleStreaming.md)

