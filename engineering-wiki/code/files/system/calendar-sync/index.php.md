# system/calendar-sync/index.php

> PHP - 936 lines

System — Calendar sync (GH discussion #75).

Two things an administrator decides, on one screen because they are the same
subject:
  1. the CONNECTIONS that write scheduled work into analysts' own calendars -
     any number, Microsoft 365 and CalDAV side by side (#133), each analyst
     choosing one under Preferences;
  2. whether analysts may publish a subscribe (.ics) link at all.

The second stands alone: an install with no supported calendar provider still
publishes subscribe links, and still has to be able to govern them.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

