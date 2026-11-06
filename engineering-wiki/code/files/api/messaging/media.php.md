# api/messaging/media.php

> API endpoint - 110 lines

Public, unauthenticated media server for ONE outbound attachment — the companion to webhook.php, but outbound: Twilio's WhatsApp API sends media by URL rather than accepting an upload, so it has to fetch the bytes from somewhere reachable on the internet. This is that somewhere.

GET /api/messaging/media.php?id=<email_attachments.id>&exp=<unix ts>&token=<hmac>

Deliberately narrow, not a general file host:
  - the token is an HMAC over (id, exp) using the app's own encryption key
    (messagingOutboundMediaToken()) — nobody can mint one without server-side
    code, and it is bound to exactly one attachment
  - exp is checked server-side; an expired link 403s even with a
    correctly-computed token for the (id, exp) pair it was issued for
  - only serves a row this app itself created as an OUTBOUND channel
    attachment (email_attachments joined to an Outbound, non-email emails
    row) — an inbound attachment, or an ordinary email attachment, is not
    reachable through this endpoint even with a forged token, because the
    query itself excludes them

## The endpoint

| | |
|---|---|
| URL | `api/messaging/media.php` |
| Protected by | Signed-in analyst (session check) (via includes/i18n.php) |
| Reads | `GET id`, `GET exp`, `GET token` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mediaFail()](/code/functions/mediaFail.md) | 33 | Sends the given HTTP status with a plain "Not found", then stops - every refusal looks the same, so nothing is revealed about why. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 |  | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [messagingOutboundMediaToken()](/code/functions/messagingOutboundMediaToken.md)

