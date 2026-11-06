# apiSupplierContactsUpdate()

PATCH /suppliers/{id}/contacts/{contactId} - updates the fields sent, keeping the rest. First name and surname cannot be emptied.

```php
function apiSupplierContactsUpdate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 533.

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
| 536 | `apiSupplierContactsUpdate` | read [contacts](/code/tables/contacts.md) |  |
| 555 | `apiSupplierContactsUpdate` | **update** [contacts](/code/tables/contacts.md) (`first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active`) |  |
| 560 | `apiSupplierContactsUpdate` | read [contacts](/code/tables/contacts.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiLoadSupplier()](/code/functions/apiLoadSupplier.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeContact()](/code/functions/apiSerializeContact.md)

*Nothing calls this function by name.*

