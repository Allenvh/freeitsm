# claimPendingRecordings()

Move pending recordings onto a ticket and (optionally) a specific message.

File renames happen outside any transaction — filesystem operations are not
transactional anyway, and a failed move must not cost the user their reply.

```php
function claimPendingRecordings(
    PDO $conn,
    array $recordingIds,
    int $ticketId,
    int $userId,
    ?int $emailId = null
): int
```

Defined in [includes/ticket_recordings.php](/code/files/includes/ticket_recordings.php.md), line 42.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$recordingIds` | `array` |  | ids the client claims to own (untrusted) |
| `$ticketId` | `int` |  | the ticket they now belong to |
| `$userId` | `int` |  | the portal user doing the claiming |
| `$emailId` | `?int` | `null` | the message they were recorded with; NULL means the ticket's opening message, which is how every recording behaved before replies could carry one |

**Returns:** int how many rows were actually claimed

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `claimPendingRecordings` | read [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 67 | `claimPendingRecordings` | **update** [ticket_recordings](/code/tables/ticket_recordings.md) (`ticket_id`, `email_id`, `file_path`) |  |

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)

