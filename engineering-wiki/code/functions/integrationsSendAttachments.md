# integrationsSendAttachments()

Push a ticket's attachments onto an issue that has just been created.

⚠️ **A failed attachment must never lose the escalation.** The issue already
exists in the tracker by the time this runs; throwing here would surface as
"escalation failed" and an analyst would raise a second one. Same rule as the
rejected priority in §7e — the expensive thing is already done.

```php
function integrationsSendAttachments(
    PDO $conn,
    array $connection,
    array $link,
    array $attachments
): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 804.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connection` | `array` |  | the connection row as loaded by `integrationsLoadConnection()` |
| `$link` | `array` |  | the integration_links row for the issue just created (needs external_id) |
| `$attachments` | `array` |  | the list from `integrationsTicketAttachments()`; entries with a skip_reason are counted, not sent |

**Returns:** array ['sent'=>int, 'failed'=>int, 'skipped'=>int, 'notes'=>string[]]

## Calls

[integrationsProviderFor()](/code/functions/integrationsProviderFor.md)

## Called from

- [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

