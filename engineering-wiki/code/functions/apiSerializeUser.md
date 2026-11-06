# apiSerializeUser()

Shapes a requester (users row) for the API - email (null for a directory user with no mailbox), names, company, active flag, cost centre and created date.

```php
function apiSerializeUser(array $u): array
```

Defined in [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$u` | `array` |  | a users row selected with `apiUserColumns()`, including the cost centre code and name when present |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersGet()](/code/functions/apiUsersGet.md), [apiUsersCreate()](/code/functions/apiUsersCreate.md), [apiUsersUpdate()](/code/functions/apiUsersUpdate.md)

