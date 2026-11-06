# asset-management/help-custom-fields.php

> PHP - 311 lines

Recording anything — the custom asset fields and import guide.

Its OWN page rather than a section on asset-management/help.php, because the
feature is genuinely large: a field catalogue, sets, two ways to attach them,
a manual add, a four-step importer and a holding area. Folded into the main
guide it would have been the longest section by a distance and buried the
inventory-script material everybody arrives for.

Same shell, sidebar, scroll-spy and accent as help.php — see
[[project_help_page_house_style]]: the house style is the shared help.css
primitives (.help-container / .help-sidebar / .help-section /
.help-list / .help-note), not a bespoke layout per page.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [asset-management/includes/header.php](/code/files/asset-management/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

