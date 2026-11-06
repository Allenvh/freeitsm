# workflow/includes/header.php

> PHP - 75 lines

Workflows Module Header Component

Assumes the parent page has already loaded includes/i18n.php and called
I18n::initFromSession(). If not, `t()` falls back to returning the keys
verbatim — no fatal, just untranslated.

## How it hangs together

**Included by:** [workflow/editor.php](/code/files/workflow/editor.php.md), [workflow/executions.php](/code/files/workflow/executions.php.md), [workflow/help-ssl.php](/code/files/workflow/help-ssl.php.md), [workflow/help-webhooks.php](/code/files/workflow/help-webhooks.php.md), [workflow/help.php](/code/files/workflow/help.php.md), [workflow/index.php](/code/files/workflow/index.php.md), [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

**Calls:** [renderHeaderRight()](/code/functions/renderHeaderRight.md), [renderWaffleMenuButton()](/code/functions/renderWaffleMenuButton.md), [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md), [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md), [t()](/code/functions/t.md)

