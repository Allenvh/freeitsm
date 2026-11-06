# api/self-service/reply_ticket.php

> API endpoint - 261 lines

API: Reply to your own ticket from the Self-Service Portal. POST { ticket_id, body, attachments[], recording_ids[] }

Until this existed the portal was entirely READ-ONLY: a requester could watch
their ticket but not answer it, so "can you send us a screenshot?" forced them
back to email — the exact round trip the portal is meant to remove.

The reply is recorded as an `emails` row with direction 'Portal', the same
shape create_ticket.php already writes for the opening message. Nothing is
SENT: the requester is already here and the analyst sees it in the inbox, so
emailing them their own words back would be noise. (That also keeps the portal
working on an install with no mailbox configured at all.) Analysts' outbound
replies quote the whole thread, so a portal reply is carried into their next
email automatically.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/reply_ticket.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php); Signed-in analyst (session check) (via includes/notifications_router.php); Signed-in analyst (session check) (via includes/service_context.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 74 |  | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 98 |  | read [users](/code/tables/users.md) |  |
| 114 |  | read [emails](/code/tables/emails.md) |  |
| 132 |  | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`, `is_read`, `ticket_id`) |  |
| 195 |  | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime`) |  |
| 226 |  | **update** [emails](/code/tables/emails.md) (`body_content`, `has_attachments`) |  |
| 232 |  | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/ticket_recordings.php](/code/files/includes/ticket_recordings.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/managers.php](/code/files/includes/managers.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [attachmentIngestNotice()](/code/functions/attachmentIngestNotice.md), [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md), [claimPendingRecordings()](/code/functions/claimPendingRecordings.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md), [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md), [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md)

