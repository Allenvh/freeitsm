# portalThirdPartyPolicy()

The configured policy: Tickets → Settings → Privacy.

Defaults to HIDE when never saved. Ed's explicit call: showing a supplier
forward to the customer is treated as the bug it is, so a fresh or upgrading
install is protected without anyone having to find the setting first.

```php
function portalThirdPartyPolicy(PDO $conn): string
```

Defined in [includes/portal_visibility.php](/code/files/includes/portal_visibility.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 77 | `portalThirdPartyPolicy` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md)
- [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md)

