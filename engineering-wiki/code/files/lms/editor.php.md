# lms/editor.php

> PHP - 257 lines

LMS — the course editor (native courses only).

Left: the lessons, in order, draggable. Right: the selected lesson's body in
TinyMCE, and the questions that follow it. A SCORM course has no lessons to
edit, so it is bounced back to the list rather than shown an editor that
cannot do anything.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | read [lms_courses](/code/tables/lms_courses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [lms/includes/header.php](/code/files/lms/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/tinymce/tinymce.min.js](/code/files/assets/js/tinymce/tinymce.min.js.md), [assets/js/lms-editor.js](/code/files/assets/js/lms-editor.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireCapability()](/code/functions/requireCapability.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

