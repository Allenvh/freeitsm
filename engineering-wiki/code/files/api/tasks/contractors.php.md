# api/tasks/contractors.php

> API endpoint - 30 lines

API: Tasks - the suppliers (and their active contacts) a task can be given to (3.3.0). GET only. Suppliers live in Contracts, so choosing one needs the Contracts module as well as Tasks; seeing a contractor's name on a task you can already see does not. Rules: includes/task_contractors.php.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/contractors.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Called from | [assets/js/projects-tools.js](/code/files/assets/js/projects-tools.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [tasksContractorChoices()](/code/functions/tasksContractorChoices.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md)

