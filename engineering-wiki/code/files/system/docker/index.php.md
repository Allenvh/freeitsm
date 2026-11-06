# system/docker/index.php

> PHP - 379 lines

System - Docker: turn on HTTPS for the container.

The operator types the name people will use, FreeITSM makes a certificate for
it (includes/docker_tls.php), and the page walks them through the three things
it cannot do for them: DNS, trusting the authority on the PCs, and restarting
the container. Everything is worked out on page load; the only request is
Generate, after which the page reloads.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dk()](/code/functions/dk.md) | 53 | Escape a translated string, then drop pre-built HTML into its {placeholders}. |
| [dkCode()](/code/functions/dkCode.md) | 64 | Escapes a command or path and wraps it in <code> for dropping into a `dk()` placeholder. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [dockerTlsBrowserAddress()](/code/functions/dockerTlsBrowserAddress.md), [dockerTlsState()](/code/functions/dockerTlsState.md), [pageTitle()](/code/functions/pageTitle.md), [storagePersistenceInContainer()](/code/functions/storagePersistenceInContainer.md), [t()](/code/functions/t.md)

