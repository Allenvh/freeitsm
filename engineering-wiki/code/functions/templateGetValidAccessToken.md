# templateGetValidAccessToken()

Get a valid access token, refreshing if expired.

```php
function templateGetValidAccessToken(PDO $conn, array $mailbox, array $tokenData): ?string
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 505.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted (azure_tenant_id, azure_client_id, azure_client_secret, oauth_scopes) |
| `$tokenData` | `array` |  | the mailbox's decoded token_data: access_token, refresh_token, expires_at |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 545 | `templateGetValidAccessToken` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |

## Calls

[encryptValue()](/code/functions/encryptValue.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateGraphContext()](/code/functions/templateGraphContext.md)

