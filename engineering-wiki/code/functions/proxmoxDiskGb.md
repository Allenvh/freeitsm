# proxmoxDiskGb()

Provisioned disk size in GB, from the config keys (not live usage).

```php
function proxmoxDiskGb(array $cfg, string $type): ?float
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 448.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the VM's config as Proxmox returns it |
| `$type` | `string` |  | 'qemu' or 'lxc' - decides which keys are disks |

**Returns:** `?float`

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxVmDetails()](/code/functions/proxmoxVmDetails.md)

