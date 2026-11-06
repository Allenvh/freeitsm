# warbotContractScope()

`contractVisibilitySql()` with its company ids written in: these queries bind NAMED parameters, which cannot be mixed with its "?" ones. Every value is a company id, cast to int, so writing them in cannot inject anything.

```php
function warbotContractScope(PDO $conn, int $analystId, string $alias): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 761.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` |  | the alias the calling query gives the contracts table |

**Returns:** `string`

## Calls

[contractVisibilitySql()](/code/functions/contractVisibilitySql.md)

## Called from

- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolSupplierContact()](/code/functions/warbotToolSupplierContact.md)

