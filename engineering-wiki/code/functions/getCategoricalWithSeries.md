# getCategoricalWithSeries()

Counts tickets by one property, split into a series per status or priority - the stacked or grouped bar chart.

```php
function getCategoricalWithSeries($conn, $prop, $seriesProp, $where, $params)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 378.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$prop` |  |  | the property to count by, e.g. 'department', 'ticket_type', 'analyst' or 'category' |
| `$seriesProp` |  |  | the widget's series_property: 'status' or 'priority' |
| `$where` |  |  | the widget's filters as " WHERE ..." on alias t, or empty for none |
| `$params` |  |  | the values bound to the placeholders in `$where`, in order |

**Returns:** array  {labels, series} from `pivotToSeries()`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 381 | `getCategoricalWithSeries` | read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 385 | `getCategoricalWithSeries` | read [departments](/code/tables/departments.md) |  |
| 388 | `getCategoricalWithSeries` | read [ticket_types](/code/tables/ticket_types.md) |  |
| 391 | `getCategoricalWithSeries` | read [analysts](/code/tables/analysts.md) |  |
| 394 | `getCategoricalWithSeries` | read [analysts](/code/tables/analysts.md) |  |
| 397 | `getCategoricalWithSeries` | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 402 | `getCategoricalWithSeries` | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 407 | `getCategoricalWithSeries` | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |
| 416 | `getCategoricalWithSeries` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[pivotToSeries()](/code/functions/pivotToSeries.md)

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md)

