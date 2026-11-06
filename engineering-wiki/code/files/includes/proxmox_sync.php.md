# includes/proxmox_sync.php

> PHP - 552 lines

Proxmox VE sync — pulls VMs, LXC containers, nodes, IPs and MACs from one Proxmox server into proxmox_vms / proxmox_nodes. Several servers are supported: each proxmox_connections row is synced on its own, and its rows never mix with another server's (every row is keyed by connection_id).

Rules carried over from the NetBox-based sync this replaces (PR #167), kept
because each one answers a real incident:
  - a VM's identity is (connection, vmid). Renaming a VM updates it, never duplicates it.
  - IPs for a QEMU VM come only from the QEMU guest agent, and only for interfaces
    whose MAC is one of the VM's own NICs (so docker0 and veth* are skipped).
  - IPs for an LXC container come from ip= on its netN lines; ip=dhcp is skipped.
  - a node that did not answer this cycle is not "removed": its VMs are left alone.
  - a VM is removed only from a LIST THAT CAME BACK - the qemu or lxc list of
    one node. See the TRAP in `proxmoxSyncConnection()`.
  - SAFETY GUARD: if fewer than half of the known VMs were seen this cycle, nothing
    is deleted. A mass deletion has to be confirmed by a healthy cycle, never by a
    cycle where requests failed.

Every request goes through HypervisorHttp (https only, the shared CA bundle).
Read-only: nothing here ever changes anything in Proxmox.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [proxmoxLoadConnection()](/code/functions/proxmoxLoadConnection.md) | 36 | Decrypted connection row, or throws. Never returns the password to a caller that will display it. |
| [proxmoxIsApiToken()](/code/functions/proxmoxIsApiToken.md) | 54 | A username containing "!" is an API token: "user@realm!tokenid", with the token's secret typed into the password field. Token auth needs no login and no CSRF token. |
| [proxmoxLogin()](/code/functions/proxmoxLogin.md) | 64 | Authentication for this server: an API token header, or a login ticket for a user. |
| [proxmoxCall()](/code/functions/proxmoxCall.md) | 89 | One API call. `$auth` is null for the login call itself. Throws with a readable message. |
| [proxmoxTryGet()](/code/functions/proxmoxTryGet.md) | 131 | GET a resource and return its data, or null if this node/VM cannot answer (not an error for the whole sync). |
| [proxmoxTestConnection()](/code/functions/proxmoxTestConnection.md) | 145 | Test the connection without writing anything: login, then read the node list. |
| [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md) | 166 | Sync one server. Returns a summary and records it on the connection row. Never throws for a per-VM or per-node problem: those are counted and reported. |
| [proxmoxVmDetails()](/code/functions/proxmoxVmDetails.md) | 310 | Everything we keep about one VM, from its list entry plus its config (and guest agent for IPs). |
| [proxmoxVmDetailBlock()](/code/functions/proxmoxVmDetailBlock.md) | 389 | The disks, adapters and guest interfaces in the shape the asset detail view reads (the same shape the vCenter sync stores as raw_data), so Proxmox VMs show the same sections without any special casing in the page. |
| [proxmoxSizeToGb()](/code/functions/proxmoxSizeToGb.md) | 437 | Proxmox size suffix to GB: K, M, G, T (no suffix means GB). |
| [proxmoxDiskGb()](/code/functions/proxmoxDiskGb.md) | 448 | Provisioned disk size in GB, from the config keys (not live usage). |
| [proxmoxSaveConnection()](/code/functions/proxmoxSaveConnection.md) | 482 | Save a server from the settings form. Returns its id. |
| [proxmoxConnectionOut()](/code/functions/proxmoxConnectionOut.md) | 536 | A server as the browser may see it: never the password, only whether there is one. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `proxmoxLoadConnection` | read [proxmox_connections](/code/tables/proxmox_connections.md) |  |
| 197 | `proxmoxSyncConnection` | **insert** [proxmox_nodes](/code/tables/proxmox_nodes.md) (`connection_id`, `node_name`, `cluster_name`, `status`, `cpu_cores`, `memory_mb`, `last_seen_datetime`) |  |
| 229 | `proxmoxSyncConnection` | **insert** [proxmox_vms](/code/tables/proxmox_vms.md) (`connection_id`, `vmid`, `vm_type`, `node_name`, `cluster_name`, `name`, `status`, `vcpus`, `memory_mb`, `disk_gb`, `os_type`, `ip_addresses`) |  |
| 255 | `proxmoxSyncConnection` | read [proxmox_vms](/code/tables/proxmox_vms.md) |  |
| 273 | `proxmoxSyncConnection` | **delete** [proxmox_vms](/code/tables/proxmox_vms.md) |  |
| 293 | `proxmoxSyncConnection` | **update** [proxmox_connections](/code/tables/proxmox_connections.md) (`last_sync_datetime`, `last_sync_status`, `last_sync_message`) |  |
| 504 | `proxmoxSaveConnection` | read [proxmox_connections](/code/tables/proxmox_connections.md) |  |
| 519 | `proxmoxSaveConnection` | **update** [proxmox_connections](/code/tables/proxmox_connections.md) (`name`, `host`, `username`, `password_enc`, `verify_ssl`, `is_active`, `sync_interval_minutes`) |  |
| 525 | `proxmoxSaveConnection` | **insert** [proxmox_connections](/code/tables/proxmox_connections.md) (`name`, `host`, `username`, `password_enc`, `verify_ssl`, `is_active`, `sync_interval_minutes`) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/hypervisor_http.php](/code/files/includes/hypervisor_http.php.md)

**Included by:** [api/assets/proxmox_connections.php](/code/files/api/assets/proxmox_connections.php.md), [api/assets/proxmox_sync.php](/code/files/api/assets/proxmox_sync.php.md), [api/assets/sync_hypervisors.php](/code/files/api/assets/sync_hypervisors.php.md), [cron/proxmox_sync.php](/code/files/cron/proxmox_sync.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [encryptValue()](/code/functions/encryptValue.md)

## Traps

> [!WARNING]
> **Line 148, in `proxmoxTestConnection`:** proxmoxCall, not proxmoxTryGet - the real reason must reach the admin. With a token there is no separate login call, so the node list is the first request; swallowing its error turned "could not reach the server" into "check the user's permissions" (found at merge, by Testing a server that does not exist).

> [!WARNING]
> **Line 245, in `proxmoxSyncConnection`:** "gone" means missing from a LIST THAT CAME BACK - one node's qemu list or its lxc list - never merely "on a node that answered". The PR scoped removal by node, so a node that was online but whose qemu list failed (a timeout, a permission on that node) still counted as read, and every VM on it was deleted. The guard did not catch it either: it compared all VMs seen across the cluster with the known ones, so one small node failing in a big cluster stayed under the 50% line. The rows are upserted above first, so a VM that moved to another node is already recorded there before this looks.

> [!WARNING]
> **Line 469:** the password field holds the API token SECRET (or the user's password). It is stored only through `encryptValue()`, never returned to the browser (proxmoxConnectionOut() says has_password and nothing more), and a blank or all-asterisks value on edit keeps the stored one.

