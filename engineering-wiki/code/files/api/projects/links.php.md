# api/projects/links.php

> API endpoint - 42 lines

Projects joined to the rest of FreeITSM (3.2.0). Every rule lives in includes/projects/links.php; this only routes.

GET  ?project_id=N                   everything linked to a project, per kind
                                     (a kind the analyst cannot use is left out)
GET  ?project_id=N&search=KIND&q=    records that could be linked to it
GET  ?for=KIND&id=N                  the projects a record is linked to (its own page)
GET  ?for=KIND&id=N&pick=1&q=       live projects it could be added to (that the analyst may change)
POST {action:'add'|'remove', project_id, kind, target_id}

## The endpoint

| | |
|---|---|
| URL | `api/projects/links.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |
| Reads | `GET for`, `GET id`, `GET pick`, `GET q`, `GET project_id`, `GET search` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md)

**Calls:** [projectApiBody()](/code/functions/projectApiBody.md), [projectApiFail()](/code/functions/projectApiFail.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectLinkAdd()](/code/functions/projectLinkAdd.md), [projectLinkRemove()](/code/functions/projectLinkRemove.md), [projectLinks()](/code/functions/projectLinks.md), [projectLinkSearch()](/code/functions/projectLinkSearch.md), [projectLinksReady()](/code/functions/projectLinksReady.md), [projectsLinkedTo()](/code/functions/projectsLinkedTo.md), [projectsPickableFor()](/code/functions/projectsPickableFor.md)

