# api/tickets/send_email.php

> API endpoint - 939 lines

Send Email API - Uses Microsoft Graph API to send emails

## The endpoint

| | |
|---|---|
| URL | `api/tickets/send_email.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getMailboxForTicket()](/code/functions/getMailboxForTicket.md) | 209 | Get mailbox for a ticket based on associated emails |
| [getValidAccessToken()](/code/functions/getValidAccessToken.md) | 236 | Get valid access token (refresh if expired) |
| [refreshAccessToken()](/code/functions/refreshAccessToken.md) | 261 | Refresh the access token using mailbox configuration |
| [saveTokenData()](/code/functions/saveTokenData.md) | 315 | Save token data to database |
| [buildEmailMessage()](/code/functions/buildEmailMessage.md) | 334 | Build email message structure for Graph API |
| [uploadedFileParts()](/code/functions/uploadedFileParts.md) | 379 | The files the analyst attached, in the Graph fileAttachment shape that all three providers' builders take (Graph directly; SMTP and Gmail through includes/mime_message.php). |
| [processInlineImages()](/code/functions/processInlineImages.md) | 422 | Process inline images in email body Converts image links the customer's mail client could never load into images carried inside the email itself (src="cid:..."). |
| [getAttachmentById()](/code/functions/getAttachmentById.md) | 543 | Get attachment by ID from database - only if it belongs to this ticket |
| [getAttachmentByCid()](/code/functions/getAttachmentByCid.md) | 566 | Get attachment by CID and email_id from database - only if it belongs to this ticket |
| [parseRecipients()](/code/functions/parseRecipients.md) | 598 | Parse recipients string into Graph API format |
| [sendEmailViaGraph()](/code/functions/sendEmailViaGraph.md) | 627 | Send email via Microsoft Graph API |
| [stripThreadFromBody()](/code/functions/stripThreadFromBody.md) | 666 | Strip the quoted thread from the email body Looks for the reply marker and returns only the content above it |
| [buildFullEmailBody()](/code/functions/buildFullEmailBody.md) | 697 | Build full email body with thread for sending to recipient Analyst's content + reply marker + quoted thread from DB |
| [stripQuotedContent()](/code/functions/stripQuotedContent.md) | 769 | Strip quoted/nested content from email body (for thread assembly) Relies on our own visible marker text, with generic blockquote fallback |
| [saveSentEmail()](/code/functions/saveSentEmail.md) | 818 | Save sent email to database, including whatever was attached to it. |
| [saveSentAttachments()](/code/functions/saveSentAttachments.md) | 871 | Put the files an analyst attached to a reply on the ticket. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 211 | `getMailboxForTicket` | read [emails](/code/tables/emails.md), read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 319 | `saveTokenData` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |
| 546 | `getAttachmentById` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 572 | `getAttachmentByCid` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 699 | `buildFullEmailBody` | read [tickets](/code/tables/tickets.md) |  |
| 705 | `buildFullEmailBody` | read [emails](/code/tables/emails.md) |  |
| 820 | `saveSentEmail` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `cc_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial`, `direction`, `mailbox_id`) |  |
| 844 | `saveSentEmail` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |
| 889 | `saveSentAttachments` | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime`) |  |
| 926 | `saveSentAttachments` | **update** [emails](/code/tables/emails.md) (`has_attachments`) |  |
| 935 | `saveSentAttachments` | **update** [emails](/code/tables/emails.md) (`body_content`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), [includes/email_log.php](/code/files/includes/email_log.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), `api/includes/mailbox_imap.php`, `api/includes/gmail.php`, [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [encryptValue()](/code/functions/encryptValue.md), [fmt_local()](/code/functions/fmt_local.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md), [mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [mailboxIdentityMismatch()](/code/functions/mailboxIdentityMismatch.md), [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md), [oauthTokenErrorMessage()](/code/functions/oauthTokenErrorMessage.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)

