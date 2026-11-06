# vcdXml()

Parses an XML response body quietly; null for an empty or unparseable body.

```php
function vcdXml(?string $body): ?SimpleXMLElement
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 161.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `?string` |  | a response body from `vcdTryGet()`; null when the request failed |

**Returns:** `?SimpleXMLElement`

## Called from

- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdTest()](/code/functions/vcdTest.md), [vcdSyncConnection()](/code/functions/vcdSyncConnection.md), [vcdVmRecord()](/code/functions/vcdVmRecord.md)

