# domainWhoisServer()

The WHOIS server for a TLD, from IANA's referral, cached with the bootstrap.

```php
function domainWhoisServer(PDO $conn, string $tld): ?string
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 355.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tld` | `string` |  | the top-level domain without a dot, e.g. 'de' |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 362 | `domainWhoisServer` | read [system_settings](/code/tables/system_settings.md) |  |
| 372 | `domainWhoisServer` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## Calls

[domainWhoisQuery()](/code/functions/domainWhoisQuery.md)

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

