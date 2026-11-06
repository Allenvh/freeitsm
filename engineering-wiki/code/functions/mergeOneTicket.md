# mergeOneTicket()

Fold a single source into the target. Assumes the caller holds a transaction.

```php
function mergeOneTicket(
    PDO $conn,
    int $actorId,
    int $sourceId,
    int $targetId,
    array $source,
    array $settings
): void
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 491.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `int` |  | the analyst doing the merge, for the audit rows |
| `$sourceId` | `int` |  | the ticket being folded away |
| `$targetId` | `int` |  | the ticket it is folded into |
| `$source` | `array` |  | the source ticket's row, as loaded by TicketsService::loadTicket() |
| `$settings` | `array` |  | the merge policy from `mergeSettings()`: reference_mode, originals_mode, ai_summary |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 504 | `mergeOneTicket` | read [emails](/code/tables/emails.md) |  |
| 508 | `mergeOneTicket` | **update** [emails](/code/tables/emails.md) (`ticket_id`) |  |
| 553 | `mergeOneTicket` | **update** [ticket_links](/code/tables/ticket_links.md) (`source_ticket_id`) |  |
| 554 | `mergeOneTicket` | **update** [ticket_links](/code/tables/ticket_links.md) (`target_ticket_id`) |  |
| 555 | `mergeOneTicket` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 557 | `mergeOneTicket` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 582 | `mergeOneTicket` | **update** [tickets](/code/tables/tickets.md) (`merged_into_id`, `status_id`, `closed_datetime`, `updated_datetime`) |  |
| 594 | `mergeOneTicket` | **insert** [ticket_merges](/code/tables/ticket_merges.md) (`source_ticket_id`, `source_ticket_number`, `target_ticket_id`, `reference_mode`, `originals_mode`, `moved_email_ids`, `moved_related`, `snapshot_email_id`, `source_prev_status_id`, `source_prev_closed_datetime`, `merged_by_id`, `merged_datetime`) |  |
| 613 | `mergeOneTicket` | **insert** [ticket_links](/code/tables/ticket_links.md) (`source_ticket_id`, `target_ticket_id`, `relation_type`, `created_by_id`, `created_datetime`) |  |

## Calls

[mergeAttachSnapshot()](/code/functions/mergeAttachSnapshot.md), [mergeAudit()](/code/functions/mergeAudit.md), [mergeBuildSnapshotHtml()](/code/functions/mergeBuildSnapshotHtml.md), [mergeFirstClosedStatusId()](/code/functions/mergeFirstClosedStatusId.md), [mergeTicketNumber()](/code/functions/mergeTicketNumber.md)

## Called from

- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeTickets()](/code/functions/mergeTickets.md)

