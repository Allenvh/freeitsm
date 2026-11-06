# api/system/debug-tools/D002_delete_ticket.php

> API endpoint - 448 lines

Debug Tool D002 — Delete Ticket (with full SQL trace)

For the support case where deleting a ticket fails with a foreign-key error
(typically "1451 Cannot delete or update a parent row" from
email_attachments → emails). Enter a ticket reference (ticket_number) and
this tool:
  1. Resolves the ticket and shows what it found.
  2. Audits every table the delete touches — existence, key columns, and the
     actual foreign-key constraints + their ON DELETE rule.
  3. Counts the child rows that will be removed.
  4. Performs the delete inside a transaction, echoing EVERY SQL statement,
     its parameters and the affected row count, then COMMITs.
  5. Verifies nothing is left behind and removes orphaned attachment files.

DESTRUCTIVE: on success the ticket and all its data are permanently deleted.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D002_delete_ticket.php` |
| Protected by | Signed-in analyst (session check) |
| Reads | `GET ref`, `POST ref` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 38 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [bool_str()](/code/functions/bool_str.md) | 47 | Formats a boolean as YES or NO for the report. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 55 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 141 |  | read [tickets](/code/tables/tickets.md) |  |
| 146 |  | read [tickets](/code/tables/tickets.md) |  |
| 238 |  | read [emails](/code/tables/emails.md) |  |
| 265 |  | read [email_attachments](/code/tables/email_attachments.md) |  |
| 290 |  | read [emails](/code/tables/emails.md) |  |
| 310 |  | read [email_attachments](/code/tables/email_attachments.md) |  |
| 336 |  | read [email_attachments](/code/tables/email_attachments.md) |  |
| 337 |  | read [emails](/code/tables/emails.md) |  |
| 338 |  | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 339 |  | read [ticket_audit](/code/tables/ticket_audit.md) |  |
| 340 |  | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 341 |  | read [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 342 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 343 |  | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) |  |
| 344 |  | read [ticket_assets](/code/tables/ticket_assets.md) |  |
| 345 |  | read [sla_notifications_sent](/code/tables/sla_notifications_sent.md) |  |
| 346 |  | read [tasks](/code/tables/tasks.md) |  |
| 366 |  | read [email_attachments](/code/tables/email_attachments.md) |  |
| 375 |  | **delete** [email_attachments](/code/tables/email_attachments.md) |  |
| 376 |  | **delete** [emails](/code/tables/emails.md) |  |
| 377 |  | **delete** [ticket_notes](/code/tables/ticket_notes.md) |  |
| 378 |  | **delete** [ticket_audit](/code/tables/ticket_audit.md) |  |
| 379 |  | **delete** [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 380 |  | **delete** [tickets](/code/tables/tickets.md) |  |
| 418 |  | read [tickets](/code/tables/tickets.md) |  |
| 420 |  | read [email_attachments](/code/tables/email_attachments.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [dbConnectionOptions()](/code/functions/dbConnectionOptions.md)

