# domains/settings/manifest.php

> PHP - 64 lines

Domains — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. The tab bar, the tick-boxes on System → Roles and their descriptions
are all derived from this file. See includes/capabilities.php.

⚠️ NO 'setting_keys' ON ANY TAB, deliberately. Every setting here is saved through
api/domains/settings.php, which validates the value and checks the SAME capability
as the tab — the LMS reminders pattern. Declaring the keys here as well would let
the generic api/settings/save_system_settings.php write them with no validation
(a days list of "banana", a recipient mode that does not exist).

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

