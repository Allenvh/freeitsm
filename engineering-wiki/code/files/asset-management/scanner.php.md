# asset-management/scanner.php

> PHP - 617 lines

Continuous camera scanner for asset labels.

WHY THIS EXISTS. A phone's own camera already opens an asset label — the QR
holds a plain HTTPS URL precisely so it needs no app. That is the right tool
for looking one thing up. It is the wrong tool for a stocktake: to update
forty assets you leave FreeITSM, open the camera, tap the banner, edit, then
repeat the whole dance forty times. This page keeps the camera on screen so
the loop is scan → applied → scan, with the app never leaving the screen.

MOBILE-FIRST, NOT MOBILE-ADAPTED — same footing as scan.php and
assign-tags.php: a purpose-built narrow surface with its own small sheet,
not the desktop module squeezed down. (It works on a desktop with a webcam,
but nobody will.)

⚠️ SECURE CONTEXT. getUserMedia is refused outside HTTPS (localhost aside),
so on a plain http:// LAN address the camera cannot start at all. That is a
browser rule, not ours, and the page says so plainly rather than showing a
dead black rectangle. Typing a tag or serial still works there.

DECODING. Chrome/Android has a native BarcodeDetector; iOS Safari does not.
The native one is used when present and the bundled jsQR is fetched ONLY as
the fallback, so Android never pays for a decoder it doesn't need.

ENGLISH-ONLY, consistent with its siblings scan.php and assign-tags.php. The
translated surfaces (the module nav, the list header link) carry proper keys.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Calls:** [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

