# api/system/debug-tools/D009_guarded_paths.php

> API endpoint - 362 lines

Debug Tool D009 — are the guarded paths actually guarded?

Answers one question end to end: "can somebody with a web browser fetch a file
that only the application is supposed to hand out?"

FreeITSM keeps uploaded files — ticket attachments, change attachments, war room
attachments, RFP uploads, call recordings, attached documents — under the web root, and
relies on the WEB SERVER refusing to serve them. Every one of those files is
meant to be reached through a PHP endpoint that checks who you are first.

On Apache that refusal comes from a .htaccess in each folder. ⚠️ nginx never
reads .htaccess, and neither does Apache with `AllowOverride None`. So the
protection is not a property of FreeITSM — it is a property of how somebody
configured their web server, which FreeITSM cannot see and has no way to assume.

THIS IS THE FAILURE MODE THAT ANNOUNCES ITSELF LEAST. A missing rewrite rule
gives you a 404 within seconds of installing. A missing deny rule gives you a
service desk that works perfectly and publishes every attachment. Nothing looks
wrong, no error is logged, and the first sign of trouble is somebody else
finding a document they should never have seen. That is the whole reason this
tool exists (issue #68).

HOW IT TESTS, and why it has to be done this way: it writes a tiny harmless
probe file into each guarded folder and then asks its OWN web server for it
over HTTP, exactly as a stranger would. Reading .htaccess files off disk would
prove nothing — the question is not whether the file exists, it is whether the
server is paying any attention to it.

⚠️ A REQUEST THAT FAILS IS NOT A PASS. A connection refused, a TLS error or a
timeout means "could not test", never "guarded" — a check that cannot run must
say so rather than quietly report all-clear. The tool proves it can see its own
site (a positive control) before it believes a single 403 or 404.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D009_guarded_paths.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 70 | Adds one titled section ("=== TITLE ===" and its lines) to the report. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 79 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |
| [d009Fetch()](/code/functions/d009Fetch.md) | 100 | Fetch a URL from this server. Returns [httpCode, transportError]. A transport error yields code 0, and every caller must treat that as "unknown", never as "blocked". |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requestScheme()](/code/functions/requestScheme.md)

