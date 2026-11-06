# vcdFind()

Elements by local name, ignoring namespaces.

```php
function vcdFind(SimpleXMLElement $node, string $localName): array
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 177.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$node` | `SimpleXMLElement` |  | the element to search below (any depth) |
| `$localName` | `string` |  | the element name without its namespace prefix, e.g. 'VMRecord' |

**Returns:** `array`

## Called from

- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdSyncConnection()](/code/functions/vcdSyncConnection.md), [vcdVmRecord()](/code/functions/vcdVmRecord.md)

