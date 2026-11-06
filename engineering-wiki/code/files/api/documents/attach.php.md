# api/documents/attach.php

> API endpoint - 77 lines

API Endpoint: attach an EXISTING document to a record.

POST { document_id, parent_type, parent_id }

The counterpart to save.php, which creates a new document. This one adds a
link to one that already exists — one warranty on eleven laptops, stored once.

⚠️ BOTH ENDS ARE CHECKED, and the first is the one that is easy to miss. You
must be able to see the DOCUMENT already, not merely the record you are
attaching it to. Without that check, anyone could attach a document they have
no access to onto a record they do — and then read it, because visibility is
inherited from the parent. Two clicks, and the permission model is inverted.

## The endpoint

| | |
|---|---|
| URL | `api/documents/attach.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanView()](/code/functions/documentCanView.md), [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentEntityDef()](/code/functions/documentEntityDef.md), [documentInsertLink()](/code/functions/documentInsertLink.md), [searchIndexDocument()](/code/functions/searchIndexDocument.md)

