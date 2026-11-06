# domainWhoisLookup()

Looks a domain up over WHOIS, for registries without RDAP, and reads the dates, statuses, registrar, name servers and DNSSEC from the text reply.

An answer saying the name is not registered comes back with not_found set.

```php
function domainWhoisLookup(PDO $conn, string $name): array
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 409.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$name` | `string` |  | a stored domain name, already through `domainNormalise()` |

**Returns:** `array`

## Calls

[domainBlankResult()](/code/functions/domainBlankResult.md), [domainDateOnly()](/code/functions/domainDateOnly.md), [domainDeriveFlags()](/code/functions/domainDeriveFlags.md), [domainLooksRedacted()](/code/functions/domainLooksRedacted.md), [domainNormaliseStatuses()](/code/functions/domainNormaliseStatuses.md), [domainTld()](/code/functions/domainTld.md), [domainWhoisQuery()](/code/functions/domainWhoisQuery.md), [domainWhoisServer()](/code/functions/domainWhoisServer.md)

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainLookup()](/code/functions/domainLookup.md)

