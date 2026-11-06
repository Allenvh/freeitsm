# includes/reply_templates.php

> PHP - 161 lines

Canned responses — the reply text an analyst inserts instead of retyping it.

WHY THIS IS SHARED
------------------
Three callers need the same two answers ("which templates may this analyst see?"
and "what does this one say for this ticket?"): the settings tab, the reply box
picker, and the render endpoint behind it. Two copies of the visibility rule is
the dangerous duplication — nothing breaks when they drift, one of them just
quietly shows an analyst somebody else's private templates.

MERGE CODES ARE DELIBERATELY THE SAME ONES AS THE AUTOMATED EMAILS
-----------------------------------------------------------------
[requester_first_name], [ticket_reference] and friends already exist for
ticket_email_templates, resolved by `buildTicketMergeData()` in template_email.php.
This reuses that builder rather than defining a second vocabulary — an admin who
has written one kind of template can write the other without relearning anything,
and a merge code added there works here for free.

...BUT THE ESCAPING IS NOT THE SAME, AND THAT IS THE WHOLE POINT
---------------------------------------------------------------
`resolveMergeCodes()` in template_email.php substitutes raw. That is survivable for
an outbound email whose only reader is the requester themselves. It is NOT
survivable here: a canned response is merged and then dropped into the analyst's
TinyMCE editor and re-rendered in the inbox, and `requester_name` ultimately comes
from the From header of an email a stranger sent us. A requester called
`<img src=x onerror=...>` would otherwise be running script in the analyst's
browser at the moment they try to answer them. So every VALUE is escaped before it
is merged into the template's HTML — the template body is trusted (an analyst
authored it), the values substituted into it never are.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [replyTemplateMergeCodes()](/code/functions/replyTemplateMergeCodes.md) | 44 | The merge codes offered in the UI, in the order they are listed. |
| [renderReplyTemplate()](/code/functions/renderReplyTemplate.md) | 72 | Resolve a template body against a ticket, escaping every substituted value. |
| [replyTemplatesVisibleTo()](/code/functions/replyTemplatesVisibleTo.md) | 103 | Every template this analyst may insert, shared first then their own. |
| [replyTemplateWriteScope()](/code/functions/replyTemplateWriteScope.md) | 147 | May this analyst write to this template? |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 118 | `replyTemplatesVisibleTo` | read [ticket_reply_templates](/code/tables/ticket_reply_templates.md) |  |
| 149 | `replyTemplateWriteScope` | read [ticket_reply_templates](/code/tables/ticket_reply_templates.md) |  |

## How it hangs together

**Includes:** [includes/template_email.php](/code/files/includes/template_email.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/tickets/delete_reply_template.php](/code/files/api/tickets/delete_reply_template.php.md), [api/tickets/get_reply_templates.php](/code/files/api/tickets/get_reply_templates.php.md), [api/tickets/render_reply_template.php](/code/files/api/tickets/render_reply_template.php.md), [api/tickets/save_reply_template.php](/code/files/api/tickets/save_reply_template.php.md)

**Calls:** [buildTicketMergeData()](/code/functions/buildTicketMergeData.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)

