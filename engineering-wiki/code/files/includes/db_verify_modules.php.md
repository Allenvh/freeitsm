# includes/db_verify_modules.php

> PHP - 190 lines

Database Verify — table → module grouping.

Powers the module filter and card colours on the DB Verify page. The mapping
is DERIVED from table-name prefixes (ticket_*, knowledge_*, sla_* …), so a
newly-added table usually lands in the right group with no edit here. Only
genuinely irregular names need a line in `$DB_VERIFY_TABLE_OVERRIDES`. Anything
unmatched falls to 'other' — always visible, never hidden — which is the
signal that a new prefix rule (or override) is worth adding.

Module keys and colours reuse includes/module-colors.php so the cards match
the rest of the app. Groups with no colour of their own (rfp, other) get a
neutral grey. This file holds NO schema truth — it only labels tables — so it
can never drift the actual verification; a wrong guess just mis-files a card.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dbVerifyModuleLabels()](/code/functions/dbVerifyModuleLabels.md) | 124 | Human labels + a neutral fallback colour for groups outside module-colors. |
| [dbVerifyModuleForTable()](/code/functions/dbVerifyModuleForTable.md) | 158 | Which module a table belongs to. Never throws; unknown tables → 'other'. |
| [dbVerifyModuleMeta()](/code/functions/dbVerifyModuleMeta.md) | 175 | Metadata for every known module group: key => {label, color, colorHover}. The front end filters this down to the groups that actually have a card. |

## How it hangs together

**Includes:** [includes/module-colors.php](/code/files/includes/module-colors.php.md)

**Included by:** [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

**Calls:** [getModuleColors()](/code/functions/getModuleColors.md)

