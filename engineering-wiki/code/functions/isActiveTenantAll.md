# isActiveTenantAll()

Is the analyst looking at every company they may see, rather than just one?

```php
function isActiveTenantAll(PDO $conn): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 667.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)
- [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md)
- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)
- [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)
- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in `KnowledgeViewer::forAnalyst`
- [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md) - in [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [ticketTenantFilter()](/code/functions/ticketTenantFilter.md), [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md)

