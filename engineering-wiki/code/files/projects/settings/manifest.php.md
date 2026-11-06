# projects/settings/manifest.php

> PHP - 79 lines

Projects - settings manifest (3.2.0).

THE single declaration of this module's settings tabs, and therefore of its
capabilities. The tab bar, the tick-boxes on System -> Roles and their
descriptions are all derived from this file. See includes/capabilities.php.

⚠️ NO 'setting_keys' ON ANY TAB, deliberately. Every setting is saved through
api/projects/settings.php, which validates the value and checks the SAME
capability as the tab (the Domains pattern) - declaring the keys here would
let the generic settings writer store them unvalidated.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

