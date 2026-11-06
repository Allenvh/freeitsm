# api/documents/links.php

> API endpoint - 96 lines

API Endpoint: what is this document attached to?

GET ?id=7

Powers the ⓘ button. Answers only for a document the caller may see, and lists
only the parents they may see — so it can never be used to enumerate records
behind a document id, which would turn an information button into a probe.

⚠️ It also reports how many parents were hidden as a COUNT ONLY. Saying "and 2
others" is a judgement call: it tells somebody the document is more widely
attached than they can see, which matters when they are deciding whether to
attach it somewhere new — and it names nothing.

## The endpoint

| | |
|---|---|
| URL | `api/documents/links.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [documents](/code/tables/documents.md), read [analysts](/code/tables/analysts.md), read [document_text](/code/tables/document_text.md) |  |
| 68 |  | read [document_links](/code/tables/document_links.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanView()](/code/functions/documentCanView.md), [documentVisibleParents()](/code/functions/documentVisibleParents.md)

