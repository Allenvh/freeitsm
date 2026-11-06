# contractVisibilitySqlForScope()

The same rule for an explicit company list, in the API's null-means-all convention (ActorContext::`$companyScope`).

```php
function contractVisibilitySqlForScope(PDO $conn, ?array $scope, string $c = 'c'): array
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 107.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `?array` |  | tenant ids the caller may reach; null means every company, empty means none |
| `$c` | `string` | `'c'` | the alias the calling query gives the contracts table (trusted - put straight into SQL) |

**Returns:** array{0:string,1:array}

## Calls

[contractTenantReady()](/code/functions/contractTenantReady.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractVisibility()](/code/functions/apiContractVisibility.md)
- [includes/services/calendar.php](/code/files/includes/services/calendar.php.md) - in `CalendarService::collectEventFields`
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::assertVisible`
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::assertContractVisible`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::validateLink`

