# api/forms/decide_catalogue_approval.php

> API endpoint - 37 lines

API: approve or reject a pending catalogue request (#928).

POST { submission_id, decision: 'approved'|'rejected', comment? }
  -> { success, decision, ticket_id, ticket_number }

Approving raises the ticket the request becomes (correct portal tenancy/identity)
and stamps form_submissions.ticket_id; rejecting records the decision only. The
engine refuses if the caller is not the assigned approver (or an admin), or if the
request is not still pending. See includes/catalogue_approvals.php.

## The endpoint

| | |
|---|---|
| URL | `api/forms/decide_catalogue_approval.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md)

**Calls:** [catalogueApprovalDecide()](/code/functions/catalogueApprovalDecide.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

