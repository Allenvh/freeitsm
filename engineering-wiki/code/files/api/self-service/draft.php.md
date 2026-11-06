# api/self-service/draft.php

> API endpoint - 90 lines

A form draft, for a SELF-SERVICE customer.

GET    ?form_id=N        — this customer's draft of that form, or null
  POST   {form_id, answers} — save (overwriting their previous one)
  DELETE ?form_id=N        — throw it away

🔑 A DELIBERATELY SEPARATE ENDPOINT from the analyst one, not a shared file
with a branch. The two differ in the only thing that matters here — who the
owner is and which forms they may touch — and a single endpoint deciding that
from whichever session happens to be set is exactly how a customer ends up
reading an analyst's draft.

🔴 A customer may only draft a form they could actually fill in: in the
catalogue, active, and the current version. The gate is in the QUERY, so a
form that is not offered is indistinguishable from one that does not exist.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/draft.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET form_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 |  | read [forms](/code/tables/forms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [serviceErrorHttpStatus()](/code/functions/serviceErrorHttpStatus.md)

