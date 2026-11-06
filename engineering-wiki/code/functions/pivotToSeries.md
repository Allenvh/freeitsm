# pivotToSeries()

Pivots label / series_val / value rows into chart series - one series per distinct series value, with a count (0 when missing) for every label.

```php
function pivotToSeries($data)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 635.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` |  |  | query rows with label, series_val and value columns |

**Returns:** array  {labels, series: [{label, values}]}

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) - in [getCategoricalWithSeries()](/code/functions/getCategoricalWithSeries.md)

