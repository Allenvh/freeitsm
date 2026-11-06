# includes/projects/gatecheck.php

> PHP - 125 lines

Projects - stage gate checklists (3.3.0): what must be true before a "go".

Each stage's gate can carry items of four kinds:
  check     a plain tick, by anybody who may change the project
  document  ticked by choosing one of the project's attached documents
            (Documents module, parent type 'project'); open again if that
            document is later taken off the project
  signoff   a named analyst signs off - ONLY that analyst; they are told on
            the bell (project.signoff_requested) when the item is added
  change    a linked change (Changes module) that must be APPROVED - worked
            out from the change on every read, never stored as done

A gate is 'standard' or 'golive' (project_stages.gate_kind). Making it go-live
adds the go-live starter items - UAT sign-off, backout plan, change approved,
support handover - each unless the gate already has one by that name (a gate
can need several sign-offs); every one editable or removable.

🔑 project_gate_checklist says what an open item does to a go or go-with-
conditions: block (refused, naming what is open) or warn (allowed, and what
was open is written into the gate's notes, so the record says so). A stop is
never blocked. A gate with no items behaves exactly as before 3.3.0.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectGateItemsReady()](/code/functions/projectGateItemsReady.md) | 31 | True when the project_gate_items table exists (after Database Verification). Cached per request. |
| [projectGateDocuments()](/code/functions/projectGateDocuments.md) | 42 | The project's attached documents as {id, title} - what a document item may point at. |
| [projectGateChanges()](/code/functions/projectGateChanges.md) | 53 | The project's linked changes with whether each is approved - what a change item may point at. |
| [projectGateItems()](/code/functions/projectGateItems.md) | 69 | Every gate item of a project, each with `done` worked out, grouped by stage id. `$showChanges` = this analyst may open Changes (otherwise a change's title is left out). |
| [projectGateOpenItems()](/code/functions/projectGateOpenItems.md) | 108 | The items still open at one stage's gate: [{id, kind, title}]. |
| [projectGoLiveStarter()](/code/functions/projectGoLiveStarter.md) | 115 | The go-live starter checklist: [kind, title]. The sign-off goes to the project manager. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 | `projectGateItemsReady` | read [project_gate_items](/code/tables/project_gate_items.md) |  |
| 45 | `projectGateDocuments` | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |
| 56 | `projectGateChanges` | read [project_changes](/code/tables/project_changes.md), read [changes](/code/tables/changes.md) |  |
| 72 | `projectGateItems` | read [project_gate_items](/code/tables/project_gate_items.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/projects/settings.php](/code/files/includes/projects/settings.php.md)

**Included by:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [t()](/code/functions/t.md)

