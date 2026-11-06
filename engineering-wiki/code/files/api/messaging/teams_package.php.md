# api/messaging/teams_package.php

> API endpoint - 67 lines

API Endpoint: download the Microsoft Teams app package for one Teams channel.

GET /api/messaging/teams_package.php?id=<channel id>

Same access rule as the channel settings it is linked from: the Tickets
messaging capability, and a channel this analyst may administer.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/teams_package.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |
| Reads | `GET id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [teamsPackageFail()](/code/functions/teamsPackageFail.md) | 25 | Answers {success: false, error} as JSON and stops - used instead of the zip download when the package cannot be built. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/messaging/TeamsPackage.php](/code/files/includes/messaging/TeamsPackage.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [teamsPackageBuild()](/code/functions/teamsPackageBuild.md)

