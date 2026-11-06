# api/lms/my_courses.php

> API endpoint - 38 lines

LMS API: the courses assigned to whoever is signed in — the My Courses feed, and the portal's Training page.

Learner-facing: it only ever returns the caller's OWN assigned courses (the
query is scoped to what reaches them), so there's nothing here a learner
shouldn't see. All the real logic lives in `lmsMyCourses()` so the two pages and
this endpoint can't disagree.

🔑 THE MODULE GATE IS ANALYST-ONLY. `lms` is a module in the analyst app's
access model; a portal user has no module list and asking about theirs would
refuse every one of them. What entitles a portal learner is the assignment
itself, which `lmsMyCourses()` has already applied — so for them the gate is the
data, not a permission lookup.

## The endpoint

| | |
|---|---|
| URL | `api/lms/my_courses.php` |
| Protected by | Module access: lms (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsMyCourses()](/code/functions/lmsMyCourses.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

