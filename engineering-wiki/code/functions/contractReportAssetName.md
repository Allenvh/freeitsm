# contractReportAssetName()

What to call a piece of equipment on the report.

A hostname is the usual answer and a SIM card has none, so the fallbacks
matter. Falling through to a bare id would be a line nobody can identify —
which on a printed report nobody can then look up either.

```php
function contractReportAssetName(array $a): string
```

Defined in [includes/contract_report.php](/code/files/includes/contract_report.php.md), line 134.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `array` |  | an asset row as `contractAssetsFor()` returns it (hostname, asset_tag, service_tag, manufacturer, model, asset_id) |

**Returns:** `string`

## Called from

- [includes/contract_report.php](/code/files/includes/contract_report.php.md) - in [contractReportBody()](/code/functions/contractReportBody.md)

