# api/forms/draft.php

> API endpoint - 72 lines

A form draft, for the ANALYST side.

GET    ?form_id=N   — the current user's draft of that form, or null
  POST   {form_id, answers}   — save (overwriting their previous one)
  DELETE ?form_id=N   — throw it away

🔑 THE OWNER IS THE SESSION, NEVER THE REQUEST. A draft is personal, so who
it belongs to is taken from `$_SESSION` and a posted owner id is ignored
entirely — otherwise anyone could read or overwrite anybody else's.

🔴 Nothing here validates the answers against the form. Not being finished is
the whole point; validation happens on submit. What IS enforced, in the
service, is that the keys are field ids belonging to that form, so a draft
cannot be used as somewhere to park arbitrary data.

## The endpoint

| | |
|---|---|
| URL | `api/forms/draft.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `GET form_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [serviceErrorHttpStatus()](/code/functions/serviceErrorHttpStatus.md)

