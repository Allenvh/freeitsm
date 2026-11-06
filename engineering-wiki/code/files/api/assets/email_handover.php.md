# api/assets/email_handover.php

> API endpoint - 79 lines

API: email the handover document to the person it is about (discussion #56).

POST { user_id: <int>, template_id: <int|null> }

Sends through `ssSendSystemEmail()`, the same sender the portal uses, so it
inherits the configured sending mailbox rather than introducing a second idea
of how this installation sends mail.

⚠️ The email is built by the SAME renderer as the printed page, with the
stylesheet inlined in a <style> block — a mail client will not fetch an
external sheet. One layout, three destinations.

## The endpoint

| | |
|---|---|
| URL | `api/assets/email_handover.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/services/handover_templates.php](/code/files/includes/services/handover_templates.php.md), [includes/handover_styles.php](/code/files/includes/handover_styles.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [handoverDocumentCss()](/code/functions/handoverDocumentCss.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [t()](/code/functions/t.md)

