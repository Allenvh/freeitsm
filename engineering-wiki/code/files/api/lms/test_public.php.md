# api/lms/test_public.php

> API endpoint - 89 lines

LMS API: the CANDIDATE's side of a competency test. No session - the link's token (sent in the body, never in a URL this endpoint logs) is the credential, and it is matched by its SHA-256 hash.

POST {t, action: start}            starts the clock (once)
  POST {t, action: save, q, a}       records one answer while the clock runs
  POST {t, action: submit}           closes the sitting and scores it

The server owns the clock: nothing the browser says about time is believed.
Exempt from the CSRF token check (includes/csrf.php) for the same reason the
web chat is: it carries its own credential and no session.

## The endpoint

| | |
|---|---|
| URL | `api/lms/test_public.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ctpOut()](/code/functions/ctpOut.md) | 27 | Echoes {success: true} merged with `$a` as JSON, then stops. |
| [ctpFail()](/code/functions/ctpFail.md) | 35 | Sends an HTTP status and {success: false, error: `$code`} - a short code such as 'finished' that the candidate page acts on - then stops. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 |  | **update** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) (`started_datetime`) |  |
| 70 |  | **update** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) (`responses_json`) |  |
| 78 |  | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsCtDeadline()](/code/functions/lmsCtDeadline.md), [lmsCtFinalise()](/code/functions/lmsCtFinalise.md), [lmsCtFindSitting()](/code/functions/lmsCtFindSitting.md), [lmsCtState()](/code/functions/lmsCtState.md)

