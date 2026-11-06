# getCustomFreemailDomains()

Admin-added custom freemail domains (the freemail_domains table), lower-cased. Cached per request; degrades to [] if the table doesn't exist yet — so the file is safe on a part-migrated install.

```php
function getCustomFreemailDomains(PDO $conn): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1280.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1285 | `getCustomFreemailDomains` | read [freemail_domains](/code/tables/freemail_domains.md) |  |

## Called from

- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [isFreemailDomain()](/code/functions/isFreemailDomain.md)

