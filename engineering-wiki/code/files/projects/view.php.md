# projects/view.php

> PHP - 741 lines

Projects - one project (3.2.0).

A banner in the project's own colours (icon, name, status, how it is run, the
health ring), then the tabs: Overview (the goal, the numbers, what is
happening now and coming up, milestones, recent activity), Plan (the phases /
stages / sprints with their tasks, drag a task between them, add tasks in
place), Timeline (the plan as a Gantt chart - projects-timeline.js), a tab per
tool, and History. Behaviour is in assets/js/projects-view.js.

The project is loaded by the page's script through api/projects/get.php,
which applies the company scope - an id the analyst may not see shows the
not-found panel, never the project.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/documents_panel.php](/code/files/includes/documents_panel.php.md), [projects/includes/header.php](/code/files/projects/includes/header.php.md), [projects/includes/project_form.php](/code/files/projects/includes/project_form.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/projects.js](/code/files/assets/js/projects.js.md), [assets/js/projects-tools.js](/code/files/assets/js/projects-tools.js.md), [assets/js/projects-targets.js](/code/files/assets/js/projects-targets.js.md), [assets/js/projects-budget.js](/code/files/assets/js/projects-budget.js.md), [assets/js/projects-control.js](/code/files/assets/js/projects-control.js.md), [assets/js/projects-intake.js](/code/files/assets/js/projects-intake.js.md), [assets/js/projects-benefits.js](/code/files/assets/js/projects-benefits.js.md), [assets/js/projects-gatecheck.js](/code/files/assets/js/projects-gatecheck.js.md), [assets/js/projects-insights.js](/code/files/assets/js/projects-insights.js.md), [assets/js/projects-toolbox.js](/code/files/assets/js/projects-toolbox.js.md), [assets/js/projects-assistant.js](/code/files/assets/js/projects-assistant.js.md), [assets/js/projects-reports.js](/code/files/assets/js/projects-reports.js.md), [assets/js/projects-charts.js](/code/files/assets/js/projects-charts.js.md), [assets/js/projects-milestones.js](/code/files/assets/js/projects-milestones.js.md), [assets/js/projects-timeline.js](/code/files/assets/js/projects-timeline.js.md), [assets/js/projects-view.js](/code/files/assets/js/projects-view.js.md), [assets/js/projects-templates.js](/code/files/assets/js/projects-templates.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [documentsPanelAssets()](/code/functions/documentsPanelAssets.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

