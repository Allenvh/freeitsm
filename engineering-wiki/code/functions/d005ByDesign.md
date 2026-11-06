# d005ByDesign()

Endpoints that are CORRECTLY reachable by any logged-in analyst, with the reason.

These act on YOU — your own password, your own MFA, your own preferences. There is no
module to check, because the resource is the caller. Listing them here (rather than
letting them sit in the findings forever) is what keeps the report actionable: a report
with twenty permanent false alarms in it is a report nobody reads.

Add to this ONLY when the endpoint genuinely acts on the caller's own account. If you
are adding something because "it's probably fine", it isn't — guard it instead.

```php
function d005ByDesign(string $rel): ?string
```

Defined in [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `string` |  | the endpoint's path from the app root, with forward slashes |

**Returns:** `?string`

## Called from

- [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md)

