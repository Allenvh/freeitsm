# assets/js/lms.js

> JavaScript - 925 lines

LMS Dashboard JavaScript Handles CRUD for courses, learning groups, assignments, and progress tracking.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `LMS(()` | 5 | LMS Dashboard JavaScript Handles CRUD for courses, learning groups, assignments, and progress tracking. |
| `init()` | 19 | Init |
| `openCreateModal()` | 34 | Creating an authored course Only the shell is made here — the title, the blurb and the pass mark. The lessons are written in the editor, so we go straight there. |
| `createCourse(e)` | 42 | Creates an authored (native) course from the New course modal through course.php and opens it in the editor. |
| `switchTab(tab)` | 65 | Tab switching |
| `loadCourses()` | 80 | Courses |
| `renderCourses()` | 95 | Draws the Courses table - title, SCORM version or "Authored", created date, active state, and Launch / Edit / Delete buttons (Edit only for authored courses). |
| `openUploadModal()` | 135 | Opens the SCORM upload modal with its fields and progress bar reset. |
| `uploadCourse(e)` | 145 | Uploads a SCORM package to courses.php with a progress bar, then reloads the course list. |
| `onload()` | 170 | Resolves with the upload's JSON reply, or rejects when it is not valid JSON. |
| `onerror()` | 175 | Rejects when the upload fails at the network level. |
| `deleteCourse(id)` | 198 | Deletes a course after a confirm and reloads the list. |
| `loadGroups()` | 215 | Learning Groups |
| `renderGroups()` | 227 | Draws the Learning groups table with each group's members and Edit / Delete buttons. |
| `loadAnalysts()` | 248 | Loads the analysts for the learning group member picker. |
| `openGroupModal(group = null)` | 257 | Opens the learning group modal, new or filled from a group, with a tick box per analyst for its members. |
| `editGroup(id)` | 274 | Opens the learning group modal for one group. |
| `saveGroup(e)` | 283 | Saves the learning group modal - a new group through groups.php, an existing one through group.php (PUT) - and reloads the groups. |
| `deleteGroup(id)` | 323 | Deletes a learning group after a confirm and reloads the groups. |
| `loadAssignments()` | 339 | Assignments |
| `renderAssignments()` | 354 | Draws the Assignments table - course, who it goes to, deadline, who assigned it - with Edit and Delete buttons. |
| `openAssignModal(assignmentId)` | 388 | Create, or edit an existing assignment when given its id. |
| `assignTargetChanged()` | 480 | Shows the person search in the assign modal when "a named person" is chosen, and clears the chosen person otherwise. |
| `searchAssignPeople()` | 495 | Searches analysts and portal users for the assign modal (debounced, two characters minimum) and lists them to pick one. |
| `saveAssignment(e)` | 533 | Saves the assign modal - course, target (a group, everyone, or one person) and deadline - creating through assignments.php or updating through assignment.php, then reloads. |
| `deleteAssignment(id)` | 583 | Removes an assignment after a confirm and reloads the assignments. |
| `loadProgress()` | 599 | Progress |
| `fillAnalystFilter(learners, current)` | 651 | Takes `learners` now — analysts AND portal users — each keyed "type:id" rather than by a bare id, because analyst 12 and portal user 12 are two different people and a plain number cannot tell them apart. |
| `renderProgress(data)` | 666 | Draws the Progress table - learner, course, group, status (Overdue when past the deadline), score, deadline, last access - with a View button for learners who have started. |
| `viewLearnerData(learnerKey, courseId)` | 716 | `learnerKey` is "analyst:12" or "user:257". Split from the right, because only the id is guaranteed free of colons. |
| `formatTime(timeStr)` | 843 | Formats a SCORM time (PT5M30S or HH:MM:SS) as a short "1h 5m" / "5m 30s" / "30s" string. |
| `formatJson(obj, indent = 0)` | 868 | Pretty-prints a value as indented, colour-classed HTML JSON for the learner data viewer. |
| `openModal(id)` | 892 | Modal helpers |
| `closeModal(id)` | 894 | Closes a modal by id. |
| `esc(str)` | 899 | Utilities |
| `statusText(status)` | 909 | Translate a normalised LMS status code to its display label. Unknown codes (e.g. SCORM-reported values without a key) fall back to a prettified version of the raw code. |

## Loaded by

- [lms/index.php](/code/files/lms/index.php.md)

