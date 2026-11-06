# api/system/docker_tls.php

> API endpoint - 80 lines

API: HTTPS certificate for the Docker image (System → Docker).

POST {host, ip}         make the certificates (see includes/docker_tls.php)
GET  ?download=ca       the authority certificate, to install on the PCs

Refuses outside a container: on WAMP or a native Linux install the web server
is configured by whoever runs it, and nothing here would reach it.

## The endpoint

| | |
|---|---|
| URL | `api/system/docker_tls.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET download` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dockerTlsJson()](/code/functions/dockerTlsJson.md) | 23 | Sends an HTTP status and the given body as JSON, then stops. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), [includes/docker_tls.php](/code/files/includes/docker_tls.php.md)

**Calls:** [dockerTlsGenerate()](/code/functions/dockerTlsGenerate.md), [dockerTlsNormaliseHost()](/code/functions/dockerTlsNormaliseHost.md), [dockerTlsNormaliseIp()](/code/functions/dockerTlsNormaliseIp.md), [storagePersistenceInContainer()](/code/functions/storagePersistenceInContainer.md), [storagePersistenceStatus()](/code/functions/storagePersistenceStatus.md)

