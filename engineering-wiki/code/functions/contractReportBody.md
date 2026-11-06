# contractReportBody()

The report body. No <html> wrapper — the page and the email each supply their own, because one needs a stylesheet link and the other needs it inlined.

```php
function contractReportBody(array $contract, array $assets): string
```

Defined in [includes/contract_report.php](/code/files/includes/contract_report.php.md), line 60.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$contract` | `array` |  | Row from `contracts`, plus supplier_name / owner_name. |
| `$assets` | `array` |  | Rows from `contractAssetsFor()`. |

**Returns:** `string`

## Calls

[contractReportAssetName()](/code/functions/contractReportAssetName.md), [t()](/code/functions/t.md)

## Called from

- [api/contracts/email_equipment_report.php](/code/files/api/contracts/email_equipment_report.php.md)
- [contracts/equipment-report.php](/code/files/contracts/equipment-report.php.md)

