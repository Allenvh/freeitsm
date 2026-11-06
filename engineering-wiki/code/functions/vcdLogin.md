# vcdLogin()

Session: Basic auth as "user@org", and the access token comes back in a response header.

```php
function vcdLogin(array $c): string
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 106.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row from `vcdLoadConnection()`, with the password decrypted |

**Returns:** `string`

## Calls

[vcdAcceptJson()](/code/functions/vcdAcceptJson.md), [vcdHttp()](/code/functions/vcdHttp.md)

## Called from

- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdTest()](/code/functions/vcdTest.md), [vcdSyncConnection()](/code/functions/vcdSyncConnection.md)

