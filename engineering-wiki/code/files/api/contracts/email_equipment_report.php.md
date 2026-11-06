# api/contracts/email_equipment_report.php

> API endpoint - 80 lines

API: email the equipment report for a contract.

POST { contract_id: <int>, to?: <string> }

Sends through `ssSendSystemEmail()`, the same sender the portal uses, so it
inherits the configured sending mailbox rather than introducing a second idea
of how this installation sends mail.

⚠️ The message is built by the SAME renderer as the printable page, with the
stylesheet inlined in a <style> block — a mail client will not fetch an
external sheet. One layout, three destinations.

⚠️ The equipment list is SCOPED to the sender, not to the recipient. What
gets attached is what the person pressing Send can see, which is the only
honest reading: they are the one choosing to send it.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/email_equipment_report.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_report.php](/code/files/includes/contract_report.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetsFor()](/code/functions/contractAssetsFor.md), [contractReportBody()](/code/functions/contractReportBody.md), [contractReportCss()](/code/functions/contractReportCss.md), [contractReportLoad()](/code/functions/contractReportLoad.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [t()](/code/functions/t.md)

