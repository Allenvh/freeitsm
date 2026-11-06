# includes/signatures.php

> PHP - 145 lines

Analyst email signatures (discussion #80, request 3).

WHY THE MERGE CODES ARE NOT THE ONES YOU ALREADY KNOW
----------------------------------------------------
⚠️ `[analyst_name]` is NOT the person typing. `buildTicketMergeData()` resolves it
from the ticket:

     COALESCE(o.full_name, a.full_name)     -- o = owner, a = assigned analyst

which is the right answer for an automatic email about a ticket, and the wrong
answer for a signature. Sam answering a ticket owned by Jo would sign it *Jo* —
every time, to the customer, without either of them noticing.

So signatures get their own vocabulary, tied to whoever is signed in: `[my_name]`,
`[my_email]`, `[my_job_title]` and so on. The `my_` prefix is not decoration; it is
the whole distinction, and it reads correctly next to the My Account screen these
are edited on. The ticket codes keep meaning exactly what they always meant.

ESCAPING
--------
Same rule as canned responses: the BODY is trusted (the analyst wrote it, and it is
HTML on purpose so a signature can have a link or bold text), the VALUES substituted
into it are not. An analyst's own job title is hardly hostile, but it is typed into
a box and ends up in somebody else's inbox, so it is escaped like anything else.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [signatureMergeData()](/code/functions/signatureMergeData.md) | 35 | The values a signature can merge, for the analyst who is signing. |
| [signatureMergeCodes()](/code/functions/signatureMergeCodes.md) | 57 | The merge codes offered in the editor, in the order they are listed. |
| [renderSignature()](/code/functions/renderSignature.md) | 80 | Resolve a signature body for one analyst. |
| [signaturesForAnalyst()](/code/functions/signaturesForAnalyst.md) | 92 | Every signature belonging to one analyst, default first. |
| [defaultSignatureForAnalyst()](/code/functions/defaultSignatureForAnalyst.md) | 115 | The one that gets inserted without being asked for, or null. |
| [setDefaultSignature()](/code/functions/setDefaultSignature.md) | 138 | Make exactly one signature the default for an analyst. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `signatureMergeData` | read [analysts](/code/tables/analysts.md) |  |
| 95 | `signaturesForAnalyst` | read [analyst_signatures](/code/tables/analyst_signatures.md) |  |
| 140 | `setDefaultSignature` | **update** [analyst_signatures](/code/tables/analyst_signatures.md) (`is_default`) |  |
| 142 | `setDefaultSignature` | **update** [analyst_signatures](/code/tables/analyst_signatures.md) (`is_default`) |  |

## How it hangs together

**Included by:** [api/myaccount/get_signatures.php](/code/files/api/myaccount/get_signatures.php.md), [api/myaccount/save_signature.php](/code/files/api/myaccount/save_signature.php.md)

