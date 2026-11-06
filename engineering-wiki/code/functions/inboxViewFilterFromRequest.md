# inboxViewFilterFromRequest()

Read the filter from the request. Absent means off, so every existing caller of either endpoint keeps exactly the behaviour it had.

```php
function inboxViewFilterFromRequest(): array
```

Defined in [includes/inbox_view_filter.php](/code/files/includes/inbox_view_filter.php.md), line 24.

**Returns:** `array`

## Called from

- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)

