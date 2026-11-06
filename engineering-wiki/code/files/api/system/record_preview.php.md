# api/system/record_preview.php

> API endpoint - 54 lines

API: an at-a-glance preview of a linked record (discussion #91). GET ?type=ticket&id=42

⚠️ NOT gated on one module. Seven kinds of record are reachable from here and
each needs a different one, so the gate lives in `recordPreview()` — which
checks the module AND the record. Naming a single module here would either
refuse somebody legitimately previewing a task from a ticket, or wave through
a type that module has nothing to do with.

🔴 An unreachable record and a non-existent one return the same thing. Telling
them apart would let somebody confirm a record exists by watching which error
they got, which is the fact the check exists to withhold.

## The endpoint

| | |
|---|---|
| URL | `api/system/record_preview.php` |
| Protected by | Signed-in analyst (session check) (via includes/i18n.php) |
| Reads | `GET type`, `GET id` |
| Called from | [assets/js/record-preview.js](/code/files/assets/js/record-preview.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/record_preview.php](/code/files/includes/record_preview.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [recordPreview()](/code/functions/recordPreview.md), [t()](/code/functions/t.md)

