# api/forms/image.php

> API endpoint - 121 lines

Serve an image block's picture. GET ?field=<form_fields.id>

🔴 IT TAKES A FIELD ID, NOT A PATH. The path is read out of that field's own
config, server-side. That removes directory traversal as a CATEGORY rather
than filtering for it: there is no caller-supplied string anywhere near the
filesystem, so `?path=1/../../config.php` has nothing to attach itself to.
The shape of the stored path is re-checked anyway, because a value that came
from our own upload endpoint is still a value from a database row.

🔴 WHO MAY SEE IT. Two different answers, and conflating them is how a form
that was never published leaks:

  an analyst with Forms access   — any form's image
  a signed-in portal user        — ONLY a form that is portal-visible, active
                                   and the current version

Anything else is a 404, never a 403: a refusal that distinguishes "not yours"
from "does not exist" tells an unauthenticated person which ids are real.

## The endpoint

| | |
|---|---|
| URL | `api/forms/image.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET field` |
| Called from | [assets/js/form-logic.js](/code/files/assets/js/form-logic.js.md) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [imageNotFound()](/code/functions/imageNotFound.md) | 30 | Everything that is not a served image ends here, identically. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | read [form_fields](/code/tables/form_fields.md), read [forms](/code/tables/forms.md) |  |
| 63 |  | read [forms](/code/tables/forms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

