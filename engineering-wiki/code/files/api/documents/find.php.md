# api/documents/find.php

> API endpoint - 91 lines

API Endpoint: find an existing document to attach somewhere else.

GET ?q=warranty&parent_type=asset&parent_id=900

🔑 THIS IS WHAT THE JOIN TABLE WAS FOR. Until now every upload made a NEW
document, so one warranty covering eleven laptops meant eleven copies — the
exact thing document_links exists to avoid. This is the door to the other half.

⚠️ You may only attach a document you can ALREADY see, to a record you can
already see. Both halves matter: without the first, this endpoint would let
somebody attach a document they have no access to onto a record they do have
access to — and then read it, because visibility is inherited. That is
privilege escalation with two clicks, and it is why the search below runs
through the same visibility clause as everything else.

## The endpoint

| | |
|---|---|
| URL | `api/documents/find.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET q`, `GET parent_type`, `GET parent_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [documents](/code/tables/documents.md), read [document_links](/code/tables/document_links.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentEntityDef()](/code/functions/documentEntityDef.md), [documentVisibilityClause()](/code/functions/documentVisibilityClause.md), [documentVisibleParents()](/code/functions/documentVisibleParents.md)

