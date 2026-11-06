# lmsCtLinkPath()

The app-relative path of a test link for a raw token.

```php
function lmsCtLinkPath(string $rawToken): string
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 318.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawToken` | `string` |  | the raw (unhashed) token from `lmsCtNewToken()` |

**Returns:** `string`

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

