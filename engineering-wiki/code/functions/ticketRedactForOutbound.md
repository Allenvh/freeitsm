# ticketRedactForOutbound()

A workflow event's payload as it may leave for a webhook, Slack or Teams (discussion #62). Unchanged for a normal ticket. For a confidential one it is cut down to an ALLOW-LIST - identifiers, number, status, priority, department, team, dates - because a deny-list of "content" fields would miss the next one somebody adds. The subject becomes "Confidential ticket" and `confidential` is set, so a message template still reads sensibly.

Never a setting: unlike AI, nobody chooses to post an HR ticket's subject into
a team channel, and a chat post cannot be taken back.

```php
function ticketRedactForOutbound(PDO $conn, array $payload): array
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 246.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$payload` | `array` |  | the workflow event payload; the ticket id is read from ticket.id or ticket_id |

**Returns:** `array`

## Calls

[ticketIsConfidential()](/code/functions/ticketIsConfidential.md)

## Called from

- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_webhook`

