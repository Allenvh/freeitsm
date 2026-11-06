# domainLinkKind()

Returns the definition (table, column, module...) of one link kind; throws a validation ServiceError for an unknown kind.

```php
function domainLinkKind(string $kind): array
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 67.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$kind` | `string` |  | the link kind - cmdb \| service \| ticket \| article (untrusted) |

**Returns:** `array`

## Calls

[domainLinkKinds()](/code/functions/domainLinkKinds.md)

## Called from

- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkAdd()](/code/functions/domainLinkAdd.md), [domainLinkRemove()](/code/functions/domainLinkRemove.md), [domainLinkSearch()](/code/functions/domainLinkSearch.md)

