# getTenantConfigRowsByCompany()

The same config list resolved SEPARATELY FOR EVERY COMPANY the analyst may see.

---------------------------------------------------------------------------
WHY THIS IS NOT JUST "getTenantConfigRows WITHOUT THE FILTER"
---------------------------------------------------------------------------
Ticket types, origins and categories are a "global default + the company's own
+ the company may HIDE a global" model. A union across companies therefore
cannot be expressed as one flat list: School A may have hidden a global type
that School B still uses, so the same row belongs in one company's list and
not the other's. Flattening loses that and would offer a type on a ticket
whose company had deliberately hidden it.

So this resolves the list once per company and returns a map keyed by company
id. The reading pane picks the entry for the TICKET's company — which is the
whole point: in the consolidated view the lists cannot be a property of the
page any more, because the page holds several companies' tickets at once.

Only worth calling in the consolidated view. One query per company, once per
page load; at the three or four companies this feature exists for, that is
cheaper than the alternative of re-fetching every time a ticket is opened.

```php
function getTenantConfigRowsByCompany(
    PDO $conn,
    int $analystId,
    string $table,
    string $entityType,
    string $cols = '*',
    string $activeWhere = '',
    string $orderBy = 'display_order, name'
): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 712.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the config table, e.g. 'ticket_types' - a developer literal |
| `$entityType` | `string` |  | the key used in tenant_config_hidden, e.g. 'ticket_type' |
| `$cols` | `string` | `'*'` | the columns to select (default '*') |
| `$activeWhere` | `string` | `''` | an extra filter with no leading AND, e.g. 'is_active = 1'; '' for none |
| `$orderBy` | `string` | `'display_order, name'` | the ORDER BY body, no keyword |

**Returns:** array<int, array<int, array>> company id => rows

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)

## Called from

- [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)

