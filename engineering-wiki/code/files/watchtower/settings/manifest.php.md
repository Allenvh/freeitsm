# watchtower/settings/manifest.php

> PHP - 50 lines

Watchtower — settings manifest.

Watchtower is the one module that owns no data: it reads every other module
and shows what needs attention. So its settings are entirely about the VIEW,
and neither capability here grants any access to the things being counted —
each card still shows only what the analyst is allowed to see.

Why this screen exists at all, given the statuses are configured elsewhere:
"which statuses appear on my dashboard" is a fact about the dashboard, not
about the status. Statuses carry their own facts — is_closed, is_default —
which the SLA engine and half the application depend on, and mixing one
view's preferences into them would mean tuning Watchtower by touring three
other modules' settings screens.

The two tabs are split because they answer different questions and carry
different risk. CARDS is layout: hide the Contracts card and you have tidied
a screen. COUNTS changes what a number MEANS — narrow "high priority" and the
figure everyone reads each morning silently changes definition — so it is
worth being able to grant the first without the second.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

