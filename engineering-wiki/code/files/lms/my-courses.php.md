# lms/my-courses.php

> PHP - 196 lines

LMS — My Courses (the learner's landing).

Everyone with the LMS module gets this. It shows only the courses assigned to
them, with their own status and a Launch button — no management, no other
people's data. Managers and admins can reach it too (they take training as
well), but their default landing is the dashboard.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [lms/includes/header.php](/code/files/lms/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/lms-my-courses.js](/code/files/assets/js/lms-my-courses.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

