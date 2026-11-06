# api/problem-management/ai_root_cause.php

> API endpoint - 75 lines

API: AI-draft a root cause + workaround for a problem from its linked incidents. Does not save — returns a draft for the analyst to review. Uses the problem_ai key.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/ai_root_cause.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [problems](/code/tables/problems.md) |  |
| 35 |  | read [emails](/code/tables/emails.md), read [problem_tickets](/code/tables/problem_tickets.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [parseClaudeJson()](/code/functions/parseClaudeJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketAiExclusionSql()](/code/functions/ticketAiExclusionSql.md)

