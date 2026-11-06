# buildDateRangeWhere()

Turns a widget's date range ('7d', '30d', 'this_month', '3m', '6m', '12m', 'this_year') into a condition on the date column and its start date.

```php
function buildDateRangeWhere($dateRange, $dateField)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 174.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dateRange` |  |  | the widget's date range: '7d', '30d', 'this_month', '3m', '6m', '12m', 'this_year' or 'all' |
| `$dateField` |  |  | the tickets date column: 'created_datetime' or 'closed_datetime' |

**Returns:** array  [sql, param] - ['', null] for 'all', empty or an unknown range

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) - in [getTimeData()](/code/functions/getTimeData.md), [getTimeWithSeries()](/code/functions/getTimeWithSeries.md), [getCreatedVsClosedData()](/code/functions/getCreatedVsClosedData.md)

