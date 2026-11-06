# api/myaccount/delete_signature.php

> API endpoint - 41 lines

API: delete one of this analyst's own signatures. POST { id }

⚠️ The DELETE is scoped by analyst_id from the session as well as by id, so the
worst an analyst can do with somebody else's id is delete nothing.

Deleting the default does NOT leave the analyst with no default:
`defaultSignatureForAnalyst()` falls back to the first remaining one. Promoting a
replacement here as well would be a second rule saying the same thing, and the two
would eventually disagree.

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/delete_signature.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | **delete** [analyst_signatures](/code/tables/analyst_signatures.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

