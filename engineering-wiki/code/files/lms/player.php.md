# lms/player.php

> PHP - 139 lines

LMS SCORM Player Loads a SCORM course in an iframe with the API bridge on the parent window.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [lms_courses](/code/tables/lms_courses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md), [lms/native-player.php](/code/files/lms/native-player.php.md), [lms/includes/header.php](/code/files/lms/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/scorm-api.js](/code/files/assets/js/scorm-api.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsCanAccessCourse()](/code/functions/lmsCanAccessCourse.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

