# includes/directory_sync.php

> PHP - 1047 lines

Directory sync — bring people in from LDAP / Active Directory.

Sign-in (includes/ldap.php) asks the directory about ONE person who is
standing in front of it. This enumerates EVERYBODY, so people exist before
anyone signs in — which is the whole point: the staff who hold equipment are
largely the staff who never log into anything.

─────────────────────────────────────────────────────────────────────────────
 THE THREE RULES THIS FILE EXISTS TO ENFORCE

 1. NOBODY IS EVER DELETED. Assets, tickets and handover documents all hang
    off users.id. A person who has left is deactivated, and keeps their history.

 2. A RUN THAT LOOKS WRONG CHANGES NOTHING. If the directory suddenly returns
    far fewer people than last time, that is far more likely to be a typo in a
    base DN, a service account losing read rights, or a slow replica than an
    actual redundancy round. The sanity brake stops the run instead of
    deactivating a company. See `syncBrakeTripped()`.

 3. MISSING ONCE IS NOISE. Nobody is deactivated on a single absence —
    sync_missed_count has to reach the provider's threshold first, and any
    sighting resets it.

 Everything else here is detail. Those three are why it is safe to run.
─────────────────────────────────────────────────────────────────────────────

Preview mode runs the identical code path and writes nothing to `users`, so
what you are shown is what would happen — not a separate estimate of it that
can drift from the real thing.

See the wiki: Directory sync — importing people from Active Directory.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dsyncAttrDefaults()](/code/functions/dsyncAttrDefaults.md) | 58 | Attribute defaults, per directory flavour. These are the names the fixture in docker/ldap-test uses, and the ones real installs overwhelmingly have. |
| [dsyncFlavour()](/code/functions/dsyncFlavour.md) | 103 | Which flavour is this provider? |
| [dsyncAttr()](/code/functions/dsyncAttr.md) | 117 | The configured attribute name for one field, falling back to the flavour default. |
| [dsyncDnList()](/code/functions/dsyncDnList.md) | 144 | Split a stored DN list into an array. One per line, blanks dropped. |
| [dsyncScopes()](/code/functions/dsyncScopes.md) | 170 | Where this provider imports from: which branches, minus which carve-outs. |
| [dsyncDnIsUnder()](/code/functions/dsyncDnIsUnder.md) | 204 | Is `$dn` inside the subtree rooted at `$ancestor` (or the ancestor itself)? |
| [dsyncMissingScopes()](/code/functions/dsyncMissingScopes.md) | 229 | Which of the stored branches no longer exist in the directory. |
| [dsyncMissingScopeMessage()](/code/functions/dsyncMissingScopeMessage.md) | 252 | Say which branches are gone, and lead with the one that changes who gets in. |
| [dsyncFetchPeople()](/code/functions/dsyncFetchPeople.md) | 278 | Reads every person under the provider's ticked directory branches with a paged LDAP search, skipping carved-out branches and counting each DN once. |
| [dsyncDnIsExcluded()](/code/functions/dsyncDnIsExcluded.md) | 349 | Does this DN sit inside any carved-out branch? |
| [dsyncValue()](/code/functions/dsyncValue.md) | 363 | One attribute off a raw entry, as a plain string ('' when absent). |
| [dsyncMapPerson()](/code/functions/dsyncMapPerson.md) | 380 | Turn a raw directory entry into the fields FreeITSM stores. Returns null when the entry has no usable identity at all. |
| [dsyncFindExisting()](/code/functions/dsyncFindExisting.md) | 424 | Find the FreeITSM record for a directory person, in order of reliability. |
| [syncBrakeTripped()](/code/functions/syncBrakeTripped.md) | 480 | Has the directory returned so much less than last time that we should refuse to act on it? |
| [dsyncLogEntry()](/code/functions/dsyncLogEntry.md) | 518 | Record one thing a run did to one person. |
| [directorySyncRun()](/code/functions/directorySyncRun.md) | 544 | Run a sync. |
| [dsyncFieldLabel()](/code/functions/dsyncFieldLabel.md) | 720 | A readable name for a person column, for the run log. |
| [dsyncNewPersonSummary()](/code/functions/dsyncNewPersonSummary.md) | 745 | What a person arriving for the first time will actually hold. |
| [dsyncApplyToExisting()](/code/functions/dsyncApplyToExisting.md) | 766 | Brings an existing person in line with the directory - the fields it owns, leaver or returner status, and the sync bookkeeping - and returns the changes in plain words. |
| [dsyncCreate()](/code/functions/dsyncCreate.md) | 833 | Create a person. Returns the new id, 0 on a preview, or null on failure. |
| [dsyncLinkIdentity()](/code/functions/dsyncLinkIdentity.md) | 868 | Store the directory's own id for this person, so a rename or OU move is survivable. |
| [dsyncHandleMissing()](/code/functions/dsyncHandleMissing.md) | 910 | People this provider manages who were NOT in this run's results. |
| [dsyncResolveManagers()](/code/functions/dsyncResolveManagers.md) | 975 | Second pass: turn each person's manager DN into a manager_id. |
| [dsyncFinishRun()](/code/functions/dsyncFinishRun.md) | 1022 | Close a run off. |
| [dsyncGetRun()](/code/functions/dsyncGetRun.md) | 1041 | Read a run back. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 430 | `dsyncFindExisting` | read [user_sso_identities](/code/tables/user_sso_identities.md), read [users](/code/tables/users.md) |  |
| 439 | `dsyncFindExisting` | read [users](/code/tables/users.md) |  |
| 452 | `dsyncFindExisting` | read [users](/code/tables/users.md) |  |
| 455 | `dsyncFindExisting` | read [users](/code/tables/users.md) |  |
| 522 | `dsyncLogEntry` | **insert** [directory_sync_entries](/code/tables/directory_sync_entries.md) (`run_id`, `action`, `user_id`, `directory_username`, `display_name`, `detail`, `created_datetime`) |  |
| 550 | `directorySyncRun` | **insert** [directory_sync_runs](/code/tables/directory_sync_runs.md) (`provider_id`, `mode`, `status`, `started_datetime`, `triggered_by_analyst_id`) |  |
| 701 | `directorySyncRun` | **update** [auth_providers](/code/tables/auth_providers.md) (`sync_last_run_datetime`, `sync_last_count`) |  |
| 819 | `dsyncApplyToExisting` | **update** [users](/code/tables/users.md) |  |
| 838 | `dsyncCreate` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `job_title`, `department`, `office`, `phone`, `mobile`, `employee_id`, `directory_username`, `auth_provider_id`, `tenant_id`, `is_managed`) |  |
| 878 | `dsyncLinkIdentity` | **insert** [user_sso_identities](/code/tables/user_sso_identities.md) (`user_id`, `provider_id`, `subject`, `email`, `source_ref`, `source_etag`, `linked_datetime`) |  |
| 915 | `dsyncHandleMissing` | read [users](/code/tables/users.md) |  |
| 938 | `dsyncHandleMissing` | **update** [users](/code/tables/users.md) (`is_active`, `deactivated_datetime`, `sync_missed_count`) |  |
| 947 | `dsyncHandleMissing` | **update** [users](/code/tables/users.md) (`sync_missed_count`) |  |
| 1001 | `dsyncResolveManagers` | **update** [users](/code/tables/users.md) (`manager_id`) |  |
| 1025 | `dsyncFinishRun` | **update** [directory_sync_runs](/code/tables/directory_sync_runs.md) (`status`, `finished_datetime`, `seen_count`, `created_count`, `updated_count`, `adopted_count`, `deactivated_count`, `conflict_count`, `error_count`, `message`) |  |
| 1043 | `dsyncGetRun` | read [directory_sync_runs](/code/tables/directory_sync_runs.md) |  |

## How it hangs together

**Includes:** [includes/ldap.php](/code/files/includes/ldap.php.md), [includes/users.php](/code/files/includes/users.php.md)

**Included by:** [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md), [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md), [api/system/run_directory_sync.php](/code/files/api/system/run_directory_sync.php.md), [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md), [includes/carddav_create.php](/code/files/includes/carddav_create.php.md), [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md)

**Calls:** [ldapBindService()](/code/functions/ldapBindService.md), [ldapOpen()](/code/functions/ldapOpen.md), [ldapStringifyId()](/code/functions/ldapStringifyId.md), [userManagerIsSafe()](/code/functions/userManagerIsSafe.md)

