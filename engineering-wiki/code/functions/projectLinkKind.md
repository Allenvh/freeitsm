# projectLinkKind()

The definition (table, column, module) of one link kind; throws a validation error for an unknown kind.

```php
function projectLinkKind(string $kind): array
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$kind` | `string` |  | the link kind: asset, change, ticket, contract, cmdb, article or problem |

**Returns:** `array`

## Calls

[projectLinkKinds()](/code/functions/projectLinkKinds.md)

## Called from

- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkAdd()](/code/functions/projectLinkAdd.md), [projectLinkRemove()](/code/functions/projectLinkRemove.md), [projectLinkSearch()](/code/functions/projectLinkSearch.md)

