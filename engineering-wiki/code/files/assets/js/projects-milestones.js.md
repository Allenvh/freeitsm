# assets/js/projects-milestones.js

> JavaScript - 213 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 4 Plan and Timeline](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-4-Plan-and-Timeline)

Projects - milestones on a project's page (3.3.0).

A milestone is a named date the project promises ("Move day"). This file
draws them in three places and owns the one dialog that edits them:
  Overview  #pvMilestones - what is coming up and what was missed, with Done
  Plan      diamonds in each lane's head (chips()) and a strip for the whole
            project's ones (projectStrip()), plus the Milestone button
  Timeline  projects-timeline.js draws them and calls `open()` / `save()`

Done, missed or due is worked out by the server (includes/projects/milestones.php)
and sent as `state`; this file never decides it, so the page, the health and the
bell always agree.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `canChange()` | 26 | True when the viewer may change this project. |
| `list()` | 30 | The project's milestones, or an empty list before the page has loaded. |
| `diamond(m, size)` | 35 | The diamond, coloured by state. |
| `daysFromToday(d)` | 40 | Days from today to a date - negative when it has passed. |
| `phrase(m)` | 43 | "Reached on time" / "Missed" / "In 5 days" - a phrase for the state. |
| `chip(m)` | 54 | A clickable chip: diamond, name, date. |
| `chips(stageId)` | 60 | The diamonds in one lane's head (stageId) - '' when it has none. |
| `projectStrip()` | 66 | The whole project's milestones, above the lanes on the Plan. |
| `renderOverview()` | 74 | Draws the Overview's milestones panel - missed first, then up to four coming up, then the last two reached, each with Done when it can still be marked; hidden when there are none and nobody can add one. |
| `item(m)` | 85 | One milestone row on the Overview - diamond, name, date, stage, state phrase and the Done button. |
| `stageOptions(selected)` | 102 | The stage drop-down options for the dialog, with "Whole project" first and the given stage selected. |
| `open(m, defaults)` | 108 | Open the dialog for milestone m, or a new one (defaults: {stage_id, due_date}). |
| `save(body)` | 129 | Write a milestone (a partial body is fine) and redraw. Returns the server's reply. |
| `saveDialog()` | 142 | Saves the milestone dialog and redraws the project; reopens the dialog with the error when the save fails. |
| `remove()` | 167 | Asks, then deletes the milestone open in the dialog. |
| `render(c)` | 203 | Keeps the page's data and draws the Overview's milestones panel. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

