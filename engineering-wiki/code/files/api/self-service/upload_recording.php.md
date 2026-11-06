# api/self-service/upload_recording.php

> API endpoint - 110 lines

API: Self-Service Portal — Upload Screen Recording

Multipart POST: file (binary), duration_seconds (optional int), has_audio (0/1)
Returns: { success, recording_id }

Pending model: recordings are uploaded BEFORE the ticket exists. The row is
inserted with ticket_id = NULL and recorded_by_user_id = current session user;
the ticket creation endpoint claims them by setting ticket_id once known.

Files land in recordings/pending/{uuid}.{ext}; create_ticket.php moves them to
recordings/{ticket_id}/{uuid}.{ext} on claim.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/upload_recording.php` |
| Protected by | Signed-in portal user (session check) |
| Reads | `POST duration_seconds`, `POST has_audio` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 |  | **insert** [ticket_recordings](/code/tables/ticket_recordings.md) (`ticket_id`, `recorded_by_user_id`, `filename`, `original_filename`, `content_type`, `file_path`, `file_size`, `duration_seconds`, `has_audio`, `created_at`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

