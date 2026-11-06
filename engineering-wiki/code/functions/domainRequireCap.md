# domainRequireCap()

Stops the request with a JSON refusal unless the signed-in analyst holds the capability.

```php
function domainRequireCap(PDO $conn, string $cap): void
```

Defined in [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), line 72.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$cap` | `string` |  | a capability key, normally a Cap::DOMAINS_* constant |

**Returns:** `void`

## Calls

[requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

## Called from

- [api/domains/auth_code.php](/code/files/api/domains/auth_code.php.md)
- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [api/domains/statuses.php](/code/files/api/domains/statuses.php.md)

