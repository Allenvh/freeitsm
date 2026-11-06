# self-service/tickets.php

> PHP - 1169 lines

Self-Service Portal — My Tickets (two-pane, Outlook-style).

Replaces the dashboard→ticket.php round trip: the list stays on screen and
the conversation loads beside it, so moving between tickets doesn't reload
the page. ticket.php now redirects here, keeping old links working.

The layout is the tickets inbox's shape, one pane lighter: the analyst needs
folders because they triage everyone's work — a customer only ever has their
own tickets, so a folder pane would be an empty gesture.

## How it hangs together

**Includes:** [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/includes/record-modal.php](/code/files/self-service/includes/record-modal.php.md), [self-service/includes/footer.php](/code/files/self-service/includes/footer.php.md)

**Calls:** [t()](/code/functions/t.md)

