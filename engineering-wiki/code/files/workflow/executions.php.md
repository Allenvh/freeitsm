# workflow/executions.php

> PHP - 148 lines

Workflows — the execution log.

Every run of every workflow, filterable and paginated, with a drill-down into
the step log and the trigger payload snapshot.

Why this page exists: the only view of a run used to be the editor's "Recent
runs" panel — the last 20 runs of ONE workflow. That's the wrong shape for the
question you actually ask when something goes wrong ("what failed, and why?"),
because you don't yet know which workflow to open. This starts from the
failure and works back.

Deep-linkable: ?status=failed&from=2026-07-12 — which is how the Watchtower
card and the "N failures" tallies link in.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [workflow/includes/header.php](/code/files/workflow/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/workflow-executions.js](/code/files/assets/js/workflow-executions.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

