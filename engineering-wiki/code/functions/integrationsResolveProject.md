# integrationsResolveProject()

Which Jira project should this work item's issue go in?

⚠️ Precedence, most specific first, and it matters:

  1. department  — a team with its own board is the sharpest signal there is
  2. company     — an MSP client with their own project
  3. `*`         — the connection's default
  4. null        — no mapping; the caller keeps whatever it was given

Returning null rather than guessing is the point. An escalation with no
resolvable project must surface as "tell me the project", never as an issue
quietly filed in whatever project happened to be first.

Pure: no database, so the precedence is provable without fixtures.

```php
function integrationsResolveProject(array $maps, ?int $tenantId, ?int $departmentId): ?string
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1073.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$maps` | `array` |  | the connection's mappings as returned by `integrationsLoadMaps()` |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$departmentId` | `?int` |  | the work item's department; null skips the department rule |

**Returns:** `?string`

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

