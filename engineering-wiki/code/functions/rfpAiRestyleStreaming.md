# rfpAiRestyleStreaming()

Streaming restyle pass over an existing piece of section HTML.

Caller supplies the rfp_id (so we can pull the right style guide),
the existing HTML, and a context label used for log readability
(e.g. "section #42" or "framing introduction"). Returns the
restyled HTML plus the usual usage stats.

Caller is responsible for the DB write — this function only does
the AI call and the audit log.

```php
function rfpAiRestyleStreaming(
    PDO $conn,
    int $rfpId,
    string $existingHtml,
    string $logContext,
    callable $onEvent
): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 1404.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id - picks the style guide |
| `$existingHtml` | `string` |  | the section's current HTML; empty throws |
| `$logContext` | `string` |  | a label for the log, e.g. "section #42" or "framing introduction" |
| `$onEvent` | `callable` |  | fn(string `$eventType`, array `$data`) - passed to `rfpAiCallAnthropicStreaming()` |

**Returns:** `array`

## Calls

[rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md), [rfpAiLogAction()](/code/functions/rfpAiLogAction.md), [rfpAiResolveStyleGuide()](/code/functions/rfpAiResolveStyleGuide.md)

## Called from

- [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md)
- [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md)

