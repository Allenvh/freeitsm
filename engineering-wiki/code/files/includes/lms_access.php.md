# includes/lms_access.php

> PHP - 615 lines

LMS access rules — the learner/manager split (RBAC pilot).

Two distinct things a person can do with the LMS:
  - TAKE assigned courses. Needs the 'lms' module only. A learner.
  - MANAGE the LMS (author, upload, run groups, assign, see everyone's
    progress). Needs the 'lms.manage' capability (or is_admin). A manager.

These helpers are the single source of truth for both, used by the pages, the
APIs and the playback gate so the rule can't drift between them.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [lmsCanManage()](/code/functions/lmsCanManage.md) | 146 | May this analyst manage the LMS? (is_admin bypasses, via analystHasCapability.) |
| [lmsIsOverdue()](/code/functions/lmsIsOverdue.md) | 177 | Has this course's deadline passed? |
| [lmsUserGroupsAvailable()](/code/functions/lmsUserGroupsAvailable.md) | 195 | Are the shared people groups available on this install? |
| [lmsAssignmentReachSql()](/code/functions/lmsAssignmentReachSql.md) | 229 | THE ONE PLACE THAT SAYS WHICH ASSIGNMENTS REACH A LEARNER. |
| [lmsCourseAssignedTo()](/code/functions/lmsCourseAssignedTo.md) | 273 | Is this course assigned to this learner? This is what they are *entitled* to take, by any of the routes above. |
| [lmsCanAccessCourse()](/code/functions/lmsCanAccessCourse.md) | 298 | May this learner open/play this course? Managers (and admins) may open any course — that's how Preview works. Everyone else may open only what's assigned to them. This is the gate the player and the learner content APIs enforce. |
| [requireLmsCourseAccessJson()](/code/functions/requireLmsCourseAccessJson.md) | 311 | Hard gate for a learner course API: 403 unless the caller may access `$courseId`. Resolves the learner from whichever session is present, so one guard covers both the analyst app and the portal. Assumes the session check and module gate have already run. |
| [lmsMyCourses()](/code/functions/lmsMyCourses.md) | 336 | The courses assigned to a learner by any route, each with their own progress — the data behind My Courses and the portal's Training page. One row per course even if it reaches them several ways (earliest deadline wins). is_overdue is computed here so the page and any caller agree. |
| [lmsAttachLessonProgress()](/code/functions/lmsAttachLessonProgress.md) | 391 | How far through a course somebody is, as "lesson N of M". |
| [lmsProgressRowFor()](/code/functions/lmsProgressRowFor.md) | 457 | This learner's progress row for a course, created on first sight. |
| [lmsAssignedLearnersSql()](/code/functions/lmsAssignedLearnersSql.md) | 509 | THE OTHER DIRECTION: every (learner, course) an assignment reaches. |
| [lmsAssignmentTargetProblem()](/code/functions/lmsAssignmentTargetProblem.md) | 584 | Is this target real? Returns a human-readable problem, or null if it is fine. |

## Classes

- [LmsLearner](/code/classes/LmsLearner.md) (class, line 32) - WHO IS TAKING A COURSE.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 199 | `lmsUserGroupsAvailable` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 235 | `lmsAssignmentReachSql` | read [lms_learning_groups](/code/tables/lms_learning_groups.md), read [lms_learning_group_members](/code/tables/lms_learning_group_members.md) |  |
| 253 | `lmsAssignmentReachSql` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md), read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md) |  |
| 277 | `lmsCourseAssignedTo` | read [lms_course_assignments](/code/tables/lms_course_assignments.md) |  |
| 339 | `lmsMyCourses` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [lms_courses](/code/tables/lms_courses.md), read [lms_progress](/code/tables/lms_progress.md) |  |
| 411 | `lmsAttachLessonProgress` | read [lms_lessons](/code/tables/lms_lessons.md) |  |
| 459 | `lmsProgressRowFor` | read [lms_progress](/code/tables/lms_progress.md) |  |
| 471 | `lmsProgressRowFor` | **insert** [lms_progress](/code/tables/lms_progress.md) (`analyst_id`, `learner_type`, `learner_id`, `course_id`, `status`, `first_access`, `last_access`, `attempt_count`, `created_datetime`, `updated_datetime`) |  |
| 514 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [lms_learning_groups](/code/tables/lms_learning_groups.md), read [lms_learning_group_members](/code/tables/lms_learning_group_members.md), read [analysts](/code/tables/analysts.md) |  |
| 524 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md), read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md) |  |
| 534 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [users](/code/tables/users.md) |  |
| 544 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [analysts](/code/tables/analysts.md) |  |
| 550 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [users](/code/tables/users.md) |  |
| 589 | `lmsAssignmentTargetProblem` | read [lms_learning_groups](/code/tables/lms_learning_groups.md) |  |
| 596 | `lmsAssignmentTargetProblem` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 602 | `lmsAssignmentTargetProblem` | read [analysts](/code/tables/analysts.md) |  |
| 608 | `lmsAssignmentTargetProblem` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/rbac.php](/code/files/includes/rbac.php.md)

**Included by:** [api/lms/assignment.php](/code/files/api/lms/assignment.php.md), [api/lms/assignments.php](/code/files/api/lms/assignments.php.md), [api/lms/assignment_targets.php](/code/files/api/lms/assignment_targets.php.md), [api/lms/course_content.php](/code/files/api/lms/course_content.php.md), [api/lms/my_courses.php](/code/files/api/lms/my_courses.php.md), [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md), [api/lms/progress.php](/code/files/api/lms/progress.php.md), [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md), [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), [includes/people.php](/code/files/includes/people.php.md), [lms/player.php](/code/files/lms/player.php.md), [self-service/course.php](/code/files/self-service/course.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md)

