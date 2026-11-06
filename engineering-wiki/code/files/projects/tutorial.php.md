# projects/tutorial.php

> PHP - 62 lines

Projects - How it fits together (3.3.0): the interactive tutorial on a page of its own, full width. The map and the walk-through are assets/js/projects-tour.js (the same code Projects -> Help used to carry inline); ?walk=1 opens on the walk. Linked from Help, the empty portfolio and the Toolbox.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [projects/includes/header.php](/code/files/projects/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/projects.js](/code/files/assets/js/projects.js.md), [assets/js/projects-tour.js](/code/files/assets/js/projects-tour.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

