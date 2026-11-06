# people/suppliers.php

> PHP - 102 lines

People — every supplier, with its contacts (#153 step 3, #162).

Shown here, kept in Contracts: suppliers and their contacts are Contracts'
records, so this page needs Contracts as well as People. Nothing is moved -
a supplier's contact never becomes a user who can sign in or raise tickets.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/people.php](/code/files/includes/people.php.md), [people/includes/render.php](/code/files/people/includes/render.php.md), [people/includes/header.php](/code/files/people/includes/header.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [peopleSupplierRows()](/code/functions/peopleSupplierRows.md), [pplE()](/code/functions/pplE.md), [pplHead()](/code/functions/pplHead.md), [pplPill()](/code/functions/pplPill.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

