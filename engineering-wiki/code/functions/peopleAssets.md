# peopleAssets()

Assets the person holds now / assets belonging to the company.

```php
function peopleAssets(PDO $conn, int $analystId, array $who): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 370.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$who` | `array` |  | ['user' => users id] for what the person holds, or ['company' => tenant id] |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 374 | `peopleAssets` | read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 376 | `peopleAssets` | read [users_assets](/code/tables/users_assets.md), read [assets](/code/tables/assets.md) |  |
| 381 | `peopleAssets` | read [assets](/code/tables/assets.md) |  |
| 382 | `peopleAssets` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md), [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleScope()](/code/functions/peopleScope.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md)

