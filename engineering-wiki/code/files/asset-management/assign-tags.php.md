# asset-management/assign-tags.php

> PHP - 208 lines

Assign tags in bulk — the reverse of printing labels.

WHY THIS EXISTS
---------------
Printing labels assumes FreeITSM decides the numbers. Plenty of companies
don't work that way: they buy a box of durable pre-printed sequential tags
(polyester or anodised aluminium — which matters far more than the encoding
once a laptop has been knocked about for three years), stick them on, and
then need the database to agree with the sticker.

THE LOOP THIS IS BUILT AROUND
-----------------------------
Scan what is ALREADY on the machine — the manufacturer's serial barcode,
present on virtually every piece of kit — then scan or type the new tag.
Save, and it resets ready for the next one. A barcode scanner is just a
keyboard that types and presses Enter, so both fields work with one without
any driver, pairing or app.

That ordering matters: identifying the asset from something already printed
on it is what makes this fast. Searching a list for "which laptop is this?"
is the slow part of a tagging day, and this removes it.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Calls:** [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md)

