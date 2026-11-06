# apiSupplierContactsDelete()

DELETE /suppliers/{id}/contacts/{contactId} - deletes one of the supplier's contacts.

```php
function apiSupplierContactsDelete(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 566.

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
| 569 | `apiSupplierContactsDelete` | **delete** [contacts](/code/tables/contacts.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiLoadSupplier()](/code/functions/apiLoadSupplier.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

