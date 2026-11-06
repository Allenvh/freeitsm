# api/documents/help_centre.php

> API endpoint - 76 lines

API Endpoint: show or hide one document of a knowledge article in the self-service Help Centre (GH #177).

POST { document_id, parent_id, show }   (parent_type is always knowledge_article)

The same two checks as attach.php and unlink.php - you must be able to see
the article AND the document - because showing a document to customers is
the same kind of act as attaching it: it widens who can read it. Editing an
article asks for nothing more than that (module, company, folder access), so
neither does this.

Recorded in the article's history, because "who put this file in front of
customers" is exactly the question somebody will one day ask.

## The endpoint

| | |
|---|---|
| URL | `api/documents/help_centre.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 64 |  | read [documents](/code/tables/documents.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md), [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanView()](/code/functions/documentCanView.md), [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentHelpCentreReady()](/code/functions/documentHelpCentreReady.md), [helpCentreSetShown()](/code/functions/helpCentreSetShown.md), [knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md)

