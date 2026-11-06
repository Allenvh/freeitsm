# userPersonFieldValue()

Normalise one incoming person field.

Blank always becomes NULL, never '': the columns are nullable so that "not
known" is distinguishable from "known to be empty", and an empty string quietly
destroys that distinction — the same trap `users.email` documents at length,
one column over.

```php
function userPersonFieldValue(string $field, $value)
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 379.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$field` | `string` |  | the users column, one of USER_PERSON_FIELDS; 'manager_id' is read as an id |
| `$value` |  |  | raw from the request body |

**Returns:** string|int|null

## Called from

- [api/self-service/update_profile.php](/code/files/api/self-service/update_profile.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)

