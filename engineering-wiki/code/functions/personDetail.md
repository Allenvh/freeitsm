# personDetail()

Everything about one person this analyst may see, or null (unknown or out of reach).

```php
function personDetail(PDO $conn, int $analystId, int $userId): ?array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 211.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the users id of the person |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 219 | `personDetail` | read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 245 | `personDetail` | read [users](/code/tables/users.md) |  |
| 250 | `personDetail` | read [users](/code/tables/users.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [costCentreSelectSql()](/code/functions/costCentreSelectSql.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [peopleAssets()](/code/functions/peopleAssets.md), [peopleContracts()](/code/functions/peopleContracts.md), [peopleCoursesForPerson()](/code/functions/peopleCoursesForPerson.md), [peopleDomains()](/code/functions/peopleDomains.md), [peopleForms()](/code/functions/peopleForms.md), [peopleName()](/code/functions/peopleName.md), [peopleProjects()](/code/functions/peopleProjects.md), [peopleScope()](/code/functions/peopleScope.md), [peopleTickets()](/code/functions/peopleTickets.md)

## Called from

- [people/person.php](/code/files/people/person.php.md)

