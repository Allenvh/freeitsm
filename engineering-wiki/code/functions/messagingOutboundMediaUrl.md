# messagingOutboundMediaUrl()

A short-lived, unguessable URL to one outbound attachment's bytes — for a provider (Twilio) that fetches media itself rather than accepting an upload. NOT a general-purpose public file host: the token is bound to this ONE attachment id and expires quickly (checked in media.php), so it only stays reachable for the brief window between sending the message and the provider fetching it — not for as long as the ticket exists.

```php
function messagingOutboundMediaUrl(PDO $conn, int $attachmentId): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 176.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$attachmentId` | `int` |  | the outbound attachment whose bytes the provider will fetch |

**Returns:** `string`

## Calls

[messagingOutboundMediaToken()](/code/functions/messagingOutboundMediaToken.md), [messagingPublicBaseUrl()](/code/functions/messagingPublicBaseUrl.md)

## Called from

- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)

