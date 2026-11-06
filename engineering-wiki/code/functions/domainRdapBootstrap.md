# domainRdapBootstrap()

TLD → RDAP base URL, from IANA's bootstrap file, cached for seven days. A failed refresh keeps using the stale copy — a week-old map of registries is far better than none, and they rarely move.

```php
function domainRdapBootstrap(PDO $conn): array
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array<string,string>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 | `domainRdapBootstrap` | read [system_settings](/code/tables/system_settings.md) |  |
| 116 | `domainRdapBootstrap` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## Calls

[domainHttpGet()](/code/functions/domainHttpGet.md)

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainLookup()](/code/functions/domainLookup.md), [domainLookupPace()](/code/functions/domainLookupPace.md)

