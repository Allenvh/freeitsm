# vcdVmRecord()

One VM record from the inventory, with its NICs and disks. Null if it has no usable uuid.

```php
function vcdVmRecord(array $c, string $token, SimpleXMLElement $rec): ?array
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 365.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row from `vcdLoadConnection()` |
| `$token` | `string` |  | the session token from `vcdLogin()` |
| `$rec` | `SimpleXMLElement` |  | one VMRecord element from /api/query?type=vm |

**Returns:** `?array`

## Calls

[vcdAcceptXml()](/code/functions/vcdAcceptXml.md), [vcdFind()](/code/functions/vcdFind.md), [vcdTryGet()](/code/functions/vcdTryGet.md), [vcdXml()](/code/functions/vcdXml.md)

## Called from

- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdSyncConnection()](/code/functions/vcdSyncConnection.md)

