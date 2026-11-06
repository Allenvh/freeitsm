# proxmoxSizeToGb()

Proxmox size suffix to GB: K, M, G, T (no suffix means GB).

```php
function proxmoxSizeToGb(float $n, string $unit): float
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 437.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$n` | `float` |  | the size number from a Proxmox size= value |
| `$unit` | `string` |  | its suffix, upper case: 'K', 'M', 'G', 'T', or '' for GB |

**Returns:** `float`

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxVmDetailBlock()](/code/functions/proxmoxVmDetailBlock.md)

