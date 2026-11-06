# proxmoxSyncConnection()

Sync one server. Returns a summary and records it on the connection row. Never throws for a per-VM or per-node problem: those are counted and reported.

```php
function proxmoxSyncConnection(PDO $conn, int $connectionId): array
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `int` |  | the proxmox_connections id to sync |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 197 | `proxmoxSyncConnection` | **insert** [proxmox_nodes](/code/tables/proxmox_nodes.md) (`connection_id`, `node_name`, `cluster_name`, `status`, `cpu_cores`, `memory_mb`, `last_seen_datetime`) |  |
| 229 | `proxmoxSyncConnection` | **insert** [proxmox_vms](/code/tables/proxmox_vms.md) (`connection_id`, `vmid`, `vm_type`, `node_name`, `cluster_name`, `name`, `status`, `vcpus`, `memory_mb`, `disk_gb`, `os_type`, `ip_addresses`) |  |
| 255 | `proxmoxSyncConnection` | read [proxmox_vms](/code/tables/proxmox_vms.md) |  |
| 273 | `proxmoxSyncConnection` | **delete** [proxmox_vms](/code/tables/proxmox_vms.md) |  |
| 293 | `proxmoxSyncConnection` | **update** [proxmox_connections](/code/tables/proxmox_connections.md) (`last_sync_datetime`, `last_sync_status`, `last_sync_message`) |  |

## Calls

[proxmoxCall()](/code/functions/proxmoxCall.md), [proxmoxLoadConnection()](/code/functions/proxmoxLoadConnection.md), [proxmoxLogin()](/code/functions/proxmoxLogin.md), [proxmoxTryGet()](/code/functions/proxmoxTryGet.md), [proxmoxVmDetails()](/code/functions/proxmoxVmDetails.md)

## Called from

- [api/assets/proxmox_sync.php](/code/files/api/assets/proxmox_sync.php.md)
- [cron/proxmox_sync.php](/code/files/cron/proxmox_sync.php.md)

## Traps

> [!WARNING]
> **Line 245, in `proxmoxSyncConnection`:** "gone" means missing from a LIST THAT CAME BACK - one node's qemu list or its lxc list - never merely "on a node that answered". The PR scoped removal by node, so a node that was online but whose qemu list failed (a timeout, a permission on that node) still counted as read, and every VM on it was deleted. The guard did not catch it either: it compared all VMs seen across the cluster with the known ones, so one small node failing in a big cluster stayed under the 50% line. The rows are upserted above first, so a VM that moved to another node is already recorded there before this looks.

