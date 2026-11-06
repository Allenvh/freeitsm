# api/self-service/submit_catalogue_form.php

> API endpoint - 74 lines

API: submit a request-catalogue form from the self-service portal. POST { form_id, data: { field_id: value, ... } }

Goes through FormsService::submitForm() so the portal gets the SAME
validation the analyst path has — required fields, email/number formats, and
(newly) choice values checked against the field's own options. Duplicating
that here would be the classic drift: the customer-facing copy is the one
that would fall behind.

The submitter is passed as `$portalUserId`, NOT as ActorContext->actorId:
`users` and `analysts` are different id spaces, `form_submissions.submitted_by`
has no foreign key, and every reader LEFT JOINs it to `analysts` — so a
requester's id written there would silently appear as whichever analyst
shared the number. The service writes them to different columns.

The context therefore carries actorId 0: there is no analyst here, and saying
so plainly is better than borrowing someone's id.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/submit_catalogue_form.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

