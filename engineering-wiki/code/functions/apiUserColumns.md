# apiUserColumns()

The columns every read of a requester selects - including the cost centre they are charged to (GH #160), once Database Verification has added it.

```php
function apiUserColumns(PDO $conn): string
```

Defined in [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md), line 13.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 17 | `apiUserColumns` | read [cost_centres](/code/tables/cost_centres.md) |  |

## Calls

[costCentreReady()](/code/functions/costCentreReady.md)

## Called from

- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersList()](/code/functions/apiUsersList.md), [apiUsersGet()](/code/functions/apiUsersGet.md), [apiUsersCreate()](/code/functions/apiUsersCreate.md), [apiUsersUpdate()](/code/functions/apiUsersUpdate.md)

