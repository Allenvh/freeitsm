# domainImportResolveNames()

CSV columns carry names; the service wants ids. status / owner / registrar are matched by name (case-insensitive); an unmatched name is dropped rather than failing the row — the domain is still worth having.

```php
function domainImportResolveNames(PDO $conn, array $fields, array $row): array
```

Defined in [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md), line 57.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$fields` | `array` |  | the domain fields mapped from the row so far; ids already set are left alone |
| `$row` | `array` |  | the CSV row keyed by column name - status, owner and registrar are read as names |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `domainImportResolveNames` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 61 | `domainImportResolveNames` | read [analysts](/code/tables/analysts.md) |  |
| 62 | `domainImportResolveNames` | read [suppliers](/code/tables/suppliers.md) |  |

## Called from

- [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md)

