# people/company.php

> PHP - 115 lines

People — one company, and everything that belongs to it (#153 step 2).

Read only. `companyDetail()` decides what this analyst may see: the company must
be one they can access, and each section must belong to a module they can open.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [people/includes/render.php](/code/files/people/includes/render.php.md), [people/includes/header.php](/code/files/people/includes/header.php.md)

**Calls:** [companyDetail()](/code/functions/companyDetail.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [entityVisit()](/code/functions/entityVisit.md), [pplE()](/code/functions/pplE.md), [pplHead()](/code/functions/pplHead.md), [pplPill()](/code/functions/pplPill.md), [pplSections()](/code/functions/pplSections.md), [pplStats()](/code/functions/pplStats.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

