# sessionRefreshGcTimestamp()

Refresh the current session file's timestamp, if there is one to refresh.

Runs on include, like the other session concerns in functions.php, because
every one of those 717 endpoints already includes it — which is also why this
must stay cheap: one stat and one touch, no session lock, no re-open.

```php
function sessionRefreshGcTimestamp(): void
```

Defined in [includes/session_keepalive.php](/code/files/includes/session_keepalive.php.md), line 67.

**Returns:** `void`

## Called from

- [includes/session_keepalive.php](/code/files/includes/session_keepalive.php.md)

