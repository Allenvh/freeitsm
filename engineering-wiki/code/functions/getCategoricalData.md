# getCategoricalData()

Counts tickets by one property (status, priority, department, type, category, closure category, resolution code, analyst, owner, origin, first-time fix, training provided), largest first.

```php
function getCategoricalData($conn, $prop, $where, $params)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 302.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$prop` |  |  | the property to count by, e.g. 'status', 'priority', 'department', 'category' or 'analyst' |
| `$where` |  |  | the widget's filters as " WHERE ..." on alias t, or empty for none |
| `$params` |  |  | the values bound to the placeholders in `$where`, in order |

**Returns:** array  {labels, values} - empty for a property it does not know

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 303 | `getCategoricalData` | read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 305 | `getCategoricalData` | read [tickets](/code/tables/tickets.md) |  |
| 307 | `getCategoricalData` | read [tickets](/code/tables/tickets.md) |  |
| 309 | `getCategoricalData` | read [tickets](/code/tables/tickets.md), read [departments](/code/tables/departments.md) |  |
| 311 | `getCategoricalData` | read [tickets](/code/tables/tickets.md), read [ticket_types](/code/tables/ticket_types.md) |  |
| 334 | `getCategoricalData` | read [tickets](/code/tables/tickets.md), read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 343 | `getCategoricalData` | read [tickets](/code/tables/tickets.md), read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |
| 345 | `getCategoricalData` | read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |
| 347 | `getCategoricalData` | read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |
| 349 | `getCategoricalData` | read [tickets](/code/tables/tickets.md), read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 351 | `getCategoricalData` | read [tickets](/code/tables/tickets.md) |  |
| 353 | `getCategoricalData` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md)

