# messaging_channels

Messaging channel "inbox" — the channel twin of target_mailboxes. One row per WhatsApp number wired to a provider (Twilio / Meta Cloud). Pinned (tenant_id set) or shared intake (NULL, routed by sender phone). `credentials` = encrypted JSON (per-provider shape). See includes/messaging/.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `channel_type` | `VARCHAR(20) NOT NULL DEFAULT 'whatsapp'` |  |
| `provider` | `VARCHAR(20) NOT NULL DEFAULT 'twilio'` |  |
| `phone_number` | `VARCHAR(40) NULL` |  |
| `channel_ref` | `VARCHAR(190) NULL` | What this channel points at, in the provider's own terms, when that is not a phone number: for Slack the workspace (team) id, T0123ABCD. NULL on phone channels, which use phone_number instead. |
| `credentials` | `LONGTEXT NULL` |  |
| `verify_token` | `VARCHAR(255) NULL` |  |
| `ingress_mode` | `VARCHAR(10) NOT NULL DEFAULT 'direct'` |  |
| `relay_secret` | `VARCHAR(255) NULL` |  |
| `tenant_id` | `INT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_inbound_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/messaging/delete_channel.php](/code/files/api/messaging/delete_channel.php.md):46 |  | delete |  |  |
| [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md):221 |  | update | `name`, `channel_type`, `provider`, `phone_number`, `credentials`, `verify_token`, `ingress_mode`, `relay_secret`, `tenant_id`, `is_active` |  |
| [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md):233 |  | insert | `name`, `channel_type`, `provider`, `phone_number`, `credentials`, `verify_token`, `ingress_mode`, `relay_secret`, `tenant_id`, `is_active` |  |
| [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md):243 | `simCleanup` | update | `last_inbound_datetime` |  |
| [api/webchat/delete_widget.php](/code/files/api/webchat/delete_widget.php.md):50 |  | delete |  |  |
| [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md):124 |  | update | `name`, `tenant_id`, `is_active` |  |
| [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md):148 |  | insert | `name`, `channel_type`, `provider`, `tenant_id`, `is_active` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):268 | `ingestInboundMessage` | update | `last_inbound_datetime` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):300 | `webchatInsertInbound` | update | `last_inbound_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/messaging/get_channels.php](/code/files/api/messaging/get_channels.php.md) | L58, L59 |  |
| [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) | testSimulation L176 |  |
| [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md) | L150 |  |
| [api/webchat/get_widgets.php](/code/files/api/webchat/get_widgets.php.md) | L30 |  |
| [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md) | loadMessagingChannel L72, messagingAdminMayAdministerChannel L505 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessChannel L479, resolveTicketTenantForChannel L1193 |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) | webchatLoadByChannel L90, webchatLoadByKey L112 |  |
| [system/integrations/index.php](/code/files/system/integrations/index.php.md) | L58 |  |
| [system/integrations/slack.php](/code/files/system/integrations/slack.php.md) | L45 |  |

