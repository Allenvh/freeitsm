# integrationsPullComments()

Pull new comments for every link on one connection. The second half of the poll cron's unit of work.

The watermark is stamped from BEFORE the provider call, not after, so a comment
posted while the poll was running lands inside the next window rather than in
the gap between them. It is only advanced on success: a failed pull must be
retried against the same window, not skipped past.

```php
function integrationsPullComments(PDO $conn, array $connection): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1414.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connection` | `array` |  | the connection row as loaded by `integrationsLoadConnection()`; inbound_enabled must be set |

**Returns:** array ['pulled'=>int, 'imported'=>int, 'skipped'=>['echo'=>n, …]]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1424 | `integrationsPullComments` | read [integration_links](/code/tables/integration_links.md) |  |
| 1450 | `integrationsPullComments` | **update** [integration_connections](/code/tables/integration_connections.md) (`last_poll_watermark`) |  |

## Calls

[integrationsApplyCommentEvent()](/code/functions/integrationsApplyCommentEvent.md), [integrationsCommentSchemaReady()](/code/functions/integrationsCommentSchemaReady.md), [integrationsProviderFor()](/code/functions/integrationsProviderFor.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

## Called from

- [cron/integration_poll.php](/code/files/cron/integration_poll.php.md)

