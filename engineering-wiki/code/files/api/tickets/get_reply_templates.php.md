# api/tickets/get_reply_templates.php

> API endpoint - 44 lines

API Endpoint: the reply templates this analyst may insert.

GET  ?all=1  include inactive ones too (the settings tab wants them; the reply-box
             picker does not — an inactive template is one an admin has retired).

Deliberately NOT capability-gated. Inserting a canned response is the everyday job,
like replying at all; gating it would not tighten anything, it would just mean most
of the service desk sees an empty picker. The capability guards WRITING a shared
template, which is where the settings power actually is.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_reply_templates.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET all` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/reply_templates.php](/code/files/includes/reply_templates.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [replyTemplateMergeCodes()](/code/functions/replyTemplateMergeCodes.md), [replyTemplatesVisibleTo()](/code/functions/replyTemplatesVisibleTo.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

