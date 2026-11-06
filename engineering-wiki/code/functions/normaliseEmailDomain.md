# normaliseEmailDomain()

Normalise a user-entered email domain for storage/matching: lower-cased, trimmed, with any leading "@", scheme or "www." stripped. Returns '' if what remains isn't a plausible domain.

```php
function normaliseEmailDomain(string $raw): string
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1324.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the domain as typed - may carry '@', 'https://', 'www.', a path or a whole address |

**Returns:** `string`

## Called from

- [api/system/add_freemail_domain.php](/code/files/api/system/add_freemail_domain.php.md)
- [api/system/add_tenant_domain.php](/code/files/api/system/add_tenant_domain.php.md)
- [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md)
- [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md)

