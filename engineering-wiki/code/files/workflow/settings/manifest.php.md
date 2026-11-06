# workflow/settings/manifest.php

> PHP - 40 lines

Workflow — settings manifest. See includes/capabilities.php.

NOTE what is deliberately NOT here: authoring workflows. A workflow can send email, call
webhooks and change records right across the product, so the ability to write one is
governed by module access to Workflow — which is itself the strong permission, and the
one to be careful who you grant. These tabs are the module's own configuration.

'formats' are the webhook message shapes (Slack, Teams, Discord, …) — data, not code, so
that adding a new destination needs no release.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

