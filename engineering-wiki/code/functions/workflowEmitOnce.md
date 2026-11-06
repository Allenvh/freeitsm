# workflowEmitOnce()

Dispatch a time-based event — but only the FIRST time this exact situation is seen. Returns true if it fired, false if it had already fired.

The three keys:
  `$event`       the trigger, e.g. 'sla.breached'
  `$entityKey`   WHAT it's about, e.g. 'ticket:183:response'
  `$fingerprint` the STATE it's about — a value that changes if the underlying
               deadline changes.

The fingerprint is what makes this re-arm correctly. If a ticket's priority
changes, its SLA target changes, and the fingerprint changes with it — so the
new deadline can breach and fire again, rather than being suppressed forever
by an emission recorded against the OLD deadline. Same for a contract whose
end date is pushed back. Without it, "fire once" would quietly mean "fire once
ever, even if the thing you were watching changed underneath you".

Atomicity: the ledger has a UNIQUE key over all three, and we INSERT IGNORE.
Only the insert that actually created a row dispatches. Two overlapping cron
runs therefore cannot double-fire — the database, not the application, is the
arbiter.

```php
function workflowEmitOnce(
    PDO $conn,
    string $event,
    string $entityKey,
    string $fingerprint,
    array $payload
): bool
```

Defined in [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$event` | `string` |  | the trigger event key, e.g. 'sla.breached' |
| `$entityKey` | `string` |  | what it is about, e.g. 'ticket:183:response' |
| `$fingerprint` | `string` |  | the state it is about; a new value re-arms the emission |
| `$payload` | `array` |  | the event payload handed to WorkflowEngine::dispatch() |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 68 | `workflowEmitOnce` | read [workflows](/code/tables/workflows.md) |  |
| 79 | `workflowEmitOnce` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_emit_workflow_event()](/code/functions/sla_emit_workflow_event.md)
- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowEmitContractExpiries()](/code/functions/workflowEmitContractExpiries.md), [workflowEmitWarrantyExpiries()](/code/functions/workflowEmitWarrantyExpiries.md)

