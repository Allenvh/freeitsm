# forms/edit/index.php

> PHP - 2984 lines

Forms — dedicated edit page (replaces the old inline editor in forms/index.php and the now-deleted forms/builder.php). Pretty URL:

/forms/edit/         → create a new form
  /forms/edit/?id=42   → edit form #42

Mounted at its own path so an edit session is a real URL the user can
bookmark, share, refresh, and back/forward through cleanly. The
inline editor in forms/index.php stays as-is for now per the
"don't delete anything yet" instruction — once this page is
confirmed good we'll cut it over.

Includes the versioning metadata panel from #434 (which never made
it into the inline editor in forms/index.php — that was the bug we
spotted).

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [forms/includes/header.php](/code/files/forms/includes/header.php.md)

**Calls:** [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

