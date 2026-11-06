# api/documents/download.php

> API endpoint - 107 lines

API Endpoint: fetch a document.

⚠️ THIS IS THE BOUNDARY. Everything else in the feature decides what to LIST;
this decides what somebody may HAVE. It must never assume the caller found the
id through a filtered list — `download.php?id=12345` with a guessed integer is
the attack, and integers are easy to guess.

So the check here is the full one: `documentCanView()` walks every link and asks
whether the caller can see any parent. It is deliberately the expensive form,
because N is 1 and being right matters more than being quick.

Every successful fetch is recorded. This is the only route to a document, so
it is the only place that can honestly claim to have seen every access.

## The endpoint

| | |
|---|---|
| URL | `api/documents/download.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET id` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [documentDenied()](/code/functions/documentDenied.md) | 38 | One refusal for every reason. "No such document" and "not yours" must look identical, or the difference between them maps out what exists. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 |  | read [documents](/code/tables/documents.md) |  |
| 66 |  | **insert** [document_access_log](/code/tables/document_access_log.md) (`document_id`, `analyst_id`, `action`, `ip_address`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/documents.php](/code/files/includes/documents.php.md)

**Calls:** [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanView()](/code/functions/documentCanView.md), [documentStoragePath()](/code/functions/documentStoragePath.md)

