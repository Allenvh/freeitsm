# api/lms/assignment.php

> API endpoint - 97 lines

LMS API: change or remove a single assignment.

PUT    course, who it is for, and the deadline
DELETE remove it

⚠️ EDITING THE TARGET IS ALLOWED, and is the point of having an edit at all:
"I meant Finance, not All staff" was previously delete-and-start-again, which
loses the deadline you had already typed. Changing the course is allowed for
the same reason — the row means "this course, for these people", and either
half can be the one you got wrong.

🔑 NOBODY'S PROGRESS IS TOUCHED BY ANY OF THIS. lms_progress is keyed on
(learner, course) and knows nothing about assignments, so moving an assignment
from one group to another does not erase what anyone has already done — if
they are still reached by it, their existing record is simply still theirs.
Somebody who is no longer reached keeps their record too; it just stops being
expected of them. That is the right behaviour and it is worth stating,
because "will this wipe the training records?" is the first thing anybody
sensible asks before pressing Save.

## The endpoint

| | |
|---|---|
| URL | `api/lms/assignment.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | read [lms_course_assignments](/code/tables/lms_course_assignments.md) |  |
| 73 |  | **update** [lms_course_assignments](/code/tables/lms_course_assignments.md) (`course_id`, `target_type`, `group_id`, `deadline`) |  |
| 91 |  | **delete** [lms_course_assignments](/code/tables/lms_course_assignments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsAssignmentTargetProblem()](/code/functions/lmsAssignmentTargetProblem.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

