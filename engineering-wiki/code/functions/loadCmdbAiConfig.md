# loadCmdbAiConfig()

Load CMDB AI config via the shared building block (ns=cmdb_ai), then attach the CMDB-specific custom instructions. Returns provider/model/api_key/ verify_ssl/custom_instructions. Throws if no key is set.

```php
function loadCmdbAiConfig(PDO $conn): array
```

Defined in [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md), line 17.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 | `loadCmdbAiConfig` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

## Called from

- [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md)
- [api/cmdb/ai_suggest_questions.php](/code/files/api/cmdb/ai_suggest_questions.php.md)
- [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md)

