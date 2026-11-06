# forms/index.php

> PHP - 947 lines

Forms Module - Forms list (dashboard)

Full-width table view. Editing happens on /forms/edit/ (#437) which
is also where AI Assist + versioning + the field builder all live.
This page is purely the list / dashboard now — click a row to edit,
or use the row icons for Fill / Submissions / Delete.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [forms/includes/header.php](/code/files/forms/includes/header.php.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

