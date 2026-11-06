# domainCtDate()

Turns a crt.sh timestamp (UTC, with or without a Z) into a 'Y-m-d H:i:s' UTC string, or null.

```php
function domainCtDate($v): ?string
```

Defined in [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), line 115.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | a crt.sh timestamp string, e.g. '2026-01-02T03:04:05'; empty or null gives null |

**Returns:** `?string`

## Called from

- [includes/domains/watch.php](/code/files/includes/domains/watch.php.md) - in [domainCtScan()](/code/functions/domainCtScan.md)

