# self-service/course.php

> PHP - 228 lines

Self-Service Portal — taking a course.

The portal's player. Both kinds of course work here, because neither the
authored player nor the SCORM runtime cares who is sitting in front of it:
  native  assets/js/lms-native-player.js, fed by api/lms/course_content.php
  scorm   the package in an iframe with assets/js/scorm-api.js bridging to
          api/lms/scorm_data.php

🔑 THE SAME JS AND THE SAME ENDPOINTS AS THE ANALYST PLAYER. A second player
written for the portal would be a second implementation of SCORM sequencing
and of quiz grading, and the day they disagreed one population's training
records would quietly stop matching the other's. Only the page around it —
portal chrome instead of analyst chrome — is different.

⚠️ The gate is re-checked HERE, server-side, before anything is drawn. The
Training page only ever links to assigned courses, but a link is a suggestion
and the URL is typeable.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [lms_courses](/code/tables/lms_courses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [self-service/includes/auth.php](/code/files/self-service/includes/auth.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/includes/footer.php](/code/files/self-service/includes/footer.php.md)

**Loads scripts:** [assets/js/confirm.js](/code/files/assets/js/confirm.js.md), [assets/js/lms-native-player.js](/code/files/assets/js/lms-native-player.js.md), [assets/js/scorm-api.js](/code/files/assets/js/scorm-api.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsCanAccessCourse()](/code/functions/lmsCanAccessCourse.md), [t()](/code/functions/t.md)

