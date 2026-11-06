# cmdb/settings/manifest.php

> PHP - 65 lines

CMDB — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. See includes/capabilities.php.

The **Classes** tab is the CMDB's schema: what kinds of configuration item exist and
what properties they carry. Changing it reshapes every object in the database, which is
a much heavier act than it looks — and it is reachable from the object page too, where
you can add an option to a property inline. Same act, different route, so it needs the
same permission (`api/cmdb/save_class_property.php`). This is the same call made for the
Kanban board's columns in Tasks.

The **AI** tab uses the shared AI settings panel, so its provider and API key are
authorised by namespace against the keys declared below (see includes/settings_keys.php
and api/system/ai/*).

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

