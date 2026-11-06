# api/projects/templates.php

> API endpoint - 50 lines

Projects - templates (3.2.0). Thin adapter over ProjectTemplatesService.

GET                                       every template, hidden and inactive ones
                                          too (the Settings tab) - needs Templates
POST {action:'save_from_project', project_id, name, description?, parts:[...], id?}
                                          parts: plan, scope, raid, benefits (3.3.0), tolerances, targets;
                                          id = replace that saved template's plan
POST {action:'update', id, name, description?, is_active}
POST {action:'delete', id}
POST {action:'builtin_hidden', key, hidden}

Starting a project FROM a template is not here: it is api/projects/save.php
with `template`, so it passes through the same create rules as any project.
The picker's list comes from api/projects/lookups.php.

## The endpoint

| | |
|---|---|
| URL | `api/projects/templates.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [projectApiBody()](/code/functions/projectApiBody.md), [projectApiFail()](/code/functions/projectApiFail.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectTemplateList()](/code/functions/projectTemplateList.md), [projectTemplatesReady()](/code/functions/projectTemplatesReady.md)

