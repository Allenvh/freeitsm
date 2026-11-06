# assets/js/projects-tools.js

> JavaScript - 857 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 1 Architecture](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-1-Architecture)

Projects - the tools on a project's page (3.2.0 phase 2): People, Scope (the MoSCoW board) and the RACI matrix.

projects-view.js owns the page and its data; after every redraw it calls
PrjTools.render({data, L, projectId, refresh, page}) and this file draws the
three tool panels from that same data, so nothing here can disagree with the
banner or the plan. Every change goes to api/projects/tools.php and then asks
the view to refresh.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `canChange()` | 22 | True unless the server says the viewer may not change this project. |
| `call(body)` | 24 | Posts a tools action to tools.php for the current project. |
| `kindIcon(kind)` | 28 | The small icon for a member's kind - team, person, contractor or analyst. |
| `renderPeople()` | 31 | Draws the People tab - a card per member with kind, role picker and stakeholder placing, plus the stakeholder map when it is switched on. |
| `roleOpts(sel)` | 35 | The project role options, with "No role" first and the member's role selected. |
| `quadrant(m)` | 69 | A member's stakeholder quadrant from power and interest - 'hh', 'hl', 'lh' or 'll', where 3 and up counts as high. |
| `stanceChip(s)` | 71 | A stakeholder's stance as a labelled chip, in words. |
| `stakeMap(members)` | 73 | The stakeholder map - the four power / interest quadrants with the people in each, then the communications plan, most important first. |
| `cell(q)` | 79 | Rows: high power first. Columns: low interest, then high. |
| `openStake(id)` | 93 | Opens the stakeholder dialog for a member - power, interest, stance and how they are kept informed. |
| `lv(sel, v)` | 97 | Fills a 1-5 level drop-down, with "Not set" first and the current level selected. |
| `saveStake()` | 106 | Saves the member's stakeholder placing and redraws the project. |
| `openMember()` | 116 | Opens the add-a-member dialog on the Analyst kind; the Contractor kind shows only for viewers who can see suppliers. |
| `loadContractors()` | 130 | Loads the suppliers and their contacts once from the Tasks contractors endpoint - false when the viewer has no Contracts access or it is not ready. |
| `fillContacts()` | 139 | Fills the contact list for the chosen supplier, with "Whole supplier" first. |
| `fillMemberPick()` | 145 | Shows the picker for the chosen member kind - analysts or teams not already on the project, the person search, or the supplier and contact lists. |
| `searchPeople()` | 170 | Searches People (after a 180ms pause) for someone to add, leaving out those already on the project. |
| `saveMember()` | 187 | Adds the chosen analyst, team, person or contractor with the chosen role and redraws the project. |
| `itemCard(it)` | 205 | One scope item's card on the MoSCoW board - title, status and stage; draggable for those who may change it. |
| `renderScope()` | 214 | Draws the Scope tab - the MoSCoW columns (must, should, could, won't, unsorted) with counts, cards and an add box in each. |
| `openItem(it)` | 234 | Opens the scope item dialog filled from it, or empty for a new one; read-only for viewers who may not change the project. |
| `saveItem()` | 255 | Saves the scope item dialog through tools.php and redraws the project. |
| `renderRaci()` | 269 | Draws the RACI tab - a deliverable-by-member grid of letter buttons, flagging rows with no A or no R; an empty state until there are people and items. |
| `cycleRaci(btn)` | 301 | Moves a RACI cell to the next letter (blank, R, A, C, I) straight away, then takes the server's row - which may have moved an A from someone else. |
| `raidPlural(t)` | 323 | The plural name of a RAID type, such as "Dependencies". |
| `raidLate(r)` | 325 | A dependency or a decision still open after its date is LATE - it turns health amber. |
| `scoreClass(s)` | 329 | The colour class for a probability x impact score - high from 15, medium from 8. |
| `heatMap(risks)` | 332 | The risk heat map - a 5x5 grid of probability against impact, each cell showing how many open risks sit there, clickable to filter. |
| `raidRow(r)` | 351 | One RAID log row - type, title, owner, dates (late in red), actions done, response, linked ticket and article, escalation badge and score. |
| `renderRaid()` | 374 | Draws the RAID tab - the risk heat map, the type filters with open counts, show closed, export, and the filtered log (as a decision log under the Decisions filter). |
| `setRaidType(t)` | 408 | Switches the RAID dialog to a type, showing only the fields that type uses and wording the date to suit it. |
| `raidEscalationHtml(r)` | 417 | Escalation (3.3.0): the current one, or the box to escalate. A saved, open entry only. |
| `raidActionsHtml(r)` | 432 | Follow-up actions (3.3.0): project tasks, each opening on the Tasks board. |
| `fillRaidExtras(r)` | 446 | Fills the RAID dialog's escalation and follow-up action sections for a saved entry, hiding each when empty. |
| `raidReopen(id)` | 453 | Re-read the entry after an escalation or an action, keeping the dialog open. |
| `raidEscalate(btn)` | 459 | Escalates the open RAID entry with the typed note and refreshes the dialog. |
| `raidAction(add, btn)` | 469 | Adds a follow-up action task to the open RAID entry, or removes one, and refreshes the dialog. |
| `openRaid(r)` | 481 | Opens the RAID dialog filled from r, or empty for a new entry of the filtered type; read-only for viewers who may not change the project. |
| `scale(labels)` | 484 | The 1-5 options for a probability or impact drop-down, each with its label. |
| `raidBody()` | 520 | The RAID entry as typed in the dialog, ready to send to raid_save. |
| `saveRaid()` | 531 | Saves the RAID dialog through tools.php and redraws the project. |
| `raidKbHtml(r)` | 542 | A lesson -> a draft Knowledge article; an issue -> a new ticket (3.2.0). Only on a saved entry, only with the other module, only when the project can be changed - the server checks all three again. |
| `raidRaiseHtml(r)` | 553 | The "Raise a ticket" button for a saved issue with no ticket yet, when the viewer may change the project and can use Tickets. |
| `raidAct(action, btn)` | 559 | Saves the RAID dialog first, then turns the entry into a ticket or a draft Knowledge article and says which. |
| `searchTicket()` | 578 | Searches tickets (after a 180ms pause) to link to the RAID entry. |
| `exceptionsBanner(exc)` | 594 | The red banner naming each tolerance breach - shared with the Overview. |
| `renderGates()` | 601 | Draws the Gates tab - exceptions banner, business case, tolerances (cost only with the Budget tool), and each stage's gate with its decision, unapproved changes and checklist. |
| `gateChanges()` | 649 | Linked changes not yet approved (3.2.0, going live safely). A WARNING, never a block: the gate is the board's decision, and it may know the change is a formality. ctx.data.gate_changes is null when this analyst cannot open Changes - then the gate says nothing, rather than "none". |
| `gateChangesBox()` | 654 | The warning listing linked changes not yet approved, shown in the gate dialog; empty when there are none. |
| `openGate(stage)` | 667 | Opens the gate decision dialog for a stage, warning of unapproved changes and open checklist items unless the stage is already closed. |
| `saveGate()` | 681 | Records the gate decision and redraws; a go that closes the stage is cheered and names the next one started. |
| `wire()` | 697 | Wires every click, change, submit and drag on the tools tabs (people, stakeholders, scope board, RACI, RAID, gates) and their dialogs, once. |
| `render(c)` | 845 | Keeps the page's data and draws each tool tab the project has switched on. |

## API endpoints it calls

- [api/tasks/contractors.php](/code/files/api/tasks/contractors.php.md)
- [api/projects/export.php](/code/files/api/projects/export.php.md)

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

