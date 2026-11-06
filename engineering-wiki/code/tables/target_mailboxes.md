# target_mailboxes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `provider` | `VARCHAR(20) NOT NULL DEFAULT 'microsoft'` |  |
| `azure_tenant_id` | `TEXT NOT NULL` |  |
| `azure_client_id` | `TEXT NOT NULL` |  |
| `azure_client_secret` | `TEXT NOT NULL` |  |
| `oauth_redirect_uri` | `TEXT NOT NULL` |  |
| `oauth_scopes` | `VARCHAR(500) NOT NULL DEFAULT 'openid email offline_access User.Read Mail.Read Mail.ReadWrite Mail.Send'` |  |
| `imap_server` | `TEXT NOT NULL` |  |
| `imap_port` | `INT NOT NULL DEFAULT 993` |  |
| `imap_encryption` | `VARCHAR(10) NOT NULL DEFAULT 'ssl'` |  |
| `imap_username` | `TEXT NULL` | Basic IMAP / SMTP mailboxes: username + password auth (no OAuth). Encrypted columns are TEXT NULL (empty on Microsoft/Google mailboxes). |
| `imap_password` | `TEXT NULL` |  |
| `smtp_server` | `TEXT NULL` |  |
| `smtp_port` | `INT NULL DEFAULT 587` |  |
| `smtp_encryption` | `VARCHAR(10) NULL DEFAULT 'tls'` |  |
| `smtp_username` | `TEXT NULL` | Separate sending credentials; blank means "use the IMAP ones". |
| `smtp_password` | `TEXT NULL` |  |
| `target_mailbox` | `TEXT NOT NULL` |  |
| `auth_mode` | `VARCHAR(20) NOT NULL DEFAULT 'delegated'` | 'delegated' = OAuth sign-in (acts as the signed-in user, /me); 'app_only' = client-credentials (the app reads the specific /users/<target_mailbox>). |
| `authenticated_as` | `VARCHAR(255) NULL` | The account actually authenticated in delegated mode (the primary address, for display). Compared against target_mailbox to catch "reading the wrong inbox". NULL = not yet authenticated / needs (re)authentication. |
| `authenticated_addresses` | `TEXT NULL` | JSON array of EVERY address the authenticated mailbox owns (primary SMTP, UPN and aliases, from Graph proxyAddresses). The target matches if it's any of these — so an alias (e.g. ed@ on the edmozley@ mailbox) is accepted, not flagged. |
| `token_data` | `LONGTEXT NULL` |  |
| `email_folder` | `VARCHAR(100) NOT NULL DEFAULT 'INBOX'` |  |
| `max_emails_per_check` | `INT NOT NULL DEFAULT 10` |  |
| `mark_as_read` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `rejected_action` | `VARCHAR(20) NOT NULL DEFAULT 'delete'` |  |
| `imported_action` | `VARCHAR(20) NOT NULL DEFAULT 'delete'` |  |
| `imported_folder` | `VARCHAR(100) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `tenant_id` | `INT NULL` |  |
| `default_origin_id` | `INT NULL` | The ticket origin stamped on tickets this mailbox opens (#79). Stored as an ID so renaming the origin can't break it; NULL = don't set one. |
| `health_dismissed` | `TEXT NULL` | JSON array of mailbox-health warning keys the admin has acknowledged. |
| `last_error` | `TEXT NULL` | What the last check said when it did NOT work — the provider's own words, so a mailbox that has stopped collecting can say why instead of just going quiet. Cleared on the next clean check. |
| `last_error_datetime` | `DATETIME NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_checked_datetime` | `DATETIME NULL` |  |
| `default_sensitivity` | `VARCHAR(20) NOT NULL DEFAULT 'normal'` | 'confidential' = every ticket arriving here is confidential (discussion #62). Needed on its own: emailed tickets get no department, so an HR mailbox is the only thing that can protect a grievance the moment it lands. |
| `oversize_policy` | `TEXT NULL` | This mailbox's own rules for emails over the attachment size limits (GH #170) - JSON, see includes/attachment_limits.php. NULL = use the defaults in Tickets -> Settings -> Mailboxes. |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md):357 | `saveTokenData` | update | `token_data` |  |
| [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md):384 | `saveTokenData` | update | `token_data` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1738 |  | update | `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2166 |  | update |  |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):512 | `saveTokenData` | update | `token_data` |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):544 | `recordMailboxCheckOutcome` | update | `last_checked_datetime`, `last_error`, `last_error_datetime` |  |
| [api/tickets/delete_mailbox.php](/code/files/api/tickets/delete_mailbox.php.md):45 |  | delete |  |  |
| [api/tickets/mailbox_logout.php](/code/files/api/tickets/mailbox_logout.php.md):33 |  | update | `token_data` |  |
| [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md):279 |  | update |  |  |
| [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md):296 |  | update | `authenticated_as`, `authenticated_addresses`, `token_data` |  |
| [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md):325 |  | insert |  |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md):319 | `saveTokenData` | update | `token_data` |  |
| [api/tickets/set_mailbox_health_dismissed.php](/code/files/api/tickets/set_mailbox_health_dismissed.php.md):88 |  | update | `health_dismissed` |  |
| [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md):171 |  | update | `token_data` |  |
| [includes/gmail.php](/code/files/includes/gmail.php.md):68 | `gmailRefreshAccessToken` | update | `token_data` |  |
| [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md):68 | `mailboxAppOnlyToken` | update | `token_data` |  |
| [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md):236 | `mailboxBackfillIdentity` | update | `authenticated_as`, `authenticated_addresses` |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md):545 | `templateGetValidAccessToken` | update | `token_data` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md) | L51 |  |
| [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md) | L105, sendMailboxEmail L188 |  |
| [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md) | L121, sendMailboxEmail L215 |  |
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) | L143 |  |
| [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md) | L51 |  |
| [api/system/get_tenant_email_routing.php](/code/files/api/system/get_tenant_email_routing.php.md) | L80, L89 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L52 |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) | getMailboxConfig L409, saveEmailToDatabase L1186 |  |
| [api/tickets/delete_mailbox.php](/code/files/api/tickets/delete_mailbox.php.md) | L34 |  |
| [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md) | L29, L31 |  |
| [api/tickets/get_sendable_mailboxes.php](/code/files/api/tickets/get_sendable_mailboxes.php.md) | L44, L52 |  |
| [api/tickets/get_triage.php](/code/files/api/tickets/get_triage.php.md) | L38 |  |
| [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md) | L170, L223 |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) | getMailboxForTicket L211 |  |
| [api/tickets/set_mailbox_health_dismissed.php](/code/files/api/tickets/set_mailbox_health_dismissed.php.md) | L49 |  |
| [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md) | L38 |  |
| [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) | calendarSyncLoadConnection L156 |  |
| [includes/feature_bingo/cards/tickets-channels.php](/code/files/includes/feature_bingo/cards/tickets-channels.php.md) | L1017 |  |
| [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) | ssGetSendingMailbox L17 |  |
| [includes/self_service_mailboxes.php](/code/files/includes/self_service_mailboxes.php.md) | selfServiceMailboxes L37 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::createTicket L173 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_get_first_active_mailbox L409 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | templateGetMailboxForTicket L482, internalTicketEmail L679 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | resolveTicketTenantForEmail L1117 |  |
| [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) | ticketSensitivityReady L54, ticketSensitivityApplyDefaults L129 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_send_email L2469 |  |

