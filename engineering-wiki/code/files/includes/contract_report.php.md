# includes/contract_report.php

> PHP - 170 lines

The equipment report for a contract: what it covers, on one page.

⚠️ ONE RENDERER, THREE DESTINATIONS — the printable page, the emailed copy
and (via the same data) the CSV. This is the shape asset handover already
uses, and for the same reason: a report that looks different depending on how
it left the building is a report people stop trusting.

The CSS is a PHP function rather than a .css file because a mail client will
not fetch an external stylesheet. It has to be inlined into the message, so
it has to be a string.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [contractReportCss()](/code/functions/contractReportCss.md) | 20 | Returns the contract equipment report's stylesheet as a string, so it can be inlined into the printable page and the emailed copy. |
| [contractReportPrintCss()](/code/functions/contractReportPrintCss.md) | 44 | Extra rules that only matter on paper. |
| [contractReportBody()](/code/functions/contractReportBody.md) | 60 | The report body. No <html> wrapper — the page and the email each supply their own, because one needs a stylesheet link and the other needs it inlined. |
| [contractReportAssetName()](/code/functions/contractReportAssetName.md) | 134 | What to call a piece of equipment on the report. |
| [contractReportLoad()](/code/functions/contractReportLoad.md) | 148 | The contract, with the supplier and owner names the report shows. Returns null when there is no such contract. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 155 | `contractReportLoad` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Included by:** [api/contracts/email_equipment_report.php](/code/files/api/contracts/email_equipment_report.php.md), [contracts/equipment-report.php](/code/files/contracts/equipment-report.php.md)

**Calls:** [contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [t()](/code/functions/t.md)

