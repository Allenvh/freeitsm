# mergeAttachSnapshot()

Attach a snapshot to the target ticket.

A ticket's attachments are, in this schema, ALWAYS `email_attachments` reached
through `emails.ticket_id` — there is no standalone ticket-attachment table. So the
snapshot arrives as a system-generated message on the thread carrying the file.
That is not a workaround dressed as a design: it puts the snapshot in the
conversation where an analyst will notice it AND in the Attachments list, from one
insert, downloadable through the same endpoint as every other attachment.

```php
function mergeAttachSnapshot(PDO $conn, int $targetId, array $source, string $html): int
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 770.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$targetId` | `int` |  | the ticket the snapshot is attached to |
| `$source` | `array` |  | the source ticket's row (ticket_number, id) - its number names the file |
| `$html` | `string` |  | the snapshot document from `mergeBuildSnapshotHtml()` |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 774 | `mergeAttachSnapshot` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `ticket_id`, `direction`, `channel`, `is_initial`) |  |
| 825 | `mergeAttachSnapshot` | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime`) |  |

## Calls

[uploadStoreBytes()](/code/functions/uploadStoreBytes.md)

## Called from

- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeOneTicket()](/code/functions/mergeOneTicket.md)

