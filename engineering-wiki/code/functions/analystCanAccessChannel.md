# analystCanAccessChannel()

May this analyst administer this *messaging channel* (WhatsApp number, web chat widget) by its owning company?

⚠️ NULL tenant_id does NOT mean "Default-owned" here — unlike tickets,
changes and assets. A channel mirrors a MAILBOX: NULL = **shared intake**, a
channel serving every company whose inbound messages are routed per-sender by
`resolveTicketTenantForChannel()`. A shared channel is nobody's private
property, so anyone holding the messaging capability may administer it; only
a channel PINNED to a company is restricted to analysts who can reach that
company.

This gates WRITES only. The channel *list* is deliberately install-wide (the
same tier as mailbox connections — MSP-global settings), and it returns no
credentials, only a has_credentials flag.

Single-company install → always true; unknown id → false; part-migrated table
→ true.

```php
function analystCanAccessChannel(PDO $conn, int $analystId, $channelId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 470.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` |  |  | the messaging channel (messaging_channels.id), any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 479 | `analystCanAccessChannel` | read [messaging_channels](/code/tables/messaging_channels.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/messaging/delete_channel.php](/code/files/api/messaging/delete_channel.php.md)
- [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md)
- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [api/messaging/teams_package.php](/code/files/api/messaging/teams_package.php.md)
- [api/messaging/telegram_connect.php](/code/files/api/messaging/telegram_connect.php.md)
- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md)
- [api/webchat/delete_widget.php](/code/files/api/webchat/delete_widget.php.md)
- [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md)

