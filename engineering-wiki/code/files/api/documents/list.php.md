# api/documents/list.php

> API endpoint - 145 lines

API Endpoint: the documents attached to one record.

GET ?parent_type=contract&parent_id=42[&offset=0&limit=50]

Paged from the start. "Show all attachments" is the obvious first
implementation and it is fine right up until somebody attaches three thousand
photographs to an asset, at which point it is a page that never renders.

Each row also reports what ELSE it is attached to — limited to parents the
caller can see, because "also on Contract 42" would otherwise disclose the
existence of a contract they have no access to. That list is what makes the
widening rule visible: attach a document somewhere public and everyone there
can read it, so the UI has to say where it already is.

## The endpoint

| | |
|---|---|
| URL | `api/documents/list.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET parent_type`, `GET parent_id`, `GET offset`, `GET limit` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 |  | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |
| 72 |  | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md), read [analysts](/code/tables/analysts.md) |  |
| 87 |  | read [document_links](/code/tables/document_links.md) |  |
| 127 |  | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentEntityDef()](/code/functions/documentEntityDef.md), [documentHelpCentreReady()](/code/functions/documentHelpCentreReady.md), [documentParentName()](/code/functions/documentParentName.md), [documentsCollectOrphans()](/code/functions/documentsCollectOrphans.md), [helpCentreArticleIsCustomerFacing()](/code/functions/helpCentreArticleIsCustomerFacing.md)

