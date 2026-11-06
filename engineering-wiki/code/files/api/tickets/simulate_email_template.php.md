# api/tickets/simulate_email_template.php

> API endpoint - 62 lines

API Endpoint: "if an email arrived from this address, which template would go back?" POST { event_trigger, email } -> { template_id, template_name, reason, explanation }

WHY A SIMULATOR EXISTS AT ALL
-----------------------------
Sender rules (discussion #80) are the first thing in FreeITSM where an
administrator's configuration decides whether somebody gets an email or silence,
and where being wrong produces no error — just a person who never heard back.
Reading the rules off the screen and working out the answer in your head is
exactly the step that goes wrong, so this does it with the same code that will
do it for real: `templateSelectForRecipient()`, not a second implementation of the
matching that could drift from it.

It answers with the REASON as well as the winner, because "Standard reply" tells
you what happens and not whether it happens for the reason you intended.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/simulate_email_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_EMAIL_TEMPLATES (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [templateSelectForRecipient()](/code/functions/templateSelectForRecipient.md)

