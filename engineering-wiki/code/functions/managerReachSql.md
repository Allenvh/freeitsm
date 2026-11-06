# managerReachSql()

Everyone a line could ever reach for this manager, as an SQL condition on `users u`: same company (no company = Default), not the manager themselves, and - unless the setting keeps them - not people who have left. The ONE definition; the team, and System -> Managers' warnings, both use it.

```php
function managerReachSql(PDO $conn, array $m): array
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 240.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$m` | `array` |  | the manager's row (id, tenant_id) from `managerRow()` |

**Returns:** array{0:string, 1:array}

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [managerCompany()](/code/functions/managerCompany.md), [managersSettings()](/code/functions/managersSettings.md)

## Called from

- [api/system/managers.php](/code/files/api/system/managers.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerTeamUserIds()](/code/functions/managerTeamUserIds.md)

