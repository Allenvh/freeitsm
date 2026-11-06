# whRate()

Success-rate cell: delivered vs terminal (delivered + dead); in-flight ignored.

```php
function whRate($delivered, $dead)
```

Defined in [system/webhooks/index.php](/code/files/system/webhooks/index.php.md), line 168.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$delivered` |  |  | how many deliveries were delivered |
| `$dead` |  |  | how many gave up (dead); in-flight ones are not counted |

## Called from

- [system/webhooks/index.php](/code/files/system/webhooks/index.php.md)

