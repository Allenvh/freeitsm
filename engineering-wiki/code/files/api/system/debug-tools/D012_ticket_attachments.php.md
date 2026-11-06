# api/system/debug-tools/D012_ticket_attachments.php

> API endpoint - 1176 lines

Debug Tool D012 — Ticket attachments, end to end.

Answers one question for one ticket: "for every file hanging off this ticket,
would the endpoint that serves it actually hand it over — and if not, why?"

⚠️ THE THING THAT MAKES THIS HARD IS THAT "TICKET ATTACHMENT" IS FOUR DIFFERENT
SUBSYSTEMS. They share nothing: not a table, not a folder, not a reader, not
even an id sequence. A report that says "attachments are broken" almost never
means all four, and working out WHICH ONE is most of the diagnosis:

  Email attachment   email_attachments   tickets/attachments/     get_attachment.php
  Document on ticket documents           uploads/documents/       documents/download.php
  Document on a note documents           uploads/documents/       documents/download.php
  Screen recording   ticket_recordings   recordings/<ticket>/     get_recording.php

They also arrived years apart, so an id of 1 in one of them is unremarkable
while the same id in another would be astonishing. The report names the
subsystem on every single line for exactly that reason.

🔑 IT RESOLVES PATHS THROUGH THE APPLICATION'S OWN FUNCTIONS, never a copy of
the rule. `documentStoragePath()` is required from includes/documents.php and
called; the other three roots are rebuilt exactly as their endpoints build
them, containment check included. A tool that reimplements the resolver
verifies its own reimplementation and passes while the product fails.

🔑 PRESENT IS NOT THE SAME AS READABLE, AND READABLE IS NOT THE SAME AS INTACT.
So each file is taken through the whole journey: resolve, contain, exist, open,
read every byte (a stream hash reads the full extent, which is what catches a
truncated or locked file), compare the length against the length recorded at
upload, compare the SHA-256 against documents.content_hash where one was
stored, sniff the leading bytes, and finally copy it to the system temp folder
and delete it again — the closest thing to a real download that can be done
without a browser.

⚠️ A CHECK THAT CANNOT RUN SAYS SO, and never passes quietly. No fileinfo
extension means "could not sniff", not "type is fine". No stored hash means
"not recorded", not "matches". This is D009's rule and it applies here for the
same reason.

⚠️ ON THE .htaccess QUESTION: it IS a thing — every one of these folders sits
under the web root and is meant to be unreachable except through its endpoint,
and the deny comes from a per-folder .htaccess plus a web.config for IIS. This
tool reports whether those guard files are PRESENT and whether they actually
contain a deny. It deliberately does NOT claim the folder is protected: nginx
never reads .htaccess and neither does Apache with AllowOverride None, so
presence proves nothing on its own. D009 is the tool that settles that, by
fetching a probe over HTTP, and this report points at it rather than guessing.

Read-only, with one exception that cleans up after itself: the temp copy. It is
written outside the application, verified, deleted, and the deletion is
confirmed and reported.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D012_ticket_attachments.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET ref`, `POST ref` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 79 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [bool_str()](/code/functions/bool_str.md) | 88 | Formats a boolean as YES or NO for the report. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 96 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |
| [d012_bytes()](/code/functions/d012_bytes.md) | 110 | Human-readable byte count. Sizes here are read by people, not parsed. |
| [d012_trim()](/code/functions/d012_trim.md) | 125 | Mask nothing, but keep a runaway value from swamping the report. |
| [d012_whyMissing()](/code/functions/d012_whyMissing.md) | 157 | WHY `is_file()` SAID NO. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 334 |  | read [tickets](/code/tables/tickets.md) |  |
| 338 |  | read [tickets](/code/tables/tickets.md) |  |
| 473 |  | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 507 |  | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md), read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 576 |  | read [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 859 |  | read [analysts](/code/tables/analysts.md) |  |
| 951 |  | read [documents](/code/tables/documents.md), read [document_links](/code/tables/document_links.md), read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 981 |  | read [tickets](/code/tables/tickets.md) |  |
| 993 |  | read [documents](/code/tables/documents.md), read [document_links](/code/tables/document_links.md), read [ticket_notes](/code/tables/ticket_notes.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [analystIsAdmin()](/code/functions/analystIsAdmin.md), [attachmentServeRules()](/code/functions/attachmentServeRules.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [dbConnectionOptions()](/code/functions/dbConnectionOptions.md), [documentCanView()](/code/functions/documentCanView.md), [documentStoragePath()](/code/functions/documentStoragePath.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [uploadDetectMime()](/code/functions/uploadDetectMime.md)

