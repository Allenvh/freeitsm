# assets/js/projects-view.js

> JavaScript - 983 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 1 Architecture](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-1-Architecture)

Projects - one project's page (3.2.0).

Banner + Overview / Plan / Timeline / History. Everything is drawn from one call to
api/projects/get.php and redrawn after each change, so the ring, the counts
and the plan can never disagree with each other.

The Plan: each phase / stage / sprint is a lane of task rows; a task can be
dragged onto another lane (or onto "not in a phase yet"), and a task can be
added in place. Task rows open the task on the Tasks board - the task window
there is the one place a task is edited, so this page never grows a second one.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `timeboxKind()` | 30 | What the project's way of running calls its time boxes - phase, stage or sprint. |
| `methodLabel()` | 35 | The name of the project's way of running, for the banner. |
| `renderBanner()` | 42 | Draws the project banner - colour, icon, code, status, method, members-only and priority chips, company, name, goal, owner and dates, and the progress ring with its health. |
| `renderOverview()` | 87 | Draws the Overview - the tiles, the warnings (exceptions, ticket spike, missed milestones, late RAID, benefit reviews, waiting change requests, escalations), the slots the other files fill, About, Now, Next up, Recent and the connections summary. |
| `burnupPoints()` | 196 | Scope and done over time, worked out here from the tasks the page already has: each top-level task counts from the day it was created (scope) and the day it was completed (done) - one each, or its estimate in hours. A task moved into the project later counts from its creation; that is the honest reading of what is stored. |
| `val(t)` | 198 | What one task counts for on the burn-up - one, or its estimate in hours. |
| `created(t)` | 202 | The date a task was created, as Y-m-d. |
| `doneOn(t)` | 204 | The date a closed task was completed, or null while it is open. |
| `dn(s)` | 209 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `iso(n)` | 211 | A day number back to a Y-m-d date. |
| `renderBurnup()` | 222 | Draws the progress-over-time panel - stage, tasks / hours and burn-up / burndown switches, then the chart, or a note when there is nothing to plot. |
| `stageMini(s)` | 270 | A small card for an active stage on the Overview - name, tasks done of total, goal and a progress bar. |
| `historyItem(h)` | 281 | One history line - who, what changed in the viewer's words, the detail (translated where the stored value is a key) and when. |
| `renderHistory()` | 319 | Draws the History tab - every history line, newest first, or a note when there is none. |
| `hrsTxt(h)` | 327 | "4h" / "1.5h" in the page's language. |
| `estimateCell(t)` | 335 | A task's estimate on the Plan (3.3.0): a small box to type it in, for those who may change the project; text for everyone else. Time logged shows beside it, red once it is over the estimate. |
| `taskRow(t)` | 347 | One task row in a Plan lane - drag grip, status, title linking to the task, subtasks, priority (when not the default), estimate, waiting / clash flags, contractor, due date, assignee and remove. |
| `isDefaultPriority(id)` | 369 | The default priority is what most tasks have, so its pill is noise: only the exceptions are shown. |
| `addRow(stageId)` | 379 | Each lane offers a light "+ Add a task" that opens the form. The lane whose form is open is remembered across redraws, so adding several tasks in a row keeps the box open and focused. |
| `lane(stage, tasks)` | 394 | One Plan lane - a stage's header (kind, name, goal, milestones, dates, status, counts, effort, start / finish and edit buttons), progress bar, task rows and add row; or the "not in a stage yet" lane when stage is null. |
| `renderPlan()` | 433 | Draws the Plan tab - the intro, Milestone and add-stage buttons, the project milestones strip, a lane per stage and the unassigned lane. |
| `renderAll()` | 452 | Redraws the whole page from the data - applies the viewer's permissions, draws the banner, Overview, Plan, Connections and History, shows only switched-on tool tabs, and hands the data to every other Projects file. |
| `showTab(name)` | 493 | Shows a tab and puts it in the address bar; charts on the shown tab draw now that they can measure their width, and the Gates tab refreshes if a document was added meanwhile. |
| `load()` | 507 | Loads the project, the lookups and its connections together and draws the page, or shows "not found"; a new empty project opens with the add-a-task box ready. |
| `refresh()` | 528 | Reloads the project from get.php and redraws the page. |
| `linkHistoryText(v)` | 540 | A connection's history value ("asset: name") with the kind in the viewer's words. |
| `linksSummary()` | 547 | "Connected to" chips on the Overview, one per kind that has links. |
| `linkRow(kind, r)` | 557 | One connected record on the Connections tab - label linking to it, sub-line, status and unlink. |
| `announcePanel()` | 575 | Service Status (3.2.0, going live safely): disruption this project has announced, and the Announce button. data.announcements is null for somebody who cannot open Service Status - then there is no panel at all. Planned maintenance is not an incident until it starts; the server turns it into one at its start and resolves it at its end. |
| `when(p)` | 580 | An announcement's time in words - from start to end, or from start with no end yet. |
| `announceRow(serviceId, impactId)` | 599 | Adds a service-and-impact row to the announce dialog; the impact defaults to the most serious level that is neither downtime nor the all-clear (Maintenance on a stock install). |
| `openAnnounce()` | 615 | Opens the announce-disruption dialog, empty, with one service row and, for planned work, the active stage's end as a suggested start. |
| `saveAnnounce()` | 634 | Announces the disruption on Service Status through tools.php, then reloads the project and redraws Connections and History. |
| `withdrawAnnounce(id)` | 653 | Asks, then withdraws a scheduled announcement and redraws Connections. |
| `renderConnections()` | 667 | Draws the Connections tab - the Service Status panel, then a panel per kind of linked record with its rows and a search box to add one. |
| `reloadLinks()` | 688 | Reloads the project's connections and redraws the Connections tab and the Overview. |
| `searchLinks(input)` | 696 | Searches records of one kind (after a 180ms pause) to connect to the project, showing the matches under the box. |
| `linkAction(action, value)` | 713 | Connects or disconnects a record, then reloads the project and its connections and redraws; after adding, the search box keeps focus for the next one. |
| `openStage(stage)` | 729 | Opens the stage dialog filled from stage, or empty for a new one, worded for the project's kind of time box. |
| `saveStage()` | 747 | Saves the stage dialog through stage_save.php and redraws; closing a stage this way is cheered. |
| `setStageStatus(id, status)` | 770 | Starts or finishes a stage from its lane's button and redraws; finishing is cheered. |
| `cheer(name, stageId)` | 781 | Celebrates a finished stage on its lane (or the ring) with a toast. |
| `deleteStage()` | 788 | Asks, then deletes the stage open in the dialog. |
| `addTask(form)` | 804 | Creates a task in a lane from its add form through task_create.php, redraws and puts the cursor back in the same form for the next one. |
| `moveTask(taskId, stageId)` | 828 | Moves a task to another lane (or out of every lane) through task_assign.php and redraws; nothing happens when it is already there. |
| `removeTask(taskId)` | 838 | Asks, then takes a task off the project (the task itself is kept) and redraws. |
| `editProject()` | 849 | Opens the project form and redraws after saving; closing the project this way is cheered. |
| `deleteProject()` | 862 | Asks, then deletes the project and goes back to the portfolio. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

