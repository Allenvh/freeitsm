# change-management/settings/manifest.php

> PHP - 69 lines

Change Management — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. See includes/capabilities.php.

Nothing here reaches credentials, money or email, so no tab is marked sensitive — but
the split still earns its keep: the **Fields** tab decides which fields appear on every
change record and whether they are required, which is a far bigger lever over how the
team works than adding a value to the impacts list.

Note what is NOT here. **CAB membership** is managed from the change record itself, not
from settings, and is reachable through the REST API — so it stays on plain module
access. Approving a change is the job, not the configuration.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

