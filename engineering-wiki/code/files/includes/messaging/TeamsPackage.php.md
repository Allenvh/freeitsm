# includes/messaging/TeamsPackage.php

> PHP - 113 lines

Builds the Microsoft Teams app package (a zip) for one Teams channel, ready to upload in Teams Admin Center → Manage apps. The package is generated from the channel's own App ID, so the admin never has to hand-edit a manifest.

Zip layout, at the root (Teams rejects a package with a folder in front):
  manifest.json, color.png (192×192), outline.png (32×32, transparent)

## Functions

| Function | Line | What it does |
|---|--:|---|
| [teamsPackageBuild()](/code/functions/teamsPackageBuild.md) | 20 | Write the package for `$channel` to `$outPath`. Returns the zip's file name. Throws with a message the admin can act on when the channel is not ready. |
| [teamsZipStore()](/code/functions/teamsZipStore.md) | 90 | A plain zip archive with every entry stored (no compression). Written by hand so the package does not depend on the PHP zip extension, which this image lacks. Three small files gain nothing from compression, and stored entries are the simplest form every zip reader, Teams included, accepts. |

## How it hangs together

**Includes:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Included by:** [api/messaging/teams_package.php](/code/files/api/messaging/teams_package.php.md)

**Calls:** [messagingPublicBaseUrl()](/code/functions/messagingPublicBaseUrl.md)

