# forms/help/_top.php

> PHP - 136 lines

Forms help topic — the chrome.

A topic page sets `$helpTopic` to its slug, requires this, writes its sections,
and requires _bottom.php. Everything else — the head, the accent, the sidebar,
the section numbers — comes from here and from _registry.php.

🔑 THE SECTION NUMBERS COME FROM THE REGISTRY, NOT FROM COUNTING THE DOM.
System help numbered its sections in JavaScript once, so a section missing
from the registry silently shifted every number after it. `helpNum()` below
resolves from the same array that builds the sidebar; an id the registry does
not know returns an EMPTY badge, because a missing number is honest and a
confidently wrong one is not.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [helpNum()](/code/functions/helpNum.md) | 44 | A section's number, from the registry. '' when the registry does not know it. |
| [helpSection()](/code/functions/helpSection.md) | 58 | Open a section: the hairline, the number badge, the heading and its lead. |
| [helpSectionEnd()](/code/functions/helpSectionEnd.md) | 68 | Closes a section opened by `helpSection()`. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [forms/help/_registry.php](/code/files/forms/help/_registry.php.md), [forms/includes/header.php](/code/files/forms/includes/header.php.md)

**Included by:** [forms/help/blocks.php](/code/files/forms/help/blocks.php.md), [forms/help/drafts.php](/code/files/forms/help/drafts.php.md), [forms/help/layout.php](/code/files/forms/help/layout.php.md), [forms/help/tables.php](/code/files/forms/help/tables.php.md), [forms/help/who-can-request.php](/code/files/forms/help/who-can-request.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/tz.js](/code/files/assets/js/tz.js.md)

**Calls:** [formsHelpTopics()](/code/functions/formsHelpTopics.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

