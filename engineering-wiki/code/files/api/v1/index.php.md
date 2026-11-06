# api/v1/index.php

> API endpoint - 215 lines

FreeITSM REST API v1 — front controller.

Routing: the path comes from mod_rewrite (see .htaccess) or directly via
PATH_INFO, so both of these are equivalent:
  GET /api/v1/tickets/42
  GET /api/v1/index.php/tickets/42     (works with no rewrite module)

Authentication: every route requires an API key (System > API) sent as
  Authorization: Bearer fitsm_xxxxxxxx...
and each route additionally requires the permission listed in its route
entry — keys are granular, starting from zero permissions.

## The endpoint

| | |
|---|---|
| URL | `api/v1/index.php` |
| Protected by | REST API key |
| Reads | `GET path` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiHandleRoot()](/code/functions/apiHandleRoot.md) | 100 | GET / — version + endpoint index. |
| [apiHandlePing()](/code/functions/apiHandlePing.md) | 194 | GET /ping — auth check + what this key can do. |

## How it hangs together

**Includes:** [api/v1/lib/bootstrap.php](/code/files/api/v1/lib/bootstrap.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md), [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md), [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), [api/v1/lib/routes.php](/code/files/api/v1/lib/routes.php.md)

**Calls:** [apiAuthenticate()](/code/functions/apiAuthenticate.md), [apiError()](/code/functions/apiError.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiJsonBody()](/code/functions/apiJsonBody.md), [apiRequirePermission()](/code/functions/apiRequirePermission.md), [apiRespond()](/code/functions/apiRespond.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getTenantById()](/code/functions/getTenantById.md)

