# csatGetSetting()

Read a system_settings key with a default fallback.

```php
function csatGetSetting(PDO $conn, string $key, string $default = ''): string
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 27.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings.setting_key, e.g. 'csat_mode'; encrypted keys are decrypted |
| `$default` | `string` | `''` | returned when the key has no row (the first answer is cached per request) |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 | `csatGetSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md)

## Called from

- [api/tickets/request_csat.php](/code/files/api/tickets/request_csat.php.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatTicketChannel()](/code/functions/csatTicketChannel.md), [sendCsatSurvey()](/code/functions/sendCsatSurvey.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::updateTicket`
- [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md)

