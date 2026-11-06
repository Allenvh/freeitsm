# contracts/equipment-report.php

> PHP - 133 lines

The equipment report for a contract, as a printable page.

?id=<contract>   and optionally &print=1 to open the print dialog on load.

"PDF" in this product means printing to one, which is what asset handover and
the RFP preview already do. There is no PDF library in the stack, and adding
one to produce a table of six columns would be a dependency to maintain
forever in exchange for a Save-as-PDF button every browser already has.

⚠️ The body comes from includes/contract_report.php, the SAME renderer the
emailed copy uses. One layout, three destinations.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/contract_report.php](/code/files/includes/contract_report.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetsFor()](/code/functions/contractAssetsFor.md), [contractReportBody()](/code/functions/contractReportBody.md), [contractReportCss()](/code/functions/contractReportCss.md), [contractReportLoad()](/code/functions/contractReportLoad.md), [contractReportPrintCss()](/code/functions/contractReportPrintCss.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

