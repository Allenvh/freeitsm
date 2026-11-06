# tickets/includes/header.php

> PHP - 112 lines

Tickets Module Header Component

Required: `$current_page` variable should be set before including.
Assumes the parent page has called I18n::initFromSession(); if not,
`t()` falls back to returning keys verbatim (no fatal, just untranslated).

## How it hangs together

**Included by:** [tickets/activity.php](/code/files/tickets/activity.php.md), [tickets/calendar.php](/code/files/tickets/calendar.php.md), [tickets/csat/index.php](/code/files/tickets/csat/index.php.md), [tickets/dashboard/index.php](/code/files/tickets/dashboard/index.php.md), [tickets/dashboard/library.php](/code/files/tickets/dashboard/library.php.md), [tickets/help-calendar-sync.php](/code/files/tickets/help-calendar-sync.php.md), [tickets/help-email-templates.php](/code/files/tickets/help-email-templates.php.md), [tickets/help-mailbox-auth.php](/code/files/tickets/help-mailbox-auth.php.md), [tickets/help-sla.php](/code/files/tickets/help-sla.php.md), [tickets/help.php](/code/files/tickets/help.php.md), [tickets/index.php](/code/files/tickets/index.php.md), [tickets/manager-access.php](/code/files/tickets/manager-access.php.md), [tickets/rota.php](/code/files/tickets/rota.php.md), [tickets/settings/index.php](/code/files/tickets/settings/index.php.md), [tickets/triage/index.php](/code/files/tickets/triage/index.php.md), [tickets/users.php](/code/files/tickets/users.php.md)

**Calls:** [renderHeaderRight()](/code/functions/renderHeaderRight.md), [renderWaffleMenuButton()](/code/functions/renderWaffleMenuButton.md), [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md), [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md), [t()](/code/functions/t.md)

