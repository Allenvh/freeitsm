# logApiResponse()

Helper function to log errors/responses

```php
function logApiResponse($conn, $hostId, $message)
```

Defined in [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md), line 122.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$hostId` |  |  | the asset id the submitting machine was matched to or created as; null before that |
| `$message` |  |  | the response or error to log - a string, or an array stored as JSON |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 124 | `logApiResponse` | **insert** [software_inventory_log](/code/tables/software_inventory_log.md) (`host_id`, `api_response`) |  |

## Called from

- [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md)

