# apiUsersCreate()

POST /users

```php
function apiUsersCreate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md), line 150.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 175 | `apiUsersCreate` | read [users](/code/tables/users.md) |  |
| 182 | `apiUsersCreate` | read [users](/code/tables/users.md) |  |
| 203 | `apiUsersCreate` | **insert** [users](/code/tables/users.md) (`email`, `username`, `display_name`, `preferred_name`, `tenant_id`, `created_at`) |  |
| 206 | `apiUsersCreate` | **update** [users](/code/tables/users.md) (`cost_centre_id`) |  |
| 208 | `apiUsersCreate` | read [users](/code/tables/users.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeUser()](/code/functions/apiSerializeUser.md), [apiUserColumns()](/code/functions/apiUserColumns.md), [apiUserCostCentre()](/code/functions/apiUserCostCentre.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md)

*Nothing calls this function by name.*

