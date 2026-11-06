# files/includes/header.php

> PHP - 60 lines

Files module header — the waffle, the module title, the nav, the avatar.

On the desktop (files/index.php) this header is the SAME as every other
module's, for consistency, but each person chooses how it behaves there
(Personalise): on, auto-hide (slides down when the mouse reaches the top
edge) or off - the start menu then carries the module switcher, Help,
Settings and Sign out instead. That behaviour is the desktop's CSS/JS; this
file only renders the bar.

?embed=1 renders no header at all - the help page inside a desktop window.

Every URL here is built from BASE_URL, never a relative "../": the pages sit
at two depths (files/ and files/settings/).

## How it hangs together

**Included by:** [files/help.php](/code/files/files/help.php.md), [files/index.php](/code/files/files/index.php.md), [files/settings/index.php](/code/files/files/settings/index.php.md)

**Calls:** [renderHeaderRight()](/code/functions/renderHeaderRight.md), [renderWaffleMenuButton()](/code/functions/renderWaffleMenuButton.md), [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md), [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md), [t()](/code/functions/t.md)

