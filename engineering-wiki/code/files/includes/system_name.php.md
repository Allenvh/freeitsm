# includes/system_name.php

> PHP - 88 lines

The system's name - System -> Branding -> "System name" (Tickets -> Settings -> General until 3.5.0).

It was saved and read by nothing: the setting's help promised the name in
the page titles, and 90 pages had "Service Desk" (or "FreeITSM") typed into
their <title> instead. They now ask here.

Never saved, empty, or unreadable (no database yet, mid-upgrade) means
"Service Desk" - exactly what every title said before - so nothing changes
for an install that has not set it.

Loaded from includes/i18n.php and includes/theme.php, one of which every
page with a title already loads.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [systemName()](/code/functions/systemName.md) | 19 | The system's name from System -> Branding, or "Service Desk" when it is not set. Cached per request. |
| [pageTitle()](/code/functions/pageTitle.md) | 61 | A browser tab title, the same shape on every page (GH #173): |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `systemName` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [lang/en/common.php](/code/files/lang/en/common.php.md)

**Included by:** [api/mcp/index.php](/code/files/api/mcp/index.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md), [includes/theme.php](/code/files/includes/theme.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [t()](/code/functions/t.md)

