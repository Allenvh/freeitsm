# assets/js/lms-my-courses.js

> JavaScript - 192 lines

LMS — My Courses (learner landing). Lists the courses assigned to me with my own status, how far through I am, and a Launch button. Read-only; no management here.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `esc(s)` | 9 | Escapes text for safe use in HTML. |
| `statusPill(row)` | 31 | The coloured status pill for one of the learner's courses - Overdue when past the deadline, otherwise the stored status. |
| `label(STATUS_LABEL[row.status] || (()` | 35 | The translated label for the course's status, or the raw status when there is no translation for it. |
| `launchLabel(status)` | 43 | The launch button's label for a course status - Review when passed or completed, Resume when incomplete or failed, otherwise Start. |
| `progressBar(row)` | 61 | The progress bar, and the reason it always carries words. |
| `cardHtml(row)` | 86 | HTML for one assigned course - title, description, status pill, score when passed or failed, deadline, progress bar and the launch button. |
| `render()` | 113 | Draws the learner's courses in the chosen layout, or the empty-state message when none are assigned. |
| `applyLayout(save)` | 132 | Marks the active layout button and redraws the list; when asked, saves the choice as the user's preference. |
| `setMycLayout(mode)` | 147 | Switches My courses to the list or cards layout (called from the layout buttons) and remembers the choice. |
| `loadLayoutPreference()` | 154 | Reads the user's saved My courses layout, falling back to the list layout. |
| `load()` | 168 | Loads the layout preference and the learner's assigned courses (my_courses.php) together, then draws the page or shows the error. |

## API endpoints it calls

- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)
- [api/system/get_user_preference.php](/code/files/api/system/get_user_preference.php.md)

## Loaded by

- [lms/my-courses.php](/code/files/lms/my-courses.php.md)

