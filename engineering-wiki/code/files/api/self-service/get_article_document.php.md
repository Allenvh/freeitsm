# api/self-service/get_article_document.php

> API endpoint - 71 lines

Serve a document attached to a knowledge article, to a portal requester who may read that article in the Help Centre (GH #177).

The portal twin of api/documents/download.php for ARTICLES, as
get_document.php is for shared ticket notes. Kept as its own endpoint rather
than a branch of that one: the two answer different questions ("did you raise
this ticket" against "may you read this article"), and each is easier to
trust when it answers exactly one.

Every refusal is the same 404 - a wrong id, a hidden document, an article in
another company, an internal article, a document that is only on a contract -
so ids cannot be used to learn what exists. The decision is
`helpCentreDocumentForPortalUser()`; this file only serves what it returns.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_article_document.php` |
| Protected by | Signed-in portal user (session check) |
| Reads | `GET id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md)

**Calls:** [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [documentStoragePath()](/code/functions/documentStoragePath.md), [helpCentreDocumentForPortalUser()](/code/functions/helpCentreDocumentForPortalUser.md), [requesterIsActive()](/code/functions/requesterIsActive.md)

