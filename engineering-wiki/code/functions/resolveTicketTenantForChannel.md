# resolveTicketTenantForChannel()

Decide which company a NEW inbound messaging-channel ticket (WhatsApp etc.) belongs to. The channel twin of `resolveTicketTenantForEmail()`:

1. Single-company install → the Default company.
  2. Pinned channel (messaging_channels.tenant_id set) → that company; the
     sender phone is ignored. (A pinned channel is also the reply identity.)
  3. Shared channel (tenant_id NULL) → the exact sender phone is mapped to a
     company (tenant_channel_senders) → that company. Phone numbers have no
     domain, so there is no domain-match step — an unmapped sender goes to
     triage (NULL), exactly like an unmatched email, and surfaces under Default.

Defensive (try/catch) so it is safe on a part-migrated install.

```php
function resolveTicketTenantForChannel(PDO $conn, $channelId, string $fromIdentifier): ?int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1185.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` |  |  | the receiving channel's id |
| `$fromIdentifier` | `string` |  | the sender's phone, normalised ('+digits') |

**Returns:** int|null a tenant id, or NULL meaning "triage".

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1193 | `resolveTicketTenantForChannel` | read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 1208 | `resolveTicketTenantForChannel` | read [tenant_channel_senders](/code/tables/tenant_channel_senders.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md), [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)

