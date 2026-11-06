# files/settings/manifest.php

> PHP - 46 lines

Files — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. The tab bar, the tick-boxes on System → Roles and their descriptions
are all derived from this file. See includes/capabilities.php.

⚠️ NO 'setting_keys' ON ANY TAB, deliberately. Every setting here is saved through
api/files/settings.php, which validates the value (a storage root must be a
writable directory, a size a number) and checks the SAME capability as the tab.

Personal preferences (desktop colour, the navbar) are not here: they live in the
desktop's own Personalise window and save to user_preferences.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

