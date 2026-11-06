# domainSpfLookupCount()

How many DNS lookups evaluating this SPF record costs (RFC 7208 §4.6.4 caps it at 10; past that, receivers treat the record as a permanent error — i.e. SPF silently stops protecting the domain). Follows include: and redirect=.

```php
function domainSpfLookupCount(
    string $spf,
    string $resolver,
    int $depth,
    array &$broken,
    array &$seen = []
): int
```

Defined in [includes/domains/checks.php](/code/files/includes/domains/checks.php.md), line 326.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$spf` | `string` |  | the SPF record text, e.g. "v=spf1 include:_spf.example.com -all" |
| `$resolver` | `string` |  | auto \| system \| google \| cloudflare, as for `domainDns()` |
| `$depth` | `int` |  | how deep in the include/redirect chain this is - 0 for the top; past 10 counts nothing |
| `$broken` | `array` |  | collects include/redirect targets that have no SPF record |
| `$seen` | `array` | `[]` | targets already followed (filled in as it goes), so a loop is counted once |

**Returns:** `int`

## Calls

[domainTxtStartingWith()](/code/functions/domainTxtStartingWith.md)

## Called from

- [includes/domains/checks.php](/code/files/includes/domains/checks.php.md) - in [domainRunChecks()](/code/functions/domainRunChecks.md), [domainSpfLookupCount()](/code/functions/domainSpfLookupCount.md)

