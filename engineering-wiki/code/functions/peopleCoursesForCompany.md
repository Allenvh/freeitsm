# peopleCoursesForCompany()

Per course, how many people in the company have finished it and how many are part-way.

```php
function peopleCoursesForCompany(PDO $conn, int $tenantId): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 553.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 558 | `peopleCoursesForCompany` | read [lms_progress](/code/tables/lms_progress.md), read [users](/code/tables/users.md), read [lms_courses](/code/tables/lms_courses.md) |  |

## Calls

[peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [companyDetail()](/code/functions/companyDetail.md)

