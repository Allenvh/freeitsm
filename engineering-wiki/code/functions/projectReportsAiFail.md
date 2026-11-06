# projectReportsAiFail()

A provider problem in words the page can show.

```php
function projectReportsAiFail(RuntimeException $e): void
```

Defined in [api/projects/reports.php](/code/files/api/projects/reports.php.md), line 29.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `RuntimeException` |  | the AI call's failure; the message 'not_configured' means no provider is set up |

**Returns:** `void`

## Calls

[projectApiOk()](/code/functions/projectApiOk.md)

## Called from

- [api/projects/reports.php](/code/files/api/projects/reports.php.md)

