# api/tickets/save_mailbox.php

> API endpoint - 346 lines

API Endpoint: Save (create/update) target mailbox

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_mailbox.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MAILBOXES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 120 |  | read [tenants](/code/tables/tenants.md) |  |
| 144 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 170 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 223 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 279 |  | **update** [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 296 |  | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`authenticated_as`, `authenticated_addresses`, `token_data`) |  |
| 325 |  | **insert** [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [encryptValue()](/code/functions/encryptValue.md), [oversizeNormalisePolicy()](/code/functions/oversizeNormalisePolicy.md), [oversizePolicyForStorage()](/code/functions/oversizePolicyForStorage.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

