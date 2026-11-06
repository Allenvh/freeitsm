# requireAnyModuleAccessJson()

Hard gate for an endpoint shared by SEVERAL modules: allow if the analyst can use ANY of them, refuse otherwise.

Some features are genuinely reachable from more than one place. Asking the knowledge
base a question is offered both in Knowledge and from the ticket reading pane, so
requiring 'knowledge' alone would break the inbox for an analyst who only works
tickets — while requiring nothing at all (which is what these endpoints did) lets
anyone with a login spend your AI budget.

"Any of these modules" is the honest gate. Use it sparingly: if an endpoint belongs to
ONE module, use `requireModuleAccessJson()`.

```php
function requireAnyModuleAccessJson(array $moduleKeys, ?PDO $conn = null): void
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 333.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$moduleKeys` | `array` |  | the module keys that may use the endpoint, e.g. ['knowledge', 'tickets'] |
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [api/calendar/get_categories.php](/code/files/api/calendar/get_categories.php.md)
- [api/calendar/get_events.php](/code/files/api/calendar/get_events.php.md)
- [api/cost-centres/options.php](/code/files/api/cost-centres/options.php.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/knowledge/knowledge_article.php](/code/files/api/knowledge/knowledge_article.php.md)
- [api/knowledge/knowledge_articles.php](/code/files/api/knowledge/knowledge_articles.php.md)
- [api/knowledge/knowledge_tags.php](/code/files/api/knowledge/knowledge_tags.php.md)
- [api/settings/get_system_settings.php](/code/files/api/settings/get_system_settings.php.md)
- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)
- [api/tasks/contractors.php](/code/files/api/tasks/contractors.php.md)
- [api/tasks/list.php](/code/files/api/tasks/list.php.md)
- [api/tickets/get_analyst_teams.php](/code/files/api/tickets/get_analyst_teams.php.md)
- [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md)
- [api/tickets/get_departments.php](/code/files/api/tickets/get_departments.php.md)
- [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md)
- [api/tickets/get_person.php](/code/files/api/tickets/get_person.php.md)
- [api/tickets/get_team_analysts.php](/code/files/api/tickets/get_team_analysts.php.md)
- [api/tickets/get_team_departments.php](/code/files/api/tickets/get_team_departments.php.md)
- [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)
- [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)

