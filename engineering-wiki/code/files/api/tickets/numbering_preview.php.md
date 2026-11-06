# api/tickets/numbering_preview.php

> API endpoint - 71 lines

API: what the next few ticket numbers would look like under a proposed configuration, and whether that configuration is usable.

🔑 Writes NOTHING. It never touches a counter, so an administrator can try
six formats and still have the next real ticket be the number they expect.
The preview in the settings screen calls this on every keystroke, and the
Save handler calls it once more before saving — so what is refused here is
refused everywhere.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/numbering_preview.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_NUMBERING (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

