# system/feature-bingo/index.php

> PHP - 308 lines

System — Feature Bingo.

One card per thing FreeITSM can do, with a star that lights once it is set up
on this install, and an explainer of what it is and why it is worth doing.
Most people use a fraction of what they have; this is the map of the rest.

The cards are code (includes/feature_bingo/cards/), evaluated by
includes/feature_bingo.php; api/system/feature_bingo.php serves them and
records "Not for us".

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

