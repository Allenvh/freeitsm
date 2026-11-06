# api/tickets/check_mailbox_email.php

> API endpoint - 1616 lines

API Endpoint: Check emails for a specific mailbox

This uses the mailbox settings from the database instead of config constants.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/check_mailbox_email.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET mailbox_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getMailboxConfig()](/code/functions/getMailboxConfig.md) | 408 | Get mailbox configuration from database |
| [getValidAccessToken()](/code/functions/getValidAccessToken.md) | 429 | Get valid access token (refresh if expired) |
| [refreshAccessToken()](/code/functions/refreshAccessToken.md) | 454 | Refresh the access token using refresh token |
| [saveTokenData()](/code/functions/saveTokenData.md) | 508 | Save token data to database |
| [recordMailboxCheckOutcome()](/code/functions/recordMailboxCheckOutcome.md) | 541 | Record how a mail check went — stamping the time AND what went wrong. |
| [failMailboxCheck()](/code/functions/failMailboxCheck.md) | 569 | Report a check that could not run at all: record it, say so, stop. |
| [updateLastChecked()](/code/functions/updateLastChecked.md) | 580 | Update last checked datetime (clean run). |
| [getEmails()](/code/functions/getEmails.md) | 590 | Retrieve emails from Microsoft Graph API |
| [deleteEmailFromMailbox()](/code/functions/deleteEmailFromMailbox.md) | 642 | Delete an email from the mailbox via Microsoft Graph API |
| [resolveMailFolderId()](/code/functions/resolveMailFolderId.md) | 682 | Resolve a folder name to what Graph will accept in a path. |
| [moveEmailToFolder()](/code/functions/moveEmailToFolder.md) | 694 | Move an email to a folder via Microsoft Graph API |
| [markEmailAsRead()](/code/functions/markEmailAsRead.md) | 735 | Mark an email as read via Microsoft Graph API |
| [handleEmailAfterProcessing()](/code/functions/handleEmailAfterProcessing.md) | 777 | Handle email after processing based on action setting Actions: 'delete' (permanent), 'move_to_deleted' (Deleted Items), 'mark_read' (leave in inbox), 'move_to_folder' (custom folder) |
| [generateTicketNumber()](/code/functions/generateTicketNumber.md) | 828 | ⚠️ The third of three copies, now delegating to the single engine in includes/ticket_numbering.php (GH #71). An emailed ticket must be numbered by exactly the same rules as one raised by an analyst or through the portal. |
| [extractTicketReference()](/code/functions/extractTicketReference.md) | 839 | Extract ticket reference from subject |
| [findTicketByNumber()](/code/functions/findTicketByNumber.md) | 865 | Find existing ticket by number. |
| [getOrCreateUser()](/code/functions/getOrCreateUser.md) | 891 | Get or create user by email address Returns the user ID |
| [fetchEmailAttachments()](/code/functions/fetchEmailAttachments.md) | 925 | Fetch attachments from Graph API |
| [saveAttachment()](/code/functions/saveAttachment.md) | 961 | Save attachment to filesystem and database |
| [rewriteCidReferences()](/code/functions/rewriteCidReferences.md) | 1040 | Rewrite CID references in email body |
| [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md) | 1078 | Save email to database |
| [stripInboundThread()](/code/functions/stripInboundThread.md) | 1410 | Strip the quoted thread from an inbound reply Relies on our own visible marker text which survives all email clients, with a generic blockquote fallback |
| [oversizeMeasure()](/code/functions/oversizeMeasure.md) | 1457 | Fetch (or take the pre-loaded) attachments of one email and measure them. Returns [the attachment list to hand on, [['name','size'], ...] for the file attachments in order]. Sizes are the DECODED bytes where the content is here, else the size the provider reports. |
| [oversizeLogStep()](/code/functions/oversizeLogStep.md) | 1489 | The processing-log step describing the size check. |
| [oversizeSummary()](/code/functions/oversizeSummary.md) | 1509 | "big.zip (40 MB), video.mp4 (30 MB)" - what broke the limits, for the log's reason column. |
| [logEmailImport()](/code/functions/logEmailImport.md) | 1523 | Writes one email_import row to system_logs with the mailbox id added to the details. A logging failure goes to the PHP error log and never stops the import. |
| [loadWhitelist()](/code/functions/loadWhitelist.md) | 1541 | Load whitelist entries for a mailbox |
| [isWhitelisted()](/code/functions/isWhitelisted.md) | 1567 | Check if a sender address is whitelisted |
| [logMailboxActivity()](/code/functions/logMailboxActivity.md) | 1593 | Log mailbox activity (imports and rejections) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 409 | `getMailboxConfig` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 512 | `saveTokenData` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |
| 544 | `recordMailboxCheckOutcome` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`last_checked_datetime`, `last_error`, `last_error_datetime`) |  |
| 896 | `getOrCreateUser` | read [users](/code/tables/users.md) |  |
| 904 | `getOrCreateUser` | **update** [users](/code/tables/users.md) (`display_name`) |  |
| 912 | `getOrCreateUser` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |
| 1007 | `saveAttachment` | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline`) |  |
| 1085 | `saveEmailToDatabase` | read [emails](/code/tables/emails.md) |  |
| 1139 | `saveEmailToDatabase` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |
| 1186 | `saveEmailToDatabase` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 1194 | `saveEmailToDatabase` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `created_datetime`, `updated_datetime`, `user_id`, `tenant_id`, `origin_id`), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 1217 | `saveEmailToDatabase` | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `cc_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`) |  |
| 1270 | `saveEmailToDatabase` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 1353 | `saveEmailToDatabase` | **update** [emails](/code/tables/emails.md) (`body_content`, `has_attachments`) |  |
| 1461 | `oversizeMeasure` | read [emails](/code/tables/emails.md) |  |
| 1526 | `logEmailImport` | **insert** [system_logs](/code/tables/system_logs.md) (`log_type`, `analyst_id`, `details`, `created_datetime`) |  |
| 1542 | `loadWhitelist` | read [mailbox_email_whitelist](/code/tables/mailbox_email_whitelist.md) |  |
| 1595 | `logMailboxActivity` | **insert** [mailbox_activity_log](/code/tables/mailbox_activity_log.md) (`mailbox_id`, `action`, `from_address`, `from_name`, `subject`, `reason`, `ticket_id`, `processing_log`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), `api/includes/mailbox_imap.php`, `api/includes/gmail.php`, `api/includes/template_email.php`, [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ticket_events.php](/code/files/includes/ticket_events.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/search/indexer.php](/code/files/includes/search/indexer.php.md)

**Calls:** [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [attachmentIngestNotice()](/code/functions/attachmentIngestNotice.md), [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [encryptValue()](/code/functions/encryptValue.md), [gmailGetEmails()](/code/functions/gmailGetEmails.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailMarkAsRead()](/code/functions/gmailMarkAsRead.md), [gmailTrashMessage()](/code/functions/gmailTrashMessage.md), [imapGetEmails()](/code/functions/imapGetEmails.md), [imapHandleAfterProcessing()](/code/functions/imapHandleAfterProcessing.md), [mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md), [mailboxBackfillIdentity()](/code/functions/mailboxBackfillIdentity.md), [mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [mailboxGraphGet()](/code/functions/mailboxGraphGet.md), [mailboxIdentityMismatch()](/code/functions/mailboxIdentityMismatch.md), [mailboxResolveFolderId()](/code/functions/mailboxResolveFolderId.md), [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md), [oauthTokenErrorMessage()](/code/functions/oauthTokenErrorMessage.md), [oversizeMbWords()](/code/functions/oversizeMbWords.md), [oversizeNotify()](/code/functions/oversizeNotify.md), [oversizePlan()](/code/functions/oversizePlan.md), [oversizeRules()](/code/functions/oversizeRules.md), [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [resolveMergedTicket()](/code/functions/resolveMergedTicket.md), [resolveTicketTenantForEmail()](/code/functions/resolveTicketTenantForEmail.md), [searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchIndexTicket()](/code/functions/searchIndexTicket.md), [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md), [ticketDispatchCreated()](/code/functions/ticketDispatchCreated.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md), [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md)

