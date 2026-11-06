# integrationsCompaniesCompatible()

⚠️ THE ISOLATION BOUNDARY. Read this before touching it.

May a work item belonging to company `$entityTenantId` be escalated into
`$connectionTenantId`'s tracker?

  connection tenant NULL  → SHARED: serves every company. Anything may go there
                            (an MSP's own Jira, used for all their clients).
  connection tenant set   → PINNED: only that company's work items.

A NULL work item means "unrouted, treated as the Default company's", so it is
resolved to the Default company id before comparing — otherwise a Default
ticket could never reach a connection pinned to Default.

Why this exists: escalation is driven by a WORKFLOW, and a workflow's
conditions are editable by anyone who can author workflows. The wiki is blunt
that company routing must never be a workflow rule
(Multi-Tenancy-Developer-Guide §1 — "a hardcoded synchronous membrane"). This
is the outbound twin of that rule: without it, one mis-scoped workflow
escalates Acme's ticket content into Globex's Jira. So the check lives HERE,
in code, and never in anything an admin can edit.

On a single-company install every call is trivially true, which is correct
rather than a bypass — there is only one company for anything to leak to.

```php
function integrationsCompaniesCompatible(
    ?int $entityTenantId,
    ?int $connectionTenantId,
    ?int $defaultTenantId = null
): bool
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 434.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$entityTenantId` | `?int` |  | the work item's company; null means the Default company |
| `$connectionTenantId` | `?int` |  | the connection's company; null means shared by every company |
| `$defaultTenantId` | `?int` | `null` | the Default company's id, used in place of a null `$entityTenantId` |

**Returns:** `bool`

## Called from

- [api/integrations/connections_for_ticket.php](/code/files/api/integrations/connections_for_ticket.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

