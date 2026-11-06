# domainWatchLabel()

The plain-English name of a watched field for the change e-mail (registrar, locks, DNSSEC, name servers, MX, SPF, DMARC).

```php
function domainWatchLabel(string $field): string
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 391.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$field` | `string` |  | a key from `domainWatchedFields()`, e.g. 'dns_ns'; an unknown key comes back unchanged |

**Returns:** `string`

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertOnChange()](/code/functions/domainAlertOnChange.md)

