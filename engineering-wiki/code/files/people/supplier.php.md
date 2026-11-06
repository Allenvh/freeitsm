# people/supplier.php

> PHP - 124 lines

People — one supplier, its contacts, and everything it has to do with (#153 step 3, #162).

Read only. `supplierDetail()` decides what this analyst may see: the page needs
People and Contracts (which owns suppliers), and each section its own module.
Changes are made in Contracts, one click away.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [people/includes/render.php](/code/files/people/includes/render.php.md), [people/includes/header.php](/code/files/people/includes/header.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [entityVisit()](/code/functions/entityVisit.md), [pplE()](/code/functions/pplE.md), [pplHead()](/code/functions/pplHead.md), [pplPill()](/code/functions/pplPill.md), [pplSectionAssets()](/code/functions/pplSectionAssets.md), [pplSectionContractorTasks()](/code/functions/pplSectionContractorTasks.md), [pplSectionContracts()](/code/functions/pplSectionContracts.md), [pplSectionDomains()](/code/functions/pplSectionDomains.md), [pplStats()](/code/functions/pplStats.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [supplierDetail()](/code/functions/supplierDetail.md), [t()](/code/functions/t.md)

