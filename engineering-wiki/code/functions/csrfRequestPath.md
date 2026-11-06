# csrfRequestPath()

The request's path relative to the app root, e.g. "api/tickets/save.php".

```php
function csrfRequestPath(): string
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 63.

**Returns:** `string`

## Called from

- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfRefuse()](/code/functions/csrfRefuse.md), [csrfEnforce()](/code/functions/csrfEnforce.md), [csrfStartPageInjection()](/code/functions/csrfStartPageInjection.md)

