# apiNmValidateIcon()

Returns the icon key when it is in cmdb_icons, otherwise answers 422 pointing at GET /cmdb-icons.

```php
function apiNmValidateIcon(PDO $conn, string $iconKey): string
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 368.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$iconKey` | `string` |  | the requested icon key, checked against cmdb_icons.icon_key |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 369 | `apiNmValidateIcon` | read [cmdb_icons](/code/tables/cmdb_icons.md) |  |

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmValidateNodeInput()](/code/functions/apiNmValidateNodeInput.md), [apiNmNodesUpdate()](/code/functions/apiNmNodesUpdate.md)

