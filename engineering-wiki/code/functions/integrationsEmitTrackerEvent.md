# integrationsEmitTrackerEvent()

Fire a `tracker.*` workflow event.

⚠️ These are NOT time-based triggers and must not go through
`workflow_scheduled_emissions`. The test on the Time-Based Triggers wiki page
is *"did something happen, or did time merely pass?"* — here something
happened: a developer moved the issue or wrote a comment. The poll is only
**how we find out**, because a self-hosted install cannot be called back.

That distinction is not pedantry, it changes behaviour. Each of these fires
from the point where the new state is **already persisted** — a status move is
only reported once the link row has been updated, a comment only once its map
row exists — so they are edge-triggered by construction and cannot repeat. A
fingerprint ledger would be actively WRONG: `todo → in_progress → todo →
in_progress` is three real transitions, and a fingerprint on current state
would silently swallow the third.

Never throws: a workflow problem must not break a poll, exactly as a workflow
problem must not break a ticket save.

```php
function integrationsEmitTrackerEvent(
    PDO $conn,
    string $event,
    array $connection,
    array $link,
    array $extra = []
): void
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 924.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$event` | `string` |  | the workflow event name, e.g. 'tracker.issue_status_changed' |
| `$connection` | `array` |  | the connection row (provider, name, id are copied into the payload) |
| `$link` | `array` |  | the integration_links row; only entity_type 'ticket' fires anything |
| `$extra` | `array` | `[]` | more fields merged into the payload's 'tracker' block |

**Returns:** `void`

## Calls

[integrationsTicketPayload()](/code/functions/integrationsTicketPayload.md)

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md), [integrationsApplyCommentEvent()](/code/functions/integrationsApplyCommentEvent.md), [integrationsRefreshConnection()](/code/functions/integrationsRefreshConnection.md)

