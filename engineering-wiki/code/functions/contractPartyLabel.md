# contractPartyLabel()

One line naming who a contract is with, for lists, previews and payloads: the supplier's name, or "Company · Person" / "Company" / "Person".

```php
function contractPartyLabel(array $row): string
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 199.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | a contract row carrying the `contractPartySql()` columns plus supplier_name and supplier_trading_name |

**Returns:** `string`

## Called from

- [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md)
- [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractsForAsset()](/code/functions/contractsForAsset.md)
- [includes/contract_report.php](/code/files/includes/contract_report.php.md) - in [contractReportLoad()](/code/functions/contractReportLoad.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleContracts()](/code/functions/peopleContracts.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewContract()](/code/functions/recordPreviewContract.md)
- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowEmitContractExpiries()](/code/functions/workflowEmitContractExpiries.md)

