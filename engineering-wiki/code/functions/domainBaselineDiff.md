# domainBaselineDiff()

What changed between the previous baseline and this one.

⚠️ A value going from KNOWN to UNKNOWN (a failed lookup, a DNS timeout) is not
a change — it is a gap in our knowledge. Reporting it would page somebody
every time a registry had a slow afternoon. Only known → different known
counts. Unknown → known is the first sighting, recorded without an alert.

```php
function domainBaselineDiff(?array $old, array $new): array
```

Defined in [includes/domains/monitor.php](/code/files/includes/domains/monitor.php.md), line 66.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$old` | `?array` |  | the previous baseline from domains.watch_baseline, or null on the first run |
| `$new` | `array` |  | the baseline `domainBaseline()` just built |

**Returns:** array<int,array{field:string,old:string,new:string}>

## Calls

[domainWatchedFields()](/code/functions/domainWatchedFields.md)

## Called from

- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::runChecks`

