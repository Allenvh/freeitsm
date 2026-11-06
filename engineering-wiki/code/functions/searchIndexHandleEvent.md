# searchIndexHandleEvent()

The dispatch subscriber. Called for EVERY workflow event, so it decides quickly whether it cares and gets out of the way.

Never throws: a search index that cannot be updated must not cost anybody
their ticket, their note or their reply.

```php
function searchIndexHandleEvent(string $event, array $payload): void
```

Defined in [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), line 394.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `string` |  | the workflow event name, e.g. 'ticket.note_added'; most are ignored |
| `$payload` | `array` |  | the event payload; the ticket id is read from ['ticket']['id'] or ['ticket_id'] |

**Returns:** `void`

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [searchCorpusDeleteTicket()](/code/functions/searchCorpusDeleteTicket.md), [searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchIndexTicket()](/code/functions/searchIndexTicket.md)

## Called from

- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::dispatch`

