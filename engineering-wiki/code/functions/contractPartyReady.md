# contractPartyReady()

True once Database Verification has added the party columns.

```php
function contractPartyReady(PDO $conn): bool
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 63.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 67 | `contractPartyReady` | read [contracts](/code/tables/contracts.md) |  |

## Called from

- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md)
- [api/contracts/get_dashboard_stats.php](/code/files/api/contracts/get_dashboard_stats.php.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsList()](/code/functions/apiContractsList.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractPartySql()](/code/functions/contractPartySql.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleContracts()](/code/functions/peopleContracts.md)
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::partyFields`, `ContractsService::writeParty`

