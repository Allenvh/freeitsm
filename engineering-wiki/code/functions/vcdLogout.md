# vcdLogout()

End the session again. Best-effort.

Added at merge time: the PR logged in on every test and every sync and never
logged out, so each scheduled run left a live session behind on the Director
side until it timed out - one more per server per interval.

```php
function vcdLogout(array $c, string $token): void
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row from `vcdLoadConnection()` |
| `$token` | `string` |  | the session token from `vcdLogin()` |

**Returns:** `void`

## Calls

[vcdAcceptJson()](/code/functions/vcdAcceptJson.md), [vcdHttp()](/code/functions/vcdHttp.md)

## Called from

- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdTest()](/code/functions/vcdTest.md), [vcdSyncConnection()](/code/functions/vcdSyncConnection.md)

