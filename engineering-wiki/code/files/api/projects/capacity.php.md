# api/projects/capacity.php

> API endpoint - 15 lines

GET ?weeks=4|8|12 - capacity (3.3.0): each person's load in the weeks ahead, project work against service-desk duty. Read-only; the rules are in includes/projects/capacity.php.

## The endpoint

| | |
|---|---|
| URL | `api/projects/capacity.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |
| Reads | `GET weeks` |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md)

**Calls:** [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectCapacity()](/code/functions/projectCapacity.md)

