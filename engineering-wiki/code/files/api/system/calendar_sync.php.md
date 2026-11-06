# api/system/calendar_sync.php

> API endpoint - 535 lines

API: the install's calendar-sync connections and feed policy (GH #75, #133).

GET                  -> every connection (WITHOUT secrets) + feed policy +
                          the Microsoft mailboxes whose credentials can be
                          borrowed + every analyst and the connection they use
  POST action=save     -> create (id=0) or update one connection.
                          provider=microsoft|caldav. Changing an existing
                          connection's provider, or its CalDAV server, answers
                          needs_confirm first - it takes back the events of the
                          analysts using that connection - resend with
                          confirm_switch=1.
       policy_only=1   -> the install-wide settings only (feed mode, accept
                          deletes, notification URL)
  POST action=test     -> one connection (id). Microsoft: mint a token and prove
                          the permission was granted. CalDAV: check the address
                          is a DAV server, and with probe_user/probe_pass list
                          that person's calendars (the sign-in is not stored).
  POST action=delete   -> one connection (id); needs_confirm first when anybody
                          uses it, then takes their events back
  POST action=set_address -> an analyst's Microsoft mailbox

🔑 ANY NUMBER OF CONNECTIONS. Most staff on Microsoft 365 and a few on
Nextcloud; an MSP with several tenants; one person on iCloud. Each analyst
picks one under Preferences. So every write here is scoped to the connection
it names, and a reset only ever touches the analysts who use it.

⚠️ SECRETS ARE NEVER RETURNED. The GET reports has_credentials as a boolean and
that is all, exactly as integrations.php does — a read that hands secrets back
to a browser needs the same care as one that writes them, and there is no
reason for the screen to know them.

## The endpoint

| | |
|---|---|
| URL | `api/system/calendar_sync.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `POST action`, `POST feed_mode`, `POST policy_only`, `POST notify_url`, `POST accept_deletes`, `POST id`, `POST provider`, `POST name`, `POST source`, `POST mailbox_id`, `POST server_url`, `POST caldav_auth`, `POST tenant_id`, `POST client_id`, `POST client_secret`, `POST confirm_switch`, `POST probe_user`, `POST probe_pass`, `POST probe`, `POST analyst_id`, `POST calendar_address`, `POST confirm` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [csUsage()](/code/functions/csUsage.md) | 53 | How many events and analysts depend on one connection. |
| [csTakeBack()](/code/functions/csTakeBack.md) | 73 | 🔴 TAKE BACK EVERYTHING ONE CONNECTION WROTE, then reset its analysts. |
| [csAdoptOrphans()](/code/functions/csAdoptOrphans.md) | 117 | 🔴 NAME THE CONNECTION ON ROWS THAT ONLY IMPLY IT. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `csUsage` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 57 | `csUsage` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |
| 78 | `csTakeBack` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 88 | `csTakeBack` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |
| 97 | `csTakeBack` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`mode`, `calendar_address`, `credentials`, `connection_id`, `delta_token`, `delta_synced_datetime`, `subscription_id`, `subscription_expires`, `subscription_secret`, `last_error`, `updated_datetime`) |  |
| 121 | `csAdoptOrphans` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`connection_id`) |  |
| 122 | `csAdoptOrphans` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`connection_id`) |  |
| 143 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 150 |  | read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 185 |  | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |
| 186 |  | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |
| 200 |  | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |
| 212 |  | read [analysts](/code/tables/analysts.md), read [calendar_enrolments](/code/tables/calendar_enrolments.md), read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 239 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 260 |  | read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 345 |  | **update** [calendar_connections](/code/tables/calendar_connections.md) (`name`, `provider`, `mailbox_id`, `credentials`, `is_active`, `last_error`, `last_error_datetime`, `token_data`, `updated_datetime`) |  |
| 353 |  | **insert** [calendar_connections](/code/tables/calendar_connections.md) (`name`, `provider`, `mailbox_id`, `credentials`, `created_by`) |  |
| 372 |  | **update** [calendar_connections](/code/tables/calendar_connections.md) (`last_error`, `last_error_datetime`) |  |
| 377 |  | **update** [calendar_connections](/code/tables/calendar_connections.md) (`last_error`, `last_error_datetime`) |  |
| 489 |  | **insert** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`analyst_id`, `calendar_address`, `mode`) |  |
| 512 |  | read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 526 |  | **delete** [calendar_connections](/code/tables/calendar_connections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md), [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md)

**Calls:** [calendarAcceptDeletes()](/code/functions/calendarAcceptDeletes.md), [calendarNotifyUrl()](/code/functions/calendarNotifyUrl.md), [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncConnectionIds()](/code/functions/calendarSyncConnectionIds.md), [calendarSyncConnectionProvider()](/code/functions/calendarSyncConnectionProvider.md), [calendarSyncDecodeCredentials()](/code/functions/calendarSyncDecodeCredentials.md), [calendarSyncEncodeCredentials()](/code/functions/calendarSyncEncodeCredentials.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [scheduleFeedMode()](/code/functions/scheduleFeedMode.md)

