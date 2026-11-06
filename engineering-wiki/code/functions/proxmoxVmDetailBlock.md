# proxmoxVmDetailBlock()

The disks, adapters and guest interfaces in the shape the asset detail view reads (the same shape the vCenter sync stores as raw_data), so Proxmox VMs show the same sections without any special casing in the page.

```php
function proxmoxVmDetailBlock(array $cfg, string $type, array $guestIfaces): array
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 389.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the VM's config as Proxmox returns it (disk, net and other keys) |
| `$type` | `string` |  | 'qemu' or 'lxc' |
| `$guestIfaces` | `array` |  | the guest agent's interfaces, shaped by `proxmoxVmDetails()`; [] when there is no agent |

**Returns:** `array`

## Calls

[proxmoxSizeToGb()](/code/functions/proxmoxSizeToGb.md)

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxVmDetails()](/code/functions/proxmoxVmDetails.md)

