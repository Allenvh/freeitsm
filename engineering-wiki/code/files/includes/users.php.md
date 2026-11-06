# includes/users.php

> PHP - 423 lines

People — the fields that describe a human rather than a login.

`users` began as an authentication record: an address, a password hash, an MFA
secret. Everything here is the other half — job title, department, office,
manager — which is what an asset register, an approval chain and a service desk
actually need, and which nothing could store until slice 1 of directory sync.

Kept in one file because THREE things will write these columns and they must
agree: the users screen, api/tickets/save_user.php, and (slice 2) the directory
sync. A list of field names duplicated across three writers is a list that will
disagree with itself within a month.

See the wiki: Directory sync — importing people from Active Directory.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [userDirectoryOwnedFields()](/code/functions/userDirectoryOwnedFields.md) | 108 | Which person fields are READ-ONLY on a managed record, given the protocol of the provider that manages it and whether that provider writes changes back. |
| [userSignsInElsewhere()](/code/functions/userSignsInElsewhere.md) | 175 | Does this person sign in through their linked provider rather than with a FreeITSM password? |
| [userSourceFilter()](/code/functions/userSourceFilter.md) | 202 | The SQL for the Source filter. Expects auth_providers joined as `ap`. |
| [userSourcesInScope()](/code/functions/userSourcesInScope.md) | 220 | Every source the people in scope are linked to, with how many - what the Source filter offers. Counted inside the caller's company scope, so the list never reveals how many people another company has in a shared directory. |
| [portalProfileSettingRaw()](/code/functions/portalProfileSettingRaw.md) | 263 | A raw system_settings value, or null when it has never been saved. |
| [portalProfileEditableFields()](/code/functions/portalProfileEditableFields.md) | 282 | The fields a portal user may change about themselves, in the constant's order. |
| [portalProfileAddressBookWrites()](/code/functions/portalProfileAddressBookWrites.md) | 293 | May an address-book contact change those fields, their change being sent back? |
| [portalProfileAccess()](/code/functions/portalProfileAccess.md) | 311 | How the portal treats one person's contact details. One answer, used by both the read and the save, so the form and the endpoint cannot disagree. |
| [userPersonFieldValue()](/code/functions/userPersonFieldValue.md) | 379 | Normalise one incoming person field. |
| [userManagerIsSafe()](/code/functions/userManagerIsSafe.md) | 403 | Would setting `$managerId` on `$userId` create a loop? |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 180 | `userSignsInElsewhere` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 224 | `userSourcesInScope` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 266 | `portalProfileSettingRaw` | read [system_settings](/code/tables/system_settings.md) |  |
| 318 | `portalProfileAccess` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 410 | `userManagerIsSafe` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/sso_identity.php](/code/files/includes/sso_identity.php.md)

**Included by:** [api/self-service/get_profile.php](/code/files/api/self-service/get_profile.php.md), [api/self-service/login.php](/code/files/api/self-service/login.php.md), [api/self-service/register.php](/code/files/api/self-service/register.php.md), [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md), [api/self-service/update_profile.php](/code/files/api/self-service/update_profile.php.md), [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md), [api/system/portal_profile.php](/code/files/api/system/portal_profile.php.md), [api/tickets/get_person.php](/code/files/api/tickets/get_person.php.md), [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md), [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), [includes/person_editor.php](/code/files/includes/person_editor.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [self-service/verify-email.php](/code/files/self-service/verify-email.php.md), [tickets/users.php](/code/files/tickets/users.php.md)

**Calls:** [ssoJitColumnsReady()](/code/functions/ssoJitColumnsReady.md), [ssoProfileSyncLocks()](/code/functions/ssoProfileSyncLocks.md)

