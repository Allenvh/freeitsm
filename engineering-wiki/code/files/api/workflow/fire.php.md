# api/workflow/fire.php

> API endpoint - 40 lines

API: Manually fire a workflow with a synthetic payload — the "Test fire" button in the editor. Lets a user verify the engine end-to-end without waiting for a real event from the host module.

Body: { id, payload?: object, dry_run?: bool }

With dry_run, the engine evaluates the conditions for real but does NOT
execute the actions — it records what each one would have done, with the
{{variables}} already substituted. Safe to run against a live workflow.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/fire.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

