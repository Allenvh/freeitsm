# api/documents/unlink.php

> API endpoint - 117 lines

API Endpoint: detach a document from one record.

🔑 THIS REMOVES THE LINK, NOT THE DOCUMENT. The same file may be attached to
eleven other things, and taking it off a contract must not delete it from
under them. Only when the LAST link goes does the document become an orphan —
and an orphan is then soft-deleted and its file removed, because a document
nothing points at is visible to nobody and would otherwise sit on disk for ever.

You may detach from a record you can see. That is the same permission as
attaching to it, deliberately: anyone who can put a document on a contract can
take it off again.

## The endpoint

| | |
|---|---|
| URL | `api/documents/unlink.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 |  | **delete** [document_links](/code/tables/document_links.md) |  |
| 65 |  | read [document_links](/code/tables/document_links.md) |  |
| 73 |  | read [documents](/code/tables/documents.md) |  |
| 77 |  | **update** [documents](/code/tables/documents.md) (`deleted_datetime`) |  |
| 92 |  | read [documents](/code/tables/documents.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanView()](/code/functions/documentCanView.md), [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentEntityDef()](/code/functions/documentEntityDef.md), [documentStoragePath()](/code/functions/documentStoragePath.md), [searchUnindexDocument()](/code/functions/searchUnindexDocument.md)

