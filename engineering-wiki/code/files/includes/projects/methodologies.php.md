# includes/projects/methodologies.php

> PHP - 175 lines

Projects - the methodology presets, the palette and the icon set (3.2.0).

🔑 A METHODOLOGY IS A LENS, NOT A SCHEMA. Every project is built from the same
pieces - tasks, time boxes (project_stages), and later the RAID log - and a
preset only says what the time boxes are called, how they behave and which
tools are switched on. That is why switching method halfway through is safe:
nothing is converted, nothing is deleted. Closed time boxes stay exactly as
they were; the open ones are relabelled. See docs/design/projects.md §3.

Defined in code, not in the database, for the same reason the Warbot tool
registry is: the behaviour behind each key is code, so the key list must be.

⚠️ PRINCE2 is a registered trademark. "Staged" is described in our own words as
a PRINCE2-STYLE approach. Never copy text from the manual into this module.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectsSchemaReady()](/code/functions/projectsSchemaReady.md) | 28 | Has Database Verification created the Projects schema yet? |
| [projectColourHex()](/code/functions/projectColourHex.md) | 48 | The first colour of a project's palette key - for a dot or a chip outside the module. |
| [projectMethodologies()](/code/functions/projectMethodologies.md) | 60 | The presets. 'timebox' is the project_stages.kind used for new and open time boxes; 'single_active' allows only one active time box at a time. |
| [projectToolDefinitions()](/code/functions/projectToolDefinitions.md) | 78 | The tools a project can switch on (phase 2). A method turns a set on by default ('tools' in `projectMethodologies()`); a project can TAILOR that - PRINCE2's own "tailor to suit the project" principle - and its choices are stored as JSON in projects.tailoring ({"raci": true, "raid": false}). Switching a tool off HIDES it; nothing it holds is deleted. |
| [projectEnabledTools()](/code/functions/projectEnabledTools.md) | 97 | The tools switched on for one project: its method's defaults, then its tailoring. |
| [projectStatuses()](/code/functions/projectStatuses.md) | 109 | Every status a project can have. |
| [projectPriorities()](/code/functions/projectPriorities.md) | 116 | Project priorities, lowest first (3.3.0). Stored as these keys; shown as projectScaleLabels('priority'). |
| [projectFinishedStatuses()](/code/functions/projectFinishedStatuses.md) | 122 | The project statuses that count as finished: closed and cancelled. |
| [projectHealthValues()](/code/functions/projectHealthValues.md) | 128 | The health values a project can be set to; 'auto' means work it out. |
| [projectStageKinds()](/code/functions/projectStageKinds.md) | 134 | The kinds of stage a project plan can have: phase, stage or sprint. |
| [projectStageStatuses()](/code/functions/projectStageStatuses.md) | 140 | The statuses a stage can have: planned, active or closed. |
| [projectColours()](/code/functions/projectColours.md) | 151 | The colour a project picks for its card: a fixed palette, never free CSS, so a stored value can only ever be one of these keys. |
| [projectIcons()](/code/functions/projectIcons.md) | 171 | The icons a project can pick. Keys only - the SVG lives in assets/js/projects.js so the stored value is never markup. |

## How it hangs together

**Included by:** [api/tasks/get.php](/code/files/api/tasks/get.php.md), [api/tasks/list.php](/code/files/api/tasks/list.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [projects/help.php](/code/files/projects/help.php.md), [projects/tutorial.php](/code/files/projects/tutorial.php.md)

