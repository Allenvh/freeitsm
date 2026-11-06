# writeupParseResponse()

Split a model response into its verdict and body.

Tolerant on purpose: a model that drops the colon, adds a markdown fence or
emits a stray blank line first should not turn a good article into an error.
The one thing we never do is GUESS a verdict — an unrecognised response is
treated as NOT_ENOUGH, because the failure mode of wrongly publishing is worse
than the failure mode of wrongly asking.

```php
function writeupParseResponse(string $raw): array
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 802.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the model's whole response text |

**Returns:** `array`

## Called from

- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)

