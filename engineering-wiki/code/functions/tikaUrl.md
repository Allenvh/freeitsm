# tikaUrl()

The configured endpoint, or '' when tier 2 is switched off.

⚠️ Configured and AVAILABLE are different states and the difference matters —
see `tikaExtract()`. Not configured means "these formats are unsupported, and we
say so"; configured-but-unreachable means "we still owe this file".

```php
function tikaUrl(PDO $conn): string
```

Defined in [includes/search/tika.php](/code/files/includes/search/tika.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `tikaUrl` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md)
- [includes/search/tika.php](/code/files/includes/search/tika.php.md) - in [tikaConfigured()](/code/functions/tikaConfigured.md), [tikaExtract()](/code/functions/tikaExtract.md), [tikaPing()](/code/functions/tikaPing.md)

