# api/myaccount/get_signatures.php

> API endpoint - 55 lines

API: this analyst's email signatures, their profile details and the merge codes.

⚠️ SCOPED TO THE SESSION, NEVER TO A PARAMETER. There is no analyst_id input and
there must never be one: a signature is one person's own text, and an endpoint that
accepted an id would hand anybody else's to whoever asked. That is also why there is
no capability check — this is not administration, it is your own account, and every
analyst is entitled to exactly their own row.

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/get_signatures.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/signatures.php](/code/files/includes/signatures.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [renderSignature()](/code/functions/renderSignature.md), [signatureMergeCodes()](/code/functions/signatureMergeCodes.md), [signaturesForAnalyst()](/code/functions/signaturesForAnalyst.md), [ssoAnalystProfileLocked()](/code/functions/ssoAnalystProfileLocked.md)

