# getCreatedVsClosedData()

Counts tickets created and tickets closed in each period of the date range, as two series on the same time labels.

```php
function getCreatedVsClosedData($conn, $timeGrouping, $dateRange, $where, $params)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 567.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$timeGrouping` |  |  | the bucket size: 'day', 'month' or 'year' |
| `$dateRange` |  |  | the widget's date range: '7d', '30d', 'this_month', '3m', '6m', '12m', 'this_year' or 'all' |
| `$where` |  |  | the widget's filters as " WHERE ..." on alias t, or empty for none |
| `$params` |  |  | the values bound to the placeholders in `$where`, in order |

**Returns:** array  {labels, series: [Created, Closed]}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 581 | `getCreatedVsClosedData` | read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 582 | `getCreatedVsClosedData` | read [tickets](/code/tables/tickets.md) |  |
| 598 | `getCreatedVsClosedData` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[buildDateRangeWhere()](/code/functions/buildDateRangeWhere.md), [formatLabel()](/code/functions/formatLabel.md), [getDateExpr()](/code/functions/getDateExpr.md), [getTimeLabels()](/code/functions/getTimeLabels.md)

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md)

