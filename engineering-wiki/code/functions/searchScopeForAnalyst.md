# searchScopeForAnalyst()

Build the scope structure for an analyst.

Company scoping mirrors `ticketTenantFilter()` exactly — including that a NULL
tenant means "the default company" and is only visible when the analyst's
active company IS the default. Ticket search in FreeITSM is not restricted by
department or team (see api/tickets/search_tickets.php), so neither is this.

```php
function searchScopeForAnalyst(PDO $conn, int $analystId, array $overrides = []): array
```

Defined in [includes/search/search.php](/code/files/includes/search/search.php.md), line 134.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$overrides` | `array` | `[]` | scope keys to replace after the defaults, e.g. ['include_internal' => false, 'source_types' => [...]] |

**Returns:** `array`

## Calls

[documentSearchVisibilityClause()](/code/functions/documentSearchVisibilityClause.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/tickets/search_content.php](/code/files/api/tickets/search_content.php.md)

