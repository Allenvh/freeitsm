# projects/index.php

> PHP - 131 lines

Projects - the portfolio (3.2.0).

Every project the analyst's company runs (or every company they can see, in
the "All companies" view) as a wall of cards: each project's own colour and
icon, a health ring with its progress inside, its target date and what is
happening now. Behaviour is in assets/js/projects-portfolio.js; the shared
renderers and the project dialog in assets/js/projects.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [projects/includes/header.php](/code/files/projects/includes/header.php.md), [projects/includes/project_form.php](/code/files/projects/includes/project_form.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/projects.js](/code/files/assets/js/projects.js.md), [assets/js/projects-charts.js](/code/files/assets/js/projects-charts.js.md), [assets/js/projects-portfolio.js](/code/files/assets/js/projects-portfolio.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

