# api/tickets/save_reply_template.php

> API endpoint - 116 lines

API Endpoint: create or update a reply template.

POST { id?, name, body, scope: 'shared'|'mine', is_active?, display_order? }

TWO DIFFERENT PERMISSION RULES IN ONE ENDPOINT
----------------------------------------------
  scope 'mine'   — anybody with the Tickets module. It is one analyst's own text,
                   saved from the reply box, and nobody else will ever see it.
  scope 'shared' — Cap::TICKETS_REPLY_TEMPLATES. This is settings: it puts words in
                   every colleague's picker, under the company's name.

The case worth staring at is the SEAM between them. A private template is yours to
edit — but flipping one to shared is not an edit, it is publishing, so the
capability is checked against the scope being SAVED, not the scope it had. Without
that, every analyst could promote their own draft into the team list and the
settings permission would mean nothing.

ON NOT SANITISING THE BODY
--------------------------
Analyst-authored HTML is stored as written, deliberately. `sanitiseUserHtml()` is for
CUSTOMER markup and its allow-list drops colours and styling — which is exactly the
rich formatting a template is for. And it would buy nothing: an analyst can already
put arbitrary HTML in an outbound reply via send_email.php, so a template grants no
capability they lack. The genuinely untrusted input here is the merge VALUES
(a requester's name comes from a stranger's From header) and those are escaped at
render time in includes/reply_templates.php.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_reply_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_REPLY_TEMPLATES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 95 |  | **update** [ticket_reply_templates](/code/tables/ticket_reply_templates.md) (`name`, `body`, `analyst_id`, `tenant_id`, `is_active`, `display_order`, `updated_datetime`) |  |
| 103 |  | **insert** [ticket_reply_templates](/code/tables/ticket_reply_templates.md) (`name`, `body`, `analyst_id`, `tenant_id`, `is_active`, `display_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/reply_templates.php](/code/files/includes/reply_templates.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [replyTemplateWriteScope()](/code/functions/replyTemplateWriteScope.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

