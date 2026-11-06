# assets/js/lms-editor.js

> JavaScript - 640 lines

LMS course editor — lessons, questions, and the three AI helpers.

The AI never writes to the database. Every helper drops its draft into the
form the author is already looking at, and it is saved by the same validated
endpoint a hand-typed lesson goes through. That is what keeps a bad generation
a nuisance rather than a problem.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `LMSEditor(()` | 9 | LMS course editor — lessons, questions, and the three AI helpers. |
| `esc(s)` | 28 | Escapes text for safe use in HTML. |
| `init()` | 36 | Starts the course editor - the TinyMCE editor, the lesson list and the pass-mark chip - and wires the course, question and AI forms. |
| `initTinyMCE()` | 51 | Creates the TinyMCE editor for the lesson body, themed light or dark, with the full-screen button first on a phone. |
| `setEditorBody(html)` | 105 | Write to the editor, or remember it until the editor exists. Every caller goes through here so none of them has to know whether TinyMCE has finished starting up. |
| `snapshotBaseline()` | 141 | 🔴 WHAT "UNSAVED CHANGES" IS MEASURED AGAINST. It used to be the lesson as the API returned it, compared with `editor.getContent()`. Those two are not the same kind of string: TinyMCE RE-SERIALISES what you give it, and by default (`entity_encoding: 'named'`) it writes non-ASCII characters back as named entities. So a body stored as |
| `loadLessons()` | 152 | Loads the course's lessons (with their questions) from lessons.php, redraws the list and opens the first lesson when none is selected yet. |
| `renderLessonList()` | 168 | Draws the numbered lesson list with question counts and delete buttons, and wires click-to-open and drag-to-reorder on each row. |
| `dropOn(targetId)` | 204 | Moves the dragged lesson to the position of the lesson it was dropped on and saves the new order through lessons.php (REORDER). |
| `selectLesson(id)` | 218 | Opens a lesson in the editor pane, asking first when the current lesson has unsaved changes. |
| `isDirty()` | 238 | True when the lesson title or body differs from what was last loaded or saved. |
| `newLesson()` | 245 | Asks for a title, creates an empty lesson with it and opens it. |
| `createLesson(title, body)` | 252 | Creates a lesson through lessons.php, reloads the list and returns the new id, or null on failure. |
| `saveLesson()` | 260 | Saves the open lesson's title and body through lessons.php and resets the unsaved-changes baseline. |
| `deleteLesson(id)` | 278 | Deletes a lesson after a confirm, closes the editor pane if it was the open one and reloads the list. |
| `currentLesson()` | 300 | The lesson currently open in the editor. |
| `renderQuestions()` | 306 | Draws the open lesson's quiz questions with their answers, the correct ones marked, and Edit / Delete buttons. |
| `typeLabel(t)` | 335 | The translated label for a question type (single, multiple, truefalse). |
| `openQuestionModal(q)` | 340 | Opens the question modal, filled from a question when editing or with two blank answers when adding. |
| `editQuestion(id)` | 358 | Opens the question modal for one question of the open lesson. |
| `addAnswerRow(text = '', correct = false)` | 364 | Adds an answer row to the question modal - a radio button for single-answer types, a tick box for multiple. |
| `onTypeChange()` | 383 | Reshapes the answer rows when the question type changes - fixed True / False rows for truefalse, radio or tick boxes otherwise - and keeps only one answer ticked for single-answer types. |
| `saveQuestion(e)` | 425 | Saves the question modal (text, type, explanation and the non-empty answers) through questions.php and reloads the lessons. |
| `deleteQuestion(id)` | 448 | Deletes a question after a confirm and reloads the lessons. |
| `renderPassMark()` | 465 | Shows the course's pass mark, or "no pass mark", on the chip in the header. |
| `openCourseModal()` | 473 | Opens the course settings modal filled with the title, description and pass mark. |
| `saveCourse(e)` | 481 | Saves the course title, description and pass mark through course.php and updates the header. |
| `generateOutline(e)` | 509 | Asks the AI for a course outline on a topic and creates one lesson per outline item, each holding the AI's summary as a starting paragraph. |
| `openLessonAiModal()` | 536 | Opens the AI lesson modal, loading the knowledge article list into its drop-down the first time. |
| `generateLesson(e)` | 556 | Asks the AI to draft a lesson from a knowledge article or the lesson title and puts the draft in the editor - nothing is saved until the author presses Save. |
| `generateQuiz()` | 580 | Asks the AI for three quiz questions on the saved lesson and saves each through questions.php; refuses while the lesson has unsaved changes. |
| `escapeForHtml(s)` | 602 | Escapes text for safe use in HTML (the same as esc). |
| `busy(btn, on)` | 605 | Disables a button and shows the "thinking" label on it while an AI request runs, then puts the label back. |
| `post(endpoint, body)` | 615 | POSTs a JSON body to an LMS API endpoint and returns the JSON; a network error comes back as {success: false, error}. |
| `closeModal(id)` | 629 | Closes a modal by id. |

## Loaded by

- [lms/editor.php](/code/files/lms/editor.php.md)

