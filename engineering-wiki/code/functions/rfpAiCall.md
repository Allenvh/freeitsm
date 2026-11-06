# rfpAiCall()

Generic provider-agnostic call.

Options:
  system      string  System prompt
  user        string  User prompt
  max_tokens  int     Default 4096
  temperature float   Default 0.0 (deterministic)

Returns:
  content       string  Text returned by the model
  tokens_in     int     Input tokens billed
  tokens_out    int     Output tokens billed
  cache_read    int|null  Cached input tokens read (Anthropic / OpenAI cached_tokens)
  cache_write   int|null  Cached input tokens written (Anthropic only)
  provider      string
  model         string
  duration_ms   int

```php
function rfpAiCall(PDO $conn, array $opts): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` |  | system, user (the prompts), max_tokens (default 4096), temperature (default 0.0) |

**Returns:** `array`

## Calls

[rfpAiCallAnthropic()](/code/functions/rfpAiCallAnthropic.md), [rfpAiCallOpenAI()](/code/functions/rfpAiCallOpenAI.md), [rfpAiGetSettings()](/code/functions/rfpAiGetSettings.md)

## Called from

- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiExtractRequirements()](/code/functions/rfpAiExtractRequirements.md), [rfpAiConsolidate()](/code/functions/rfpAiConsolidate.md)

