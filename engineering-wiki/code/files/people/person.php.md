# people/person.php

> PHP - 113 lines

People — one person, and everything about them from every module (#153 step 2).

Read only. `personDetail()` decides what this analyst may see: the person must be
in a company they can access, and each section must belong to a module they can
open. Out of reach reads exactly like "no such person".

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [people/includes/render.php](/code/files/people/includes/render.php.md), [people/includes/header.php](/code/files/people/includes/header.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [entityVisit()](/code/functions/entityVisit.md), [fmt_date()](/code/functions/fmt_date.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [personDetail()](/code/functions/personDetail.md), [pplE()](/code/functions/pplE.md), [pplHead()](/code/functions/pplHead.md), [pplInitials()](/code/functions/pplInitials.md), [pplPill()](/code/functions/pplPill.md), [pplSections()](/code/functions/pplSections.md), [pplStats()](/code/functions/pplStats.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

