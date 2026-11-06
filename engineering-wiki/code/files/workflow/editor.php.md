# workflow/editor.php

> PHP - 400 lines

Workflows — editor (visual canvas builder).

Stage 2 of the workflow module: the form-based editor (Stage 1) is gone;
everything happens on a Process Mapper-style dot-grid canvas. A workflow
is a graph of nodes (one trigger + N conditions + M actions) that the
engine reads as: trigger_event + ordered conditions array + ordered
actions array. Order is taken from each node's Y position at save time,
top-to-bottom — so the user just drags nodes around to reorder.

The engine's contract is unchanged. We store x/y as extra fields on each
condition/action JSON object (the engine ignores them). The trigger is
always pinned to top-centre and isn't stored.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [workflow/includes/header.php](/code/files/workflow/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [checklists/search_scoring.js](/code/files/checklists/search_scoring.js.md), [assets/js/workflow-editor.js](/code/files/assets/js/workflow-editor.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

