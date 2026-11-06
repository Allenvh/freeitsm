# recentTrailShouldRecord()

Is this request a person opening a record page?

⚠️ WITHOUT THIS THE TRAIL FILLS WITH NOISE. The same module directories serve
XHR fragments, polling endpoints and print views, and a heartbeat that fires
every thirty seconds would otherwise write a "visit" every thirty seconds and
push everything you actually did off the end of the log.

```php
function recentTrailShouldRecord(): bool
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 226.

**Returns:** `bool`

## Called from

- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [entityVisit()](/code/functions/entityVisit.md)

