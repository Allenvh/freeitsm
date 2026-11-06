# domainParseTags()

"v=DMARC1; p=reject; rua=mailto:x" → ['v'=>'DMARC1','p'=>'reject','rua'=>'mailto:x']

```php
function domainParseTags(string $record): array
```

Defined in [includes/domains/checks.php](/code/files/includes/domains/checks.php.md), line 305.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$record` | `string` |  | a tag=value record such as DMARC; parts without an "=" are skipped |

**Returns:** `array`

## Called from

- [includes/domains/checks.php](/code/files/includes/domains/checks.php.md) - in [domainRunChecks()](/code/functions/domainRunChecks.md)

