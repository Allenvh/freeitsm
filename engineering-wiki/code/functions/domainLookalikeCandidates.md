# domainLookalikeCandidates()

The look-alike candidates for a name, keyed by candidate => technique. Deliberately bounded (a few hundred at most): every candidate is two DNS queries.

```php
function domainLookalikeCandidates(string $name): array
```

Defined in [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), line 133.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the stored domain name; the first label is varied and the rest kept as the ending |

**Returns:** `array`

## Called from

- [includes/domains/watch.php](/code/files/includes/domains/watch.php.md) - in [domainLookalikeScan()](/code/functions/domainLookalikeScan.md)

