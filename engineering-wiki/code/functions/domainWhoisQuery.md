# domainWhoisQuery()

One port-43 query. null when the server could not be reached.

```php
function domainWhoisQuery(string $server, string $query): ?string
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 385.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$server` | `string` |  | the WHOIS server host name, e.g. 'whois.iana.org' |
| `$query` | `string` |  | the line to send - normally the domain or TLD, with server-specific flags where needed |

**Returns:** `?string`

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainWhoisServer()](/code/functions/domainWhoisServer.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

