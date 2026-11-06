# includes/calendar_sync/calendar_sync.php

> PHP - 434 lines

Calendar sync — loading connections, choosing a provider, and reading an analyst's enrolment (GH discussion #75).

The core of the feature lives above the provider contract in
CalendarSyncProvider.php: this file is the plumbing that gets a usable
provider object and answers "does this analyst want their scheduled work in
their calendar, and where".

Credential handling deliberately mirrors includes/integrations/integrations.php
— an encrypted JSON blob in a `credentials` column, decoded on load, and never
returned to a UI. One convention for secrets, not two.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [calendarModeIsValid()](/code/functions/calendarModeIsValid.md) | 34 | 🔑 WHY MODE IS ONE VALUE AND NOT TWO BOOLEANS. |
| [taskCalendarModeIsValid()](/code/functions/taskCalendarModeIsValid.md) | 62 | True when `$mode` is one of the task calendar modes - off, work, due or both. |
| [taskCalendarWantsWork()](/code/functions/taskCalendarWantsWork.md) | 72 | Does this analyst want a task's work window in their calendar? |
| [taskCalendarWantsDue()](/code/functions/taskCalendarWantsDue.md) | 82 | Does this analyst want a task's due date in their calendar? |
| [calendarSyncDecodeCredentials()](/code/functions/calendarSyncDecodeCredentials.md) | 92 | Decrypt + JSON-decode a stored credentials blob (never throws). |
| [calendarSyncEncodeCredentials()](/code/functions/calendarSyncEncodeCredentials.md) | 109 | Encrypt a credentials array for storage. |
| [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md) | 123 | Does this database have the calendar-sync tables yet? |
| [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md) | 146 | Load a connection with its secrets decrypted, or null. |
| [calendarSyncConnectionIds()](/code/functions/calendarSyncConnectionIds.md) | 182 | The ids of every active connection, oldest first. |
| [calendarSyncOnlyConnection()](/code/functions/calendarSyncOnlyConnection.md) | 202 | The connection when the install has exactly one, else null. |
| [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md) | 213 | The connection this analyst's calendar work goes through, or null. |
| [calendarSyncConnectionProvider()](/code/functions/calendarSyncConnectionProvider.md) | 227 | A connection's provider name, or '' when it does not exist. Cached per request, because it is asked for every enrolment that is read. |
| [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md) | 257 | Build the provider for a connection. |
| [calendarSyncAccount()](/code/functions/calendarSyncAccount.md) | 281 | An analyst's own calendar sign-in, decrypted: ['username' => …, 'password' => …]. Empty when they have not saved one. Never returned to a browser. |
| [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md) | 301 | One analyst's enrolment, or a synthetic "off" row when they have never chosen. |
| [scheduleFeedMode()](/code/functions/scheduleFeedMode.md) | 387 | The install-wide ceiling on subscribe links. |
| [scheduleFeedAllowed()](/code/functions/scheduleFeedAllowed.md) | 405 | May anyone subscribe on this install? |
| [scheduleFeedDetail()](/code/functions/scheduleFeedDetail.md) | 418 | How much detail THIS analyst's feed carries: 'full' or 'ref'. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 148 | `calendarSyncLoadConnection` | read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 156 | `calendarSyncLoadConnection` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 187 | `calendarSyncConnectionIds` | read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 233 | `calendarSyncConnectionProvider` | read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 310 | `calendarSyncEnrolment` | read [analysts](/code/tables/analysts.md) |  |
| 340 | `calendarSyncEnrolment` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |
| 393 | `scheduleFeedMode` | read [system_settings](/code/tables/system_settings.md) |  |
| 424 | `scheduleFeedDetail` | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md), [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md)

**Included by:** [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md), [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md), [api/tickets/get_schedule_feed_url.php](/code/files/api/tickets/get_schedule_feed_url.php.md), [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md), [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), [system/preferences/index.php](/code/files/system/preferences/index.php.md)

**Calls:** [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [decryptValue()](/code/functions/decryptValue.md), [encryptValue()](/code/functions/encryptValue.md)

