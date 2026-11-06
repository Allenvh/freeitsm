# includes/ticket_recordings.php

> PHP - 90 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Self Service Developer Guide](https://github.com/edmozley/freeitsm/wiki/Self-Service-Developer-Guide)

Claiming pending screen recordings onto a ticket — in ONE place.

WHY THIS EXISTS
---------------
A recording must be uploaded BEFORE the thing it belongs to exists: the user
may record, watch it back, and re-record several times before they are happy,
and each attempt has to leave the browser. So uploads land as PENDING
(`ticket_id IS NULL`, file under `recordings/pending/{uuid}.{ext}`) and are
CLAIMED when the ticket or the reply is finally written —
see api/self-service/upload_recording.php.

That claim was written for raising a ticket. Letting people record while
REPLYING needed exactly the same dance — same ownership guard, same file move,
same fallback — so it moved here rather than being copied. The ownership guard
in particular is the kind of thing that must never exist in two versions.

🔑 THE GUARD: a row is only claimable while `ticket_id IS NULL` AND it was
uploaded by the SAME user. Both halves matter — the first stops a recording
being stolen off an existing ticket, the second stops one user claiming
another's pending upload. Recording ids are sequential and guessable, so this
is the only thing standing between them.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [claimPendingRecordings()](/code/functions/claimPendingRecordings.md) | 42 | Move pending recordings onto a ticket and (optionally) a specific message. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `claimPendingRecordings` | read [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 67 | `claimPendingRecordings` | **update** [ticket_recordings](/code/tables/ticket_recordings.md) (`ticket_id`, `email_id`, `file_path`) |  |

## How it hangs together

**Included by:** [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)

