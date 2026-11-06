# formatLabel()

Turns a bucket key into the axis label in the analyst's date format - day and month buckets are rendered without a time-zone shift, years as they are.

```php
function formatLabel($raw, $timeGrouping)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 280.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` |  |  | a bucket key: 'YYYY-MM-DD' for a day, 'YYYY-MM' for a month, the year for a year |
| `$timeGrouping` |  |  | the bucket size: 'day', 'month' or 'year' |

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) - in [getTimeData()](/code/functions/getTimeData.md), [getTimeWithSeries()](/code/functions/getTimeWithSeries.md), [getCreatedVsClosedData()](/code/functions/getCreatedVsClosedData.md)

