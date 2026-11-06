# assets/js/lms-native-player.js

> JavaScript - 294 lines

LMS — the learner's player for an authored course.

One lesson per step: read the body, answer that lesson's questions, move on.
Answers are held locally until the end and graded in one go by the server —
the client has no answer key to check against, by design (see
api/lms/course_content.php), so it cannot tell you if you're right and cannot
be made to lie about it either.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `LMSPlayer(()` | 10 | LMS — the learner's player for an authored course. |
| `esc(s)` | 36 | Escapes text for safe use in HTML. |
| `elapsed()` | 38 | Seconds since the time count was last sent to the server. |
| `init()` | 44 | Loads the course and its lessons, registers the attempt and resumes at the saved bookmark, then draws the contents list and the lesson. Saves a bookmark when the page is closed before finishing. |
| `renderToc()` | 78 | Draws the lesson contents list (current and done lessons marked) and updates the progress bar and "step n of m" label. |
| `renderLesson()` | 100 | Shows the current lesson's body and its quiz questions, keeping answers already given, and sets the Previous / Next (or Finish) buttons. |
| `unanswered()` | 150 | Every question in the course must be answered before it can be graded. |
| `go(i)` | 159 | Moves to lesson i, saving a bookmark first. |
| `next()` | 168 | Moves to the next lesson, or finishes the course on the last one. |
| `prev()` | 174 | Moves back one lesson. |
| `saveBookmark(unloading)` | 180 | Saves the current lesson and the time spent through native_progress.php; when the page is closing it uses sendBeacon so the request survives. |
| `finish()` | 201 | Submits every answer for grading through native_progress.php, asking first when some questions are unanswered, then shows the result. |
| `renderResult(d)` | 237 | Shows the graded result - passed, failed or completed with the score and pass mark - then the review of each question with the correct answer and explanation, and the Finish (and Retry on a fail) buttons. |

## Loaded by

- [lms/native-player.php](/code/files/lms/native-player.php.md)
- [self-service/course.php](/code/files/self-service/course.php.md)

