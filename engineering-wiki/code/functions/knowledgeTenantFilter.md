# knowledgeTenantFilter()

Scope a Knowledge query to what an ANALYST should see right now: the company they have switched to, plus everything shared.

The Knowledge counterpart of `activeTenantFilter()` — it follows the header's
company switcher exactly as tickets/assets/changes do, so switching company
means the same thing everywhere. It differs only in that NULL stays visible
from every company rather than only from Default (see
knowledgeTenantFilterForCompany above for why).

```php
function knowledgeTenantFilter(PDO $conn, int $analystId, string $alias = 'a'): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 993.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'a'` | the knowledge_articles alias in the calling query; '' for none |

**Returns:** array [sqlFragment, params]

## Calls

[getActiveTenantId()](/code/functions/getActiveTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeTenantFilterForCompany()](/code/functions/knowledgeTenantFilterForCompany.md)

*Nothing calls this function by name.*

