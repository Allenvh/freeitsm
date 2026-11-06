# process-mapper/includes/header.php

> PHP - 59 lines

Process Mapper Module Header Component

Assumes the parent page has already loaded includes/i18n.php and called
I18n::initFromSession(). If not, `t()` falls back to returning the keys
verbatim — no fatal, just untranslated.

## How it hangs together

**Included by:** [process-mapper/help.php](/code/files/process-mapper/help.php.md), [process-mapper/index.php](/code/files/process-mapper/index.php.md), [process-mapper/settings/index.php](/code/files/process-mapper/settings/index.php.md)

**Calls:** [renderHeaderRight()](/code/functions/renderHeaderRight.md), [renderWaffleMenuButton()](/code/functions/renderWaffleMenuButton.md), [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md), [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md), [t()](/code/functions/t.md)

