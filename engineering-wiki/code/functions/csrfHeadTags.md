# csrfHeadTags()

The tags every page needs: the token and the script that sends it.

```php
function csrfHeadTags(): string
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 267.

**Returns:** `string`

## Calls

[csrfToken()](/code/functions/csrfToken.md)

## Called from

- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfStartPageInjection()](/code/functions/csrfStartPageInjection.md)

