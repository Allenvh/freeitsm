# includes/hypervisor_http.php

> PHP - 115 lines

The one HTTP door for the hypervisor syncs (Proxmox VE, VMware Cloud Director).

Added when PR #167 was merged. Each engine had its own cURL block; they now
share this one, for three reasons:

  1. HTTPS ONLY. Every request carries a credential - a Proxmox API token
     secret, or a Director password in Basic auth - so plain http would send
     it across the network readable by anyone on the path. `validateHost()`
     refuses http:// when a server is saved, and `request()` refuses it again
     in case a row was written some other way.

  2. THE CA BUNDLE. With "Verify certificate" ticked, the PR set
     CURLOPT_SSL_VERIFYPEER but no CURLOPT_CAINFO, so on WAMP and some
     Docker and Linux hosts a perfectly valid certificate failed with
     "unable to get local issuer certificate" - GH #129 again. A ticked box
     now goes through sslApplyCurl(`$ch`, true), the same helper as every other
     integration: verification forced on, with the install's bundle.

  3. TESTS. Neither product can be run in CI. `$testTransport` lets
     tests/hypervisor-sync.php answer as a stand-in Proxmox or Director and
     drive the real sync code - including every rule about what gets deleted.

## Classes

- [HypervisorHttp](/code/classes/HypervisorHttp.md) (class, line 28)

## How it hangs together

**Includes:** [includes/ssl.php](/code/files/includes/ssl.php.md)

**Included by:** [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)

**Calls:** [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Traps

> [!WARNING]
> **Line 37:** keep this https-only. The credential travels with every request.

