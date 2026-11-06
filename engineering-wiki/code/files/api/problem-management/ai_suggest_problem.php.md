# api/problem-management/ai_suggest_problem.php

> API endpoint - 56 lines

API: scan recent open incidents in the active company and propose candidate problems (groups of incidents that likely share a root cause). Suggestion only — the analyst confirms before anything is created. Uses the problem_ai key.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/ai_suggest_problem.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [parseClaudeJson()](/code/functions/parseClaudeJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketAiExclusionSql()](/code/functions/ticketAiExclusionSql.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

