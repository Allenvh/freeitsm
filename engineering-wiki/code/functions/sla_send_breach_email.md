# sla_send_breach_email()

Send a breach / warning email via the ticket's originating mailbox. Falls back to the first active mailbox if the ticket has none (e.g. manually-created ticket).

```php
function sla_send_breach_email(
    PDO $conn,
    int $ticketId,
    array $state,
    string $targetType,
    string $trigger,
    array $recipients
): void
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 300.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$state` | `array` |  | the ticket's SLA state from `sla_get_state()` |
| `$targetType` | `string` |  | 'response' or 'resolution' - which part of `$state` the email is about |
| `$trigger` | `string` |  | 'warning' or 'breach' |
| `$recipients` | `array` |  | the email addresses to send to, from `sla_resolve_recipients()` |

**Returns:** `void`

## Calls

[buildTicketMergeData()](/code/functions/buildTicketMergeData.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md), [sla_build_breach_email_body()](/code/functions/sla_build_breach_email_body.md)

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)

