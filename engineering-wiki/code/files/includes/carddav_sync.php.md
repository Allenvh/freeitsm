# includes/carddav_sync.php

> PHP - 538 lines

Importing people from a CardDAV address book.

⭐ THIS FILE IS DELIBERATELY SMALL, AND THAT IS THE POINT.

Directory sync's policy layer turned out to be genuinely transport-agnostic
rather than merely named that way: `dsyncFindExisting()`,
`dsyncApplyToExisting()`, `dsyncCreate()`, `dsyncLinkIdentity()` and
`dsyncHandleMissing()` contain **no LDAP references at all**. They consume a
normalised person array and write plain `users` columns.

So this file does exactly two things the LDAP path does differently:

  1. turns a vCard into that same person array, and
  2. decides which cards are in scope.

Everything that was expensive to get right — nobody is ever deleted, a run
that returns far fewer people changes nothing, missing once is noise, and
preview runs the identical code path — is reused untouched. A second
implementation of those rules would be a second set of ways to lose somebody's
history.

Asked for in https://github.com/edmozley/freeitsm/issues/133.
Wiki: CardDAV Import Internals — scope resolution, the run lifecycle and the
test rig. (The transport half is CardDAV Contact Sync — Developer Guide.)

## Functions

| Function | Line | What it does |
|---|--:|---|
| [cdsyncMapCard()](/code/functions/cdsyncMapCard.md) | 69 | Turn one vCard into the person array the policy layer expects. |
| [cdsyncPhones()](/code/functions/cdsyncPhones.md) | 149 | Split a card's telephone numbers into a phone and a mobile. |
| [cdsyncPhoneLines()](/code/functions/cdsyncPhoneLines.md) | 177 | WHICH lines the two numbers came from, as indexes into the unfolded card. |
| [cdsyncOrg()](/code/functions/cdsyncOrg.md) | 215 | Pull the department out of a structured `ORG` value. |
| [cdsyncLocality()](/code/functions/cdsyncLocality.md) | 239 | The locality (town) from `ADR`, which is the part that answers "which site". |
| [cdsyncResolveScope()](/code/functions/cdsyncResolveScope.md) | 268 | Which cards are in scope, resolved BEFORE anything is imported. |
| [cdsyncInScope()](/code/functions/cdsyncInScope.md) | 331 | Is this mapped person inside the resolved scope? |
| [cardDavSyncRun()](/code/functions/cardDavSyncRun.md) | 349 | Run an import for one CardDAV provider. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 355 | `cardDavSyncRun` | **insert** [directory_sync_runs](/code/tables/directory_sync_runs.md) (`provider_id`, `mode`, `status`, `started_datetime`, `triggered_by_analyst_id`) |  |
| 495 | `cardDavSyncRun` | **update** [auth_providers](/code/tables/auth_providers.md) (`sync_last_run_datetime`, `sync_last_count`) |  |

## How it hangs together

**Includes:** [includes/carddav.php](/code/files/includes/carddav.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md)

**Included by:** [api/system/run_directory_sync.php](/code/files/api/system/run_directory_sync.php.md), [includes/carddav_write.php](/code/files/includes/carddav_write.php.md)

**Calls:** [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md), [cardDavFetchCards()](/code/functions/cardDavFetchCards.md), [cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavScopeList()](/code/functions/cardDavScopeList.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md), [cardDavUnfold()](/code/functions/cardDavUnfold.md), [dsyncApplyToExisting()](/code/functions/dsyncApplyToExisting.md), [dsyncCreate()](/code/functions/dsyncCreate.md), [dsyncFindExisting()](/code/functions/dsyncFindExisting.md), [dsyncFinishRun()](/code/functions/dsyncFinishRun.md), [dsyncGetRun()](/code/functions/dsyncGetRun.md), [dsyncHandleMissing()](/code/functions/dsyncHandleMissing.md), [dsyncLogEntry()](/code/functions/dsyncLogEntry.md), [dsyncNewPersonSummary()](/code/functions/dsyncNewPersonSummary.md), [syncBrakeTripped()](/code/functions/syncBrakeTripped.md)

