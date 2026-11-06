# rejectSimpleRequestForgery()

Refuse a state-changing request that declares text/plain.

Runs on include. Silent on CLI (cron has no request), and on GET/HEAD/OPTIONS,
which carry no body to forge.

```php
function rejectSimpleRequestForgery(): void
```

Defined in [includes/request_guard.php](/code/files/includes/request_guard.php.md), line 59.

**Returns:** `void`

## Called from

- [includes/request_guard.php](/code/files/includes/request_guard.php.md)

