# getTimeWithSeries()

Counts tickets created or closed per period across the date range, split into a series per status or priority, with empty periods filled in as zero.

```php
function getTimeWithSeries($conn, $prop, $seriesProp, $timeGrouping, $dateRange, $where, $params)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 495.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$prop` |  |  | 'created' or 'closed' - which date the tickets are counted by |
| `$seriesProp` |  |  | the widget's series_property: 'status' or 'priority' |
| `$timeGrouping` |  |  | the bucket size: 'day', 'month' or 'year' |
| `$dateRange` |  |  | the widget's date range: '7d', '30d', 'this_month', '3m', '6m', '12m', 'this_year' or 'all' |
| `$where` |  |  | the widget's filters as " WHERE ..." on alias t, or empty for none |
| `$params` |  |  | the values bound to the placeholders in `$where`, in order |

**Returns:** array  {labels, series}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 499 | `getTimeWithSeries` | read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 516 | `getTimeWithSeries` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[buildDateRangeWhere()](/code/functions/buildDateRangeWhere.md), [formatLabel()](/code/functions/formatLabel.md), [getDateExpr()](/code/functions/getDateExpr.md), [getDateField()](/code/functions/getDateField.md), [getTimeLabels()](/code/functions/getTimeLabels.md)

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md)

