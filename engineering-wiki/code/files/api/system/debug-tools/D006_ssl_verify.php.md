# api/system/debug-tools/D006_ssl_verify.php

> API endpoint - 329 lines

Debug Tool D006 — SSL / HTTPS certificate verification

Answers one question end to end: "can this server actually make a
certificate-verified HTTPS request, and if not, why?"

FreeITSM makes a lot of outbound HTTPS calls (mailboxes, AI providers, SSO,
Intune/vCenter, webhooks, email). Since #919 verification is ON by default
(SSL_VERIFY_PEER) and every handle goes through `sslApplyCurl()`, which attaches
a CA bundle. This tool shows the whole chain — the global switch, the php.ini
CA config, the shipped includes/cacert.pem, which bundle actually wins, and a
batch of LIVE verified requests to the real services the app talks to — so a
"certificate problem" is diagnosed in one place instead of guessed at.

⚠️ It also asks the question a live request cannot: config.php is the
OPERATOR'S file and never upgrades, so a hand-assembled or pre-release copy can
lack the SSL block the app expects. Every certificate test here can pass while a
mailbox sign-in still dies on "Call to undefined function `sslApplyCurl()`".
This tool used to print that helper's `function_exists()` as if it meant something,
having itself loaded functions.php first - so it could only ever say YES.

READ-ONLY. It makes unauthenticated HEAD requests to public endpoints and
writes nothing. Prints no secrets (no API keys, no request bodies).

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D006_ssl_verify.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 59 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [yn()](/code/functions/yn.md) | 68 | Formats a boolean as YES or NO for the report. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 75 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md), [sslResolveCaBundle()](/code/functions/sslResolveCaBundle.md)

