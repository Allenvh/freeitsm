# getDateExpr()

The SQL expression that buckets a ticket date by day, month or year for GROUP BY.

```php
function getDateExpr($timeGrouping, $dateField)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 214.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$timeGrouping` |  |  | the bucket size: 'day', 'month' or 'year' |
| `$dateField` |  |  | the tickets date column: 'created_datetime' or 'closed_datetime' |

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) - in [getTimeData()](/code/functions/getTimeData.md), [getTimeWithSeries()](/code/functions/getTimeWithSeries.md), [getCreatedVsClosedData()](/code/functions/getCreatedVsClosedData.md)

