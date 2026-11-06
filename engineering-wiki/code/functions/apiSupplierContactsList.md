# apiSupplierContactsList()

GET /suppliers/{id}/contacts - the supplier's contacts, by surname.

```php
function apiSupplierContactsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 490.

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
| 493 | `apiSupplierContactsList` | read [contacts](/code/tables/contacts.md) |  |

## Calls

[apiLoadSupplier()](/code/functions/apiLoadSupplier.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

