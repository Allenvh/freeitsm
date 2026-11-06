# assets/js/lms-tests.js

> JavaScript - 716 lines

LMS -> Tests (competency tests). One file for the three analyst pages, chosen by <body data-ct-page="index|edit|result">: index - Tests, Question bank and Candidates tabs edit - the test builder: role, skills, questions, sending to candidates result - one candidate's result Shared pieces: the question editor and the bank picker (modals built here, so no page carries a copy of their markup). All writes go to api/lms/tests.php.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `L(key, en, p)` | 22 | Translates a key from the lms.tests language section, or fills the {placeholders} in the English given at the call site. |
| `esc(s)` | 24 | Escapes a value for safe use in HTML; null and undefined become an empty string. |
| `$(id)` | 26 | Shorthand for document.getElementById. |
| `get(params)` | 29 | GETs api/lms/tests.php with the given query parameters and returns the parsed JSON. |
| `post(body)` | 33 | POSTs a JSON body to api/lms/tests.php and returns the parsed JSON. |
| `toast(msg, kind)` | 40 | Shows a short toast message, creating the toast element on first use; errors stay up longer. |
| `fail(d)` | 48 | Shows the API's error, or a generic "That did not work", as an error toast. |
| `diffLabel(d)` | 51 | The translated label for a question difficulty (beginner, intermediate, advanced). |
| `fmtLabel(f)` | 53 | The translated label for a question format - graded (best answer) or multiple choice. |
| `statusLabel(s)` | 55 | The translated label for a question status (draft, approved, hidden). |
| `stateLabel(s)` | 60 | The translated label for a candidate's sitting state; a sitting whose time ran out still shows as In progress. |
| `when(utc)` | 65 | Formats a UTC date-time for display in the user's time zone. |
| `pct(n)` | 67 | Formats a number as a percentage to one decimal place, or an empty string when there is none. |
| `opts(list, sel, label)` | 69 | HTML option list from a list of values, labelled through the given label function, with one selected. |
| `modal(id, title, bodyHtml, actionsHtml)` | 72 | ------------------------------------------------------------------ modal shell |
| `closeModal(m)` | 87 | Closes a modal. |
| `questionCard(q, o)` | 93 | ------------------------------------------------------------------ question card |
| `editQuestion(q, onSaved, testId)` | 121 | Opens the question editor modal for a new or existing question and saves it through save_question. Switching the format converts the answers - choice to graded ranks the correct answer highest, graded to choice keeps only the best as correct. |
| `readRows()` | 137 | Copies what is typed in the answer rows back into the answers list before a redraw or save. |
| `draw()` | 149 | Redraws the answer rows for the current format - a radio button per answer for choice, a marks box per answer for graded. |
| `bankFilters(prefix, skills, withStatus)` | 192 | ------------------------------------------------------------------ bank (shared by the tab and the picker) |
| `bankQuery(prefix, statusOverride)` | 207 | The question bank query (search, skill, difficulty, format, status) read from the filter controls with the given id prefix. |
| `wireFilters(prefix, run)` | 212 | Re-runs a bank search when any filter with the given id prefix changes, debouncing typing in the search box. |
| `pickFromBank(testId, already, done)` | 225 | Opens the picker of approved and draft bank questions not already on the test, and adds the ticked ones to it through add_questions. |
| `count()` | 234 | Shows how many questions are ticked in the bank picker. |
| `run()` | 236 | Searches the bank with the picker's filters and lists the matches not already on the test. |
| `candidatesTable(rows, showTest)` | 262 | ------------------------------------------------------------------ candidates table (index tab + builder) |
| `showLink(link, note, emailed)` | 283 | Shows the candidate's one-time test link in a modal with a Copy button, saying whether it was emailed. |
| `linkNote(days)` | 299 | The note shown under a candidate link - shown only once, works once, and how many days it stays open. |
| `wireSittingActions(host, reload)` | 301 | Wires the New link, Cancel and Delete buttons on a candidates table, then reloads it. |
| `sendToCandidate(testId, canEmail, done)` | 322 | Opens the Send modal that creates a sitting for a candidate (name, optional email) and shows the link, emailing it when ticked and a mailbox can send. |
| `initIndex()` | 344 | ================================================================== INDEX |
| `show(tab)` | 350 | Switches the Tests page to a tab (Tests, Question bank, Candidates), records it in the URL hash and loads it. |
| `loadTests()` | 362 | Loads and lists the tests with their skills, question and candidate counts, and how many questions still need checking. |
| `loadBank()` | 385 | Builds the question bank filters on first use, then runs the bank search. |
| `runBank()` | 400 | Searches the question bank with the tab's filters and lists the questions with Approve, Edit, Hide / Unhide and Delete actions; clears the ticked selection. |
| `bulk()` | 421 | Shows the bulk Approve / Hide bar with the number of ticked bank questions, or hides it when none are ticked. |
| `loadCandidates()` | 456 | Loads every candidate sitting into the Candidates tab and shows the retention setting for finished candidates. |
| `initEdit()` | 472 | ================================================================== EDIT (builder) |
| `skillRow(sk)` | 482 | Adds an editable skill row (skill, difficulty, number of questions, format, Fill and Remove) to the test builder and returns it. |
| `readTest()` | 498 | The save_test payload read from the builder - title, role, time limit, pass mark and the skill rows. |
| `save(quiet)` | 508 | Saves the test through save_test, switching the URL to the new id on first save, then reloads it. |
| `haveFor(sk)` | 517 | How many of the test's questions (not hidden) match a skill row's skill, difficulty and format. |
| `load()` | 524 | Loads the test with its skills and questions into the builder, then draws them and the candidates; a new unsaved test just draws. |
| `render()` | 543 | Draws the builder's question counts per skill, the question list with its actions, and enables Send only when every question is approved. |
| `loadSittings()` | 568 | Loads the candidates for this test into the builder's candidates table. |
| `fill(rows)` | 577 | Fills the given skills with questions one at a time through generate - from the bank first when ticked, the AI writing the rest - then reports the totals and any errors. |
| `next()` | 585 | Fills the next skill in the list, or finishes - re-enabling the buttons, reporting the totals and reloading the test. |
| `initResult()` | 662 | ================================================================== RESULT |

## API endpoints it calls

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

