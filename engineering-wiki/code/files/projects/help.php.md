# projects/help.php

> PHP - 302 lines

Projects - help. Numbered sections with a scroll-spy sidebar, the house layout from assets/css/help.css (the People and Domains guides' model). The words live in lang/<locale>/projects.php under help.*, one entry per paragraph, so every section is translatable and this file is only the shape.

⚠️ The health rules in section "health" must match `projectAutoHealth()` in
includes/projects/read.php. Change one, change the other.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [projects/includes/header.php](/code/files/projects/includes/header.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

