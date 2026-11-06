# normaliseEmailAddress()

Normalise a user-entered email address for storage/matching: lower-cased, trimmed, with any "mailto:" prefix or surrounding angle brackets stripped. Returns '' if what remains isn't a valid email address.

Used for the per-company "specific senders" list (tenant_sender_addresses),
which matches on the exact sender address — unlike domains, freemail
addresses are allowed here (that's the whole point: route an individual
personal address even when its domain can't be mapped).

```php
function normaliseEmailAddress(string $raw): string
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1351.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the address as typed - may carry 'mailto:' or angle brackets |

**Returns:** `string`

## Called from

- [api/system/add_tenant_sender.php](/code/files/api/system/add_tenant_sender.php.md)
- [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md)

