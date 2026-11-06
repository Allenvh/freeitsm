# tickets/index.php

> PHP - 1135 lines

Inbox - Main interface for Service Desk Ticketing System Folder-style layout with departments, statuses, and reading pane

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_display.php](/code/files/includes/ticket_display.php.md), [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/inbox_display.php](/code/files/includes/inbox_display.php.md), [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/record-preview.js](/code/files/assets/js/record-preview.js.md), [assets/js/tinymce/tinymce.min.js](/code/files/assets/js/tinymce/tinymce.min.js.md), [assets/js/safe-html.js](/code/files/assets/js/safe-html.js.md), [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/schedule.js](/code/files/assets/js/schedule.js.md), [checklists/search_scoring.js](/code/files/checklists/search_scoring.js.md), [checklists/ticket_view.js](/code/files/checklists/ticket_view.js.md), [assets/js/inbox.js](/code/files/assets/js/inbox.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [inboxDisplayForAnalyst()](/code/functions/inboxDisplayForAnalyst.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [snoozeWakeHour()](/code/functions/snoozeWakeHour.md), [t()](/code/functions/t.md), [ticketAiSettings()](/code/functions/ticketAiSettings.md), [ticketDisplaySettings()](/code/functions/ticketDisplaySettings.md)

