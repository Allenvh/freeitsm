# network-mapper/diagram.php

> PHP - 1717 lines

Network Mapper — Diagram editor (chunk B).

Renders the editor shell and hands off to assets/js/network-mapper.js for
data loading, autosave, save-as-new-version, and (in later chunks) the
drag/bind/connector logic. The PHP side here is intentionally thin — most
of the live behaviour lives client-side.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [network-mapper/includes/header.php](/code/files/network-mapper/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/vendor/html2canvas.min.js](/code/files/assets/js/vendor/html2canvas.min.js.md), [assets/js/vendor/jspdf.umd.min.js](/code/files/assets/js/vendor/jspdf.umd.min.js.md), [assets/js/network-mapper-icons.js](/code/files/assets/js/network-mapper-icons.js.md), [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

