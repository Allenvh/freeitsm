# includes/calendar_sync/MicrosoftCalendarProvider.php

> PHP - 496 lines

MicrosoftCalendarProvider — scheduled work into an analyst's Exchange calendar via Microsoft Graph (GH discussion #75).

🔑 APP-ONLY, NOT PER-ANALYST OAUTH. The app authenticates as itself with the
client-credentials flow and writes to /users/<address>/events. There is no
consent flow, no refresh token and no per-analyst grant — an administrator
adds Calendars.ReadWrite (APPLICATION, not delegated) to the app registration
once, consents once, and every analyst can be enrolled. This is the single
fact that makes the feature practical on an install with fifty analysts.

⚠️ THAT PERMISSION REACHES EVERY MAILBOX IN THE TENANT. Microsoft's mitigation
is an Application Access Policy scoping the app to a mail-enabled security
group. The setup screen must say so plainly — an administrator discovering the
scope for themselves, after the fact, is how trust in an integration dies.

Verifying a mailbox uses /users/<address>/calendar rather than /users/<address>
ON PURPOSE: the latter needs User.Read.All, a second application permission,
for information we do not otherwise want. Reading the calendar we are about to
write to is covered by the permission we already require.

## Classes

- [MicrosoftCalendarProvider](/code/classes/MicrosoftCalendarProvider.md) (class, line 28)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 105 | `MicrosoftCalendarProvider::token` | **update** [calendar_connections](/code/tables/calendar_connections.md) (`token_data`) |  |

## How it hangs together

**Includes:** [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md), [includes/ssl.php](/code/files/includes/ssl.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Included by:** [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md)

**Calls:** [encryptValue()](/code/functions/encryptValue.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

