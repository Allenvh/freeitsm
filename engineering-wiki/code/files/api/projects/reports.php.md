# api/projects/reports.php

> API endpoint - 87 lines

Projects - briefing and reports, and the AI project manager (3.2.0). Rules: includes/services/project_reports.php; the AI: includes/projects/ai.php.

GET  ?project_id=N        reports, the latest briefing, whether AI is set up,
                          and what this analyst may do (write / approve)
POST {action, project_id, ...}
     briefing  {refresh?}       the Overview's briefing (cached for 10 minutes)
     draft     {kind, days?}    the AI drafts a highlight / exception / checkpoint / closure report
     save      {id?, kind?, title, body}   write one, or edit a draft
     approve   {id}
     schedule  {schedule, kind}  a draft each week / fortnight / month (3.3.0)
     send      {id, emails[], note?}  email an approved report (3.3.0)
     delete    {id}

The AI only PROPOSES: a draft is a draft until a person approves it.

## The endpoint

| | |
|---|---|
| URL | `api/projects/reports.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php); Signed-in analyst (session check) (via includes/i18n.php) |
| Reads | `GET project_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectReportsAiFail()](/code/functions/projectReportsAiFail.md) | 29 | A provider problem in words the page can show. |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [projectAiReady()](/code/functions/projectAiReady.md), [projectApiBody()](/code/functions/projectApiBody.md), [projectApiFail()](/code/functions/projectApiFail.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectCanDelete()](/code/functions/projectCanDelete.md)

