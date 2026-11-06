# vcdAcceptJson()

The Accept header for a cloudapi (JSON) call at the connection's API version.

```php
function vcdAcceptJson(string $version): array
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 86.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$version` | `string` |  | the connection's API version, e.g. '38.0' |

**Returns:** `array`

## Called from

- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdLogin()](/code/functions/vcdLogin.md), [vcdLogout()](/code/functions/vcdLogout.md), [vcdTest()](/code/functions/vcdTest.md), [vcdSyncConnection()](/code/functions/vcdSyncConnection.md)

