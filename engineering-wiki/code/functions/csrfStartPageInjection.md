# csrfStartPageInjection()

Put `csrfHeadTags()` into every HTML page without editing each one: the page's output is buffered and the tags are inserted straight after its <head>. Pages only - an API response, a file, a stream and anything that is not HTML pass through untouched.

```php
function csrfStartPageInjection(): void
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 283.

**Returns:** `void`

## Calls

[csrfHeadTags()](/code/functions/csrfHeadTags.md), [csrfRequestPath()](/code/functions/csrfRequestPath.md)

## Called from

- [includes/request_guard.php](/code/files/includes/request_guard.php.md)

