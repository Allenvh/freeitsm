# emailLogRouteLabel()

Human label for a route key, falling back to the key itself for unknown ones.

```php
function emailLogRouteLabel(string $route): string
```

Defined in [includes/email_log.php](/code/files/includes/email_log.php.md), line 148.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$route` | `string` |  | a route key as stored in email_send_log.route, e.g. 'reply' or 'sla' |

**Returns:** `string`

## Called from

- [api/tickets/get_mailbox_outbound.php](/code/files/api/tickets/get_mailbox_outbound.php.md)

