# vcdTest()

The Test button: logs in, reads one VM record and one edge gateway, logs out and returns a one-line result. Throws when the login fails or the VM inventory cannot be read; unreadable edge gateways are only mentioned in the message.

```php
function vcdTest(array $c): string
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 190.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row from `vcdLoadConnection()`, with the password decrypted |

**Returns:** `string`

## Calls

[vcdAcceptJson()](/code/functions/vcdAcceptJson.md), [vcdAcceptXml()](/code/functions/vcdAcceptXml.md), [vcdLogin()](/code/functions/vcdLogin.md), [vcdLogout()](/code/functions/vcdLogout.md), [vcdTryGet()](/code/functions/vcdTryGet.md), [vcdXml()](/code/functions/vcdXml.md)

## Called from

- [api/assets/vcloud_sync.php](/code/files/api/assets/vcloud_sync.php.md)

