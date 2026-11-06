# writeupAiConfig()

Which AI config the assistant should use.

Prefers its own namespace so the spend shows up as its own line on the
provider's bill, but falls back to the module's existing Knowledge AI key
rather than demanding a second one. Somebody who has already set up Knowledge
AI has told us how this module talks to a model; making them say it twice
before the assistant will do anything is friction for no benefit, and the
first thing they would ask is why.

The returned 'ns' says which one answered, so the UI can be honest about
where the spend is going.

```php
function writeupAiConfig(PDO $conn): array
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

## Called from

- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)

