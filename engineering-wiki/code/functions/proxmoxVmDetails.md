# proxmoxVmDetails()

Everything we keep about one VM, from its list entry plus its config (and guest agent for IPs).

```php
function proxmoxVmDetails(
    array $c,
    array $auth,
    string $type,
    string $node,
    int $vmid,
    array $listEntry,
    string $clusterName
): array
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 310.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row |
| `$auth` | `array` |  | what `proxmoxLogin()` returned |
| `$type` | `string` |  | 'qemu' for a VM or 'lxc' for a container |
| `$node` | `string` |  | the node name the VM is on |
| `$vmid` | `int` |  | the VM's Proxmox id |
| `$listEntry` | `array` |  | the VM's entry from the node's qemu or lxc list (name, status, maxmem) |
| `$clusterName` | `string` |  | the cluster's name, or the node's when it is not in a cluster |

**Returns:** `array`

## Calls

[proxmoxDiskGb()](/code/functions/proxmoxDiskGb.md), [proxmoxTryGet()](/code/functions/proxmoxTryGet.md), [proxmoxVmDetailBlock()](/code/functions/proxmoxVmDetailBlock.md)

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md)

