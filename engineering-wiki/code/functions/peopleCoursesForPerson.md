# peopleCoursesForPerson()

The person's courses - exactly what their own My courses page shows.

```php
function peopleCoursesForPerson(PDO $conn, int $userId): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 537.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the users id of the learner |

**Returns:** `array`

## Calls

[lmsMyCourses()](/code/functions/lmsMyCourses.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [personDetail()](/code/functions/personDetail.md)

