# tickets/settings/manifest.php

> PHP - 308 lines

Tickets — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. See includes/capabilities.php.

---------------------------------------------------------------------------
THIS IS THE MODULE THE WHOLE DESIGN WAS ARGUED FOR
---------------------------------------------------------------------------
Fourteen tabs, and they are emphatically not the same kind of thing:

  Mailboxes       OAuth credentials and inbound mail routing. Whoever holds this can
                  point the service desk's mailbox somewhere else — that is, redirect
                  or read the company's email.
  Messaging       the WhatsApp channel's Twilio/Meta credentials.
  Reply cleanup   an AI provider's API key.
  SLA             the targets the whole service desk is measured against.
  ...
  Ticket types    a list of words.

A single "manage Tickets settings" permission would have made those one grant. It is
why the unit of permission is the TAB.

---------------------------------------------------------------------------
WHAT IS DELIBERATELY NOT HERE
---------------------------------------------------------------------------
Working tickets. Replying, assigning, closing, adding notes, checking the mailbox for
new mail, sending a WhatsApp reply — all of that is the everyday job and stays on plain
module access. Gate any of it and you have not tightened security, you have broken the
service desk.

Teams and analysts are NOT here either: they moved to the System module (#769-771) and
are already administrator-only. The department/team MAPPING lives on the Departments tab
and belongs to Tickets; creating the team itself does not.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

