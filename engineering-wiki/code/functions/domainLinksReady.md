# domainLinksReady()

Before Database Verification the tables are not there: no links, never an error.

```php
function domainLinksReady(PDO $conn): bool
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[domainLinkKinds()](/code/functions/domainLinkKinds.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinks()](/code/functions/domainLinks.md), [domainLinkAdd()](/code/functions/domainLinkAdd.md), [domainLinkRemove()](/code/functions/domainLinkRemove.md), [domainLinkSearch()](/code/functions/domainLinkSearch.md), [domainsLinkedTo()](/code/functions/domainsLinkedTo.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md)

