# people/contact.php

> PHP - 96 lines

People — one supplier contact (#153 step 3, #162).

Read only, and NOT a user: a supplier's contact stays in Contracts → Contacts,
so it cannot sign in, raise a ticket or be picked up by directory sync.
`supplierContactDetail()` needs People and Contracts, and each section its own
module.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [people/includes/render.php](/code/files/people/includes/render.php.md), [people/includes/header.php](/code/files/people/includes/header.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [entityVisit()](/code/functions/entityVisit.md), [fmt_date()](/code/functions/fmt_date.md), [pplE()](/code/functions/pplE.md), [pplHead()](/code/functions/pplHead.md), [pplInitials()](/code/functions/pplInitials.md), [pplPill()](/code/functions/pplPill.md), [pplSectionContractorTasks()](/code/functions/pplSectionContractorTasks.md), [pplSectionDomains()](/code/functions/pplSectionDomains.md), [pplStats()](/code/functions/pplStats.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [supplierContactDetail()](/code/functions/supplierContactDetail.md), [t()](/code/functions/t.md)

