# self-service/training.php

> PHP - 242 lines

Self-Service Portal — My training.

The portal end of the LMS: the courses somebody has been given, and where
they got to. The data comes from api/lms/my_courses.php, the SAME endpoint the
analyst My Courses page uses — the two views are the same question asked by
two different kinds of person, and `lmsMyCourses()` answers it once so they
cannot drift apart.

⚠️ NOTHING HERE DECIDES WHAT SOMEBODY MAY SEE. The endpoint returns only what
is assigned to the caller, and course.php re-checks on the way in, so a
hand-typed course id gets nowhere. This page draws what it is given.

## How it hangs together

**Includes:** [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/includes/footer.php](/code/files/self-service/includes/footer.php.md)

**Calls:** [t()](/code/functions/t.md)

