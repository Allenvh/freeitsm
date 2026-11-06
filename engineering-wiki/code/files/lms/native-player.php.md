# lms/native-player.php

> PHP - 87 lines

LMS — the player for an authored (native) course.

Included by player.php once it knows the course is native; `$course`, `$courseId`
and `$conn` are already set up there, and the session/module guard has run.

The content itself is fetched by the JS from api/lms/course_content.php rather
than rendered here, because that endpoint is the one that never sends the
answer key. Rendering the lessons server-side would mean writing a second
query, and a second chance to leak it.

## How it hangs together

**Includes:** [includes/base_url.php](/code/files/includes/base_url.php.md), [lms/includes/header.php](/code/files/lms/includes/header.php.md)

**Included by:** [lms/player.php](/code/files/lms/player.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/lms-native-player.js](/code/files/assets/js/lms-native-player.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

