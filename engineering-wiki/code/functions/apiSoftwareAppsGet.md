# apiSoftwareAppsGet()

GET /software/apps/{id} — app + licences + compliance numbers

```php
function apiSoftwareAppsGet(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), line 148.

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
| 153 | `apiSoftwareAppsGet` | read [software_licences](/code/tables/software_licences.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiRespond()](/code/functions/apiRespond.md), [apiSoftwareInstallCount()](/code/functions/apiSoftwareInstallCount.md), [apiSoftwareLoadApp()](/code/functions/apiSoftwareLoadApp.md)

*Nothing calls this function by name.*

