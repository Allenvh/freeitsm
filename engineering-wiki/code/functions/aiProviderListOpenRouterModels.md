# aiProviderListOpenRouterModels()

Fetch (and cache) the OpenRouter model catalogue. No API key required. Cached in system_settings as JSON for 24h to keep the model picker snappy. Falls back to a stale cache if a refresh fetch fails.

```php
function aiProviderListOpenRouterModels(PDO $conn, bool $force = false): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 791.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$force` | `bool` | `false` | true to fetch afresh even when the 24-hour cache is still fresh |

**Returns:** array{models: array<int,array>, cached_at: int, stale: bool} models: [{id,name,context_length,prompt_price,completion_price}]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 794 | `aiProviderListOpenRouterModels` | read [system_settings](/code/tables/system_settings.md) |  |
| 846 | `aiProviderListOpenRouterModels` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/system/ai/openrouter_models.php](/code/files/api/system/ai/openrouter_models.php.md)

