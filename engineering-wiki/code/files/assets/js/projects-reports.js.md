# assets/js/projects-reports.js

> JavaScript - 324 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 7 Alerts Reports and AI](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-7-Alerts-Reports-and-AI)

Projects - the Overview's briefing and the Reports tab: the AI project manager (3.2.0). Rules: includes/services/project_reports.php; the AI: includes/projects/ai.php.

The AI only PROPOSES. A briefing is marked as the AI's; a report is a draft
until someone who may approve it does, and an approved report is final.
Report text is a small Markdown subset (## heading, - bullet, **bold**),
drawn by `md()` below, which escapes everything first.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 17 | Translates a key under projects.reports.*. |
| `call(body)` | 28 | Posts a reports action to reports.php for the current project. |
| `when(s)` | 30 | A date-time formatted for the viewer, or the raw value when the formatter is not loaded. |
| `md(text)` | 33 | Escape, then the three marks the AI is told to use. Nothing else becomes markup. |
| `inline(s)` | 35 | Escapes one line and turns **bold** into strong text. |
| `flush()` | 40 | Writes the paragraph lines collected so far as one paragraph. |
| `close()` | 42 | Closes the bullet list if one is open. |
| `aiError(r)` | 57 | Shows a not-configured or unreachable toast when the reply carries an AI error; returns true when it did. |
| `fetchState()` | 64 | Loads the project's briefing, reports, schedule and permissions from reports.php once, then draws. |
| `drawBriefing()` | 75 | Draws the Overview's AI briefing panel - the briefing and who asked for it, a Brief me / Refresh or set-up button, or a wait spinner; hidden when there is nothing to show. |
| `brief()` | 92 | Asks the AI for a fresh briefing and redraws the panel. |
| `badges(r)` | 104 | A report's badges - kind, status, and AI-drafted or AI-drafted-then-edited. |
| `drawReports()` | 112 | Draws the Reports tab - the new report picker (kind, period, Draft with AI, Write), the scheduled drafts setting and the list of reports. |
| `draw()` | 148 | Draws both the briefing and the Reports tab. |
| `draftWithAi()` | 151 | Asks the AI to draft a report of the chosen kind over the chosen period and opens the draft. |
| `setMode(mode)` | 165 | Switches the report dialog between editing the Markdown and the rendered preview. |
| `openReport(id)` | 175 | Opens the report dialog for a report, or a new one of the chosen kind; an approved report is read-only and the buttons follow the viewer's rights. |
| `fail(e)` | 201 | Shows an error in the report dialog. |
| `save(quiet)` | 204 | Saves the report in the dialog and returns its id; unless quiet, closes the dialog and redraws the list. |
| `approve()` | 215 | Asks, saves any unsaved edit, then approves the report - which makes it final - and redraws. |
| `remove()` | 229 | Asks, then deletes the report open in the dialog. |
| `openSend()` | 238 | Opens the send dialog for an approved report, listing the people it can go to with tick boxes, plus a box for another address. |
| `send()` | 252 | Emails the approved report to the ticked people and any other address, saying which failed. |
| `setSchedule()` | 273 | Saves how often a report is drafted automatically, and of which kind. |
| `copy()` | 285 | Copies the report's title and text to the clipboard. |
| `wire()` | 292 | Wires the briefing and Reports tab buttons and the report and send dialogs, once. |
| `render(c)` | 316 | Called by projects-view.js after every redraw: the Overview's slot is new each time. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

