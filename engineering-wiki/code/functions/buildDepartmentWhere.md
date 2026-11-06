# buildDepartmentWhere()

Turns a widget's department filter into a t.department_id IN (...) condition with its integer parameters - ['', []] when there is no filter.

```php
function buildDepartmentWhere($deptFilter)
```

Defined in [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md), line 200.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$deptFilter` |  |  | the widget's department ids, decoded from department_filter; null or empty for none |

## Called from

- [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md)

