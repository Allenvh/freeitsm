# api/myaccount/save_signature.php

> API endpoint - 84 lines

API: create or update one of this analyst's own signatures. POST { id?, name, body, is_default }

⚠️ EVERY statement is scoped by analyst_id from the SESSION, and the incoming id is
only ever used together with it. Without that pairing, posting somebody else's
signature id would let an analyst rewrite what another person signs their emails
with — which is both a tampering bug and, since signatures carry phone numbers, a
way to read them back afterwards.

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/save_signature.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | **update** [analyst_signatures](/code/tables/analyst_signatures.md) (`name`, `body`, `updated_datetime`) |  |
| 53 |  | read [analyst_signatures](/code/tables/analyst_signatures.md) |  |
| 60 |  | **insert** [analyst_signatures](/code/tables/analyst_signatures.md) (`analyst_id`, `name`, `body`, `is_default`, `display_order`) |  |
| 68 |  | read [analyst_signatures](/code/tables/analyst_signatures.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/signatures.php](/code/files/includes/signatures.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [setDefaultSignature()](/code/functions/setDefaultSignature.md)

