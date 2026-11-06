# unmergeTicket()

Undo a merge: take back exactly what was moved, and reopen the source.

HOW THIS DIFFERS FROM UNDOING A SPLIT
------------------------------------
Undoing a split refuses if ANYTHING has happened on the new ticket, because that
ticket was brand new — activity on it means somebody has started working the thing
you are about to delete.

A merge is the opposite. The survivor is the LIVE ticket: people are supposed to
keep replying on it, and usually have. Refusing on that basis would make unmerge
useless within the hour. So this takes back only what the merge recorded and leaves
everything else on the survivor, including every reply written since.

It still refuses when the recorded rows have been moved on AGAIN (split out, merged
elsewhere), because then this is no longer a reversal and pretending otherwise
strands messages.

WHAT IT CANNOT PUT BACK
-----------------------
Ticket-to-ticket LINKS that the merge re-pointed at the survivor stay there. The
merge did not record which links it moved (it deduplicates and drops self-links, so
"which ones came from the source" is genuinely ambiguous afterwards). This is a
known limitation rather than an oversight; the alternative is guessing, and links
are cheap to re-add by hand.

```php
function unmergeTicket(PDO $conn, int $actorId, int $mergeId): array
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 172.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `int` |  | the analyst undoing the merge, for the audit rows |
| `$mergeId` | `int` |  | the merge to undo (ticket_merges.id) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 175 | `unmergeTicket` | read [ticket_merges](/code/tables/ticket_merges.md) |  |
| 198 | `unmergeTicket` | read [emails](/code/tables/emails.md) |  |
| 210 | `unmergeTicket` | **update** [emails](/code/tables/emails.md) (`ticket_id`) |  |
| 229 | `unmergeTicket` | read [email_attachments](/code/tables/email_attachments.md) |  |
| 235 | `unmergeTicket` | **delete** [email_attachments](/code/tables/email_attachments.md) |  |
| 236 | `unmergeTicket` | **delete** [emails](/code/tables/emails.md) |  |
| 243 | `unmergeTicket` | **update** [tickets](/code/tables/tickets.md) (`merged_into_id`, `status_id`, `closed_datetime`, `updated_datetime`) |  |
| 257 | `unmergeTicket` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 266 | `unmergeTicket` | read [ticket_merges](/code/tables/ticket_merges.md) |  |
| 270 | `unmergeTicket` | read [emails](/code/tables/emails.md) |  |
| 277 | `unmergeTicket` | **update** [ticket_merges](/code/tables/ticket_merges.md) (`undone_datetime`, `undone_by_id`) |  |

## Calls

[mergeAudit()](/code/functions/mergeAudit.md), [mergeTicketNumber()](/code/functions/mergeTicketNumber.md)

## Called from

- [api/tickets/undo_merge.php](/code/files/api/tickets/undo_merge.php.md)

