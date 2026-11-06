# gmailApiGet()

An authenticated Gmail API GET, returning ['code' => int, 'body' => array|null]. Every caller was writing the same twelve lines of curl.

```php
function gmailApiGet(string $accessToken, string $url): array
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 172.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Google OAuth access token, sent as the Bearer token |
| `$url` | `string` |  | the full Gmail API URL to GET |

**Returns:** `array`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/gmail.php](/code/files/includes/gmail.php.md) - in [gmailListLabels()](/code/functions/gmailListLabels.md), [gmailVerifyFolder()](/code/functions/gmailVerifyFolder.md)

