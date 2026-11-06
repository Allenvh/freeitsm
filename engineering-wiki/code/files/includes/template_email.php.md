# includes/template_email.php

> PHP - 774 lines

Email Template Engine

Sends automated emails triggered by ticket events (new ticket, assigned, closed).
Templates are configured in Tickets > Settings > Templates.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [templateEventAudience()](/code/functions/templateEventAudience.md) | 30 | Who an event's email is addressed to: 'requester' or 'analyst'. |
| [sendTemplateEmail()](/code/functions/sendTemplateEmail.md) | 47 | Main entry point — send a template email for a ticket event. Returns silently if no active template exists or no mailbox is found. Never throws — errors go to `error_log()`. |
| [getActiveTemplate()](/code/functions/getActiveTemplate.md) | 234 | Get the first active template for a given event trigger. |
| [templateSelectForRecipient()](/code/functions/templateSelectForRecipient.md) | 266 | Choose the template for an event and a recipient, and say WHY it was chosen. |
| [templateRulesByTemplate()](/code/functions/templateRulesByTemplate.md) | 322 | Sender rules for the given templates, keyed by template id, values lowercased. |
| [buildTicketMergeData()](/code/functions/buildTicketMergeData.md) | 349 | Build merge data from ticket, analyst, and department tables. |
| [resolveMergeCodes()](/code/functions/resolveMergeCodes.md) | 431 | Replace [merge_code] placeholders with actual values. |
| [buildTemplateEmailBody()](/code/functions/buildTemplateEmailBody.md) | 455 | Wraps a template email body in the house HTML, the "Please reply above this line" marker and the hidden SDREF tag that threads a reply back to the ticket. |
| [templateGetMailboxForTicket()](/code/functions/templateGetMailboxForTicket.md) | 481 | Get the mailbox associated with a ticket's emails. |
| [templateGetValidAccessToken()](/code/functions/templateGetValidAccessToken.md) | 505 | Get a valid access token, refreshing if expired. |
| [templateGraphContext()](/code/functions/templateGraphContext.md) | 577 | Everything a Microsoft send needs for one mailbox: a valid access token and the Graph base path to send from. Returns null if no usable token could be obtained. |
| [templateSendViaGraph()](/code/functions/templateSendViaGraph.md) | 626 | Send an email message via Microsoft Graph API. |
| [internalTicketEmail()](/code/functions/internalTicketEmail.md) | 676 | Send an internal notification ABOUT a ticket to staff addresses - an SLA alert, a closure with fields missing - from the ticket's own mailbox, or the first active mailbox when the ticket has none (a ticket raised by hand or through the API never touched one). |
| [templateSaveSentEmail()](/code/functions/templateSaveSentEmail.md) | 748 | Save the sent template email to the emails table. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 269 | `templateSelectForRecipient` | read [ticket_email_templates](/code/tables/ticket_email_templates.md) |  |
| 327 | `templateRulesByTemplate` | read [ticket_email_template_rules](/code/tables/ticket_email_template_rules.md) |  |
| 350 | `buildTicketMergeData` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [users](/code/tables/users.md), read [analysts](/code/tables/analysts.md), read [departments](/code/tables/departments.md) |  |
| 482 | `templateGetMailboxForTicket` | read [emails](/code/tables/emails.md), read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 545 | `templateGetValidAccessToken` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |
| 679 | `internalTicketEmail` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 750 | `templateSaveSentEmail` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial`, `direction`, `mailbox_id`) |  |
| 767 | `templateSaveSentEmail` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/email_log.php](/code/files/includes/email_log.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), [includes/gmail.php](/code/files/includes/gmail.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md)

**Included by:** [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md), [api/tickets/get_email_templates.php](/code/files/api/tickets/get_email_templates.php.md), [api/tickets/simulate_email_template.php](/code/files/api/tickets/simulate_email_template.php.md), [includes/csat.php](/code/files/includes/csat.php.md), [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md), [includes/reply_templates.php](/code/files/includes/reply_templates.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Calls:** [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [emailLogSkipped()](/code/functions/emailLogSkipped.md), [encryptValue()](/code/functions/encryptValue.md), [fmt_local()](/code/functions/fmt_local.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md), [mailboxIsAppOnly()](/code/functions/mailboxIsAppOnly.md), [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

