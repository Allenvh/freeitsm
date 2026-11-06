# csrfToken()

The session's token, created if missing. Works whether the session is open, or was opened read_and_close (then it is reopened just long enough to save). Returns '' when there is no session to keep it in.

```php
function csrfToken(): string
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 110.

**Returns:** `string`

## Called from

- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfHeadTags()](/code/functions/csrfHeadTags.md)

