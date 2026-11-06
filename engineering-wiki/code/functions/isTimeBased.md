# isTimeBased()

Whether a widget property is plotted over time ('created' or 'closed') rather than counted by category.

```php
function isTimeBased($prop)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$prop` |  |  | the widget's aggregate_property, e.g. 'status', 'created', 'closed' or 'created_vs_closed' |

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md)

