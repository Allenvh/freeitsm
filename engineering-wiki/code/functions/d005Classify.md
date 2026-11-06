# d005Classify()

What guards it? Returns [level, detail]. Strongest match wins.

The order and the breadth here matter more than they look. A scanner that shouts
about endpoints which are in fact perfectly well guarded — by a mechanism it simply
didn't recognise — gets ignored within a week, and then it protects nothing. So every
legitimate authentication style in the codebase is taught to it explicitly, and the
cases below are the ones that DID produce false alarms on the first run:
  - MFA endpoints authenticate through `getMfaAuthContext()`, not a bare `$_SESSION` read;
  - the browser extension's endpoint takes an Authorization API key;
  - the inbound messaging webhook is authenticated by the provider's own signature.

```php
function d005Classify(string $src, string $rel): array
```

Defined in [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md), line 143.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$src` | `string` |  | the endpoint file's full PHP source |
| `$rel` | `string` |  | the endpoint's path from the app root, with forward slashes |

**Returns:** `array`

## Called from

- [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md)

