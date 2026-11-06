# system/contributors/index.php

> PHP - 167 lines

System → Contributors — thanks to the people who have made a substantial contribution to FreeITSM.

Read-only by design. The list lives in includes/contributors.php, a code
file, because it is part of what FreeITSM is rather than something each
install curates — see the note at the top of that file.

Card layout follows LMS → My courses (lms/my-courses.php), which is the
product's existing "a stack of readable cards" shape.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/contributors.php](/code/files/includes/contributors.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Calls:** [getContributors()](/code/functions/getContributors.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

