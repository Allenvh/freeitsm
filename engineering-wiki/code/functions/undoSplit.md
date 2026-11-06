# undoSplit()

Undo a split: send the messages back and remove the ticket that was created.

WHY THIS IS ALLOWED TO REFUSE
-----------------------------
An undo is only safe while the split is still the last thing that happened. The
moment somebody replies on the new ticket, adds a note, or logs time against it,
"put it back" stops having an obvious meaning — that reply was written to a
different ticket, about a different problem, and dragging it into the original is
not a reversal, it is a second mistake.

So this refuses rather than guessing, and says which kind of work is in the way.
"You cannot undo this because somebody has worked on it" is a sentence an analyst
can act on; a silently mangled conversation is not.

Splits made before the moved ids were recorded (#915) cannot be undone at all —
there is nothing to identify which messages to send back, and guessing would
scatter a conversation permanently.

```php
function undoSplit(PDO $conn, int $actorId, int $splitId): array
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 401.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `int` |  | the analyst undoing, recorded as undone_by_id; 0 is stored as NULL |
| `$splitId` | `int` |  | the ticket_splits row to reverse |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 404 | `undoSplit` | read [ticket_splits](/code/tables/ticket_splits.md) |  |
| 429 | `undoSplit` | read [emails](/code/tables/emails.md) |  |
| 435 | `undoSplit` | read [emails](/code/tables/emails.md) |  |
| 454 | `undoSplit` | **update** [emails](/code/tables/emails.md) (`ticket_id`) |  |
| 458 | `undoSplit` | **delete** [emails](/code/tables/emails.md) |  |
| 463 | `undoSplit` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 472 | `undoSplit` | **update** [ticket_splits](/code/tables/ticket_splits.md) (`undone_datetime`, `undone_by_id`) |  |

## Calls

[splitAudit()](/code/functions/splitAudit.md)

## Called from

- [api/tickets/undo_split.php](/code/files/api/tickets/undo_split.php.md)

