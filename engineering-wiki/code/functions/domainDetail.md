# domainDetail()

One domain for its page: everything but the auth code's value.

```php
function domainDetail(PDO $conn, int $id, ?int $analystId = null): ?array
```

Defined in [includes/domains/read.php](/code/files/includes/domains/read.php.md), line 149.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain's id - access is not checked here, the caller does that |
| `$analystId` | `?int` | `null` | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 160 | `domainDetail` | read [contracts](/code/tables/contracts.md), read [domains](/code/tables/domains.md) |  |

## Calls

[contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [domainListSelect()](/code/functions/domainListSelect.md), [domainShapeRow()](/code/functions/domainShapeRow.md)

## Called from

- [api/domains/get.php](/code/files/api/domains/get.php.md)

