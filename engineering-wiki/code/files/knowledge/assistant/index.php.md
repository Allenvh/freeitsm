# knowledge/assistant/index.php

> PHP - 576 lines

Knowledge — the assistant.

Reads what the service desk has actually been answering and reports, in plain
language, what the knowledge base is missing. Not a dashboard of metrics: a
short statement ("I read 340 closed tickets. Your knowledge base is missing
six things.") followed by the six things, each with the evidence behind it and
two decisions — write it, or say it isn't needed.

The framing is deliberate. "This ticket could be an article" is unknowable
from one ticket, and a button offering it on every ticket produces a knowledge
base full of "reset the user's password". "You have answered this fourteen
times and have no article" is a fact, and it is the one worth acting on.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [knowledge/includes/header.php](/code/files/knowledge/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/safe-html.js](/code/files/assets/js/safe-html.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

