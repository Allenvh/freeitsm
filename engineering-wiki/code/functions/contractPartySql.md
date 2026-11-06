# contractPartySql()

Joins and columns that name a contract's customer, for alias `$c`: customer_company_name, customer_person_name, customer_person_email Empty strings before Database Verification, so callers need no second branch.

```php
function contractPartySql(PDO $conn, string $c = 'c'): array
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 176.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$c` | `string` | `'c'` | the alias the calling query gives the contracts table (trusted - put straight into SQL) |

**Returns:** array{0:string,1:string} [select columns (with leading comma), joins]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 187 | `contractPartySql` | read [tenants](/code/tables/tenants.md), read [users](/code/tables/users.md) |  |

## Calls

[contractPartyReady()](/code/functions/contractPartyReady.md)

## Called from

- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md)
- [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractSelect()](/code/functions/apiContractSelect.md), [apiContractsList()](/code/functions/apiContractsList.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractsForAsset()](/code/functions/contractsForAsset.md)
- [includes/contract_report.php](/code/files/includes/contract_report.php.md) - in [contractReportLoad()](/code/functions/contractReportLoad.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleContracts()](/code/functions/peopleContracts.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewContract()](/code/functions/recordPreviewContract.md)
- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowEmitContractExpiries()](/code/functions/workflowEmitContractExpiries.md)

