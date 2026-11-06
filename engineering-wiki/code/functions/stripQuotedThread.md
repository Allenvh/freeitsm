# stripQuotedThread()

Strip quoted/nested thread content from an email body Relies on our own visible marker text, with generic blockquote fallback

```php
function stripQuotedThread($body)
```

Defined in [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md), line 178.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` |  |  | an email's HTML body, which may still carry the quoted earlier thread |

## Called from

- [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md)

