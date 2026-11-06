# domainsLinkedTo()

The other direction: the domains linked to one CI / service / ticket / article that this analyst can see. Empty (not an error) when they cannot open Domains or the other record, so a page can call it unconditionally.

```php
function domainsLinkedTo(PDO $conn, int $analystId, string $kind, int $targetId): array
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 313.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the kind of record on the other side - cmdb \| service \| ticket \| article |
| `$targetId` | `int` |  | that record's id |

**Returns:** `array`

## Calls

[analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [domainLinkDomainRows()](/code/functions/domainLinkDomainRows.md), [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md), [domainLinkKinds()](/code/functions/domainLinkKinds.md), [domainLinksReady()](/code/functions/domainLinksReady.md), [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

