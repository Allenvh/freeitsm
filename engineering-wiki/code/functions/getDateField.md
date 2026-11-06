# getDateField()

The tickets date column a time-based property counts by - closed_datetime for 'closed', created_datetime otherwise.

```php
function getDateField($prop)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 161.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$prop` |  |  | a time-based aggregate_property: 'created' or 'closed' |

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) - in [getTimeData()](/code/functions/getTimeData.md), [getTimeWithSeries()](/code/functions/getTimeWithSeries.md)

