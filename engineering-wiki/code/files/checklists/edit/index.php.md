# checklists/edit/index.php

> PHP - 465 lines

Checklists — the SOP template editor, on its own screen.

WHY THIS IS NOT A MODAL (it was, as contributed in PR #141):

A template is a parent with an UNBOUNDED list of children, each child having
five attributes of its own. That is the same shape as a form and its fields,
and forms/edit/ is a full screen for exactly this reason. A 680px modal at
88vh with its own inner scrollbar is a bad place to drag a fifteen-step
procedure into order — the drop target is often off-screen and a scrolling
modal body cannot auto-scroll during a drag without a lot of bespoke work.

A screen also gets a URL: ?id=N can be linked to from a ticket, the browser's
back button behaves, and the unsaved-changes guard has something to guard.

House rule the shape follows: a modal is for a handful of fields; anything
with a repeating child list gets a screen (forms, process mapper, knowledge).

The drag-and-drop is the forms module's implementation, deliberately — same
handle-gated dragstart, same midpoint drop calculation, same class names for
the drop indicator. Copied rather than re-invented so the two behave alike.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [checklist_roles](/code/tables/checklist_roles.md) |  |
| 48 |  | read [checklist_categories](/code/tables/checklist_categories.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [checklists/includes/header.php](/code/files/checklists/includes/header.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

