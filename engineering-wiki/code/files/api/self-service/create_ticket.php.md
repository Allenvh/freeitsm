# api/self-service/create_ticket.php

> API endpoint - 335 lines

API: Create Ticket from Self-Service Portal POST - Creates a new ticket for the logged-in user

## The endpoint

| | |
|---|---|
| URL | `api/self-service/create_ticket.php` |
| Protected by | Signed-in analyst (session check) (via includes/notifications_router.php); Signed-in analyst (session check) (via includes/service_context.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [generateTicketNumber()](/code/functions/generateTicketNumber.md) | 328 | ⚠️ One of THREE copies of this, now all delegating to the single engine in includes/ticket_numbering.php (GH #71). Left as a wrapper rather than deleted so the call sites in this file keep reading naturally; the format, the counter and the uniqueness check all live in one place. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 72 |  | read [users](/code/tables/users.md) |  |
| 135 |  | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `category_id`, `user_id`, `tenant_id`, `created_datetime`, `updated_datetime`), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 167 |  | read [users_assets](/code/tables/users_assets.md) |  |
| 169 |  | **insert** [ticket_assets](/code/tables/ticket_assets.md) (`ticket_id`, `asset_id`, `created_datetime`, `created_by_user_id`) |  |
| 204 |  | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`, `is_read`, `ticket_id`) |  |
| 236 |  | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime`) |  |
| 271 |  | **update** [emails](/code/tables/emails.md) (`body_content`, `has_attachments`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/html_sanitise.php](/code/files/includes/html_sanitise.php.md), [includes/ticket_recordings.php](/code/files/includes/ticket_recordings.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/self_service_mailboxes.php](/code/files/includes/self_service_mailboxes.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md), [includes/ticket_events.php](/code/files/includes/ticket_events.php.md)

**Calls:** [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [attachmentIngestNotice()](/code/functions/attachmentIngestNotice.md), [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md), [claimPendingRecordings()](/code/functions/claimPendingRecordings.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [sanitiseUserHtml()](/code/functions/sanitiseUserHtml.md), [selfServiceMailboxes()](/code/functions/selfServiceMailboxes.md), [ticketCategoriesResolved()](/code/functions/ticketCategoriesResolved.md), [ticketCategoryOn()](/code/functions/ticketCategoryOn.md), [ticketDispatchCreated()](/code/functions/ticketDispatchCreated.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [ticketSensitivityRaise()](/code/functions/ticketSensitivityRaise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)

