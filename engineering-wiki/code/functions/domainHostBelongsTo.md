# domainHostBelongsTo()

Is `$host` this domain or one of its sub-domains? Used to keep the "extra hosts to check certificates on" list from being pointed at somebody else's server — or at an internal address, which would make this a way to probe the network.

```php
function domainHostBelongsTo(string $host, string $domain): bool
```

Defined in [includes/domains/names.php](/code/files/includes/domains/names.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$host` | `string` |  | the host to test; trimmed, lower-cased and any trailing dot removed first |
| `$domain` | `string` |  | the stored domain name (already lower case) |

**Returns:** `bool`

## Called from

- [includes/domains/names.php](/code/files/includes/domains/names.php.md) - in [domainParseSslHosts()](/code/functions/domainParseSslHosts.md)

