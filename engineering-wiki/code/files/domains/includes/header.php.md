# domains/includes/header.php

> PHP - 53 lines

Domains module header — the waffle, the module title, the nav, the avatar.

Every URL here is built from BASE_URL, never a relative "../": the pages sit
at two depths (domains/ and domains/<page>/), and a relative link that works
from one is broken from the other.

## How it hangs together

**Included by:** [domains/accounts/index.php](/code/files/domains/accounts/index.php.md), [domains/dashboard/index.php](/code/files/domains/dashboard/index.php.md), [domains/help.php](/code/files/domains/help.php.md), [domains/index.php](/code/files/domains/index.php.md), [domains/settings/index.php](/code/files/domains/settings/index.php.md), [domains/table/index.php](/code/files/domains/table/index.php.md), [domains/view.php](/code/files/domains/view.php.md)

**Calls:** [renderHeaderRight()](/code/functions/renderHeaderRight.md), [renderWaffleMenuButton()](/code/functions/renderWaffleMenuButton.md), [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md), [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md), [t()](/code/functions/t.md)

