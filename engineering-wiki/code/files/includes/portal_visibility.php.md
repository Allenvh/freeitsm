# includes/portal_visibility.php

> PHP - 163 lines

What the requester may see of their OWN ticket in the self-service portal.

THE PROBLEM
-----------
A ticket's thread is not always a conversation with the requester. When an
analyst forwards a ticket to a supplier, or CCs a colleague, that message is
written onto the SAME ticket — and the supplier's reply lands there too. The
portal showed the whole thread, so correspondence *about* the requester was
visible *to* the requester, occasionally with another customer's document
attached.

"Forward" is not recorded anywhere: a forward and an ordinary reply look
identical in `emails`. So the rule is not "was this a forward" but the
question that actually matters, which also covers CCs and third-party
replies, and works on existing history with no migration:

    Was this message TO or FROM the person who raised the ticket?

WHERE THIS MUST BE APPLIED
--------------------------
BOTH the thread listing AND the attachment download endpoint. Hiding a
message in the list while its attachment URL still works is decoration, not
enforcement — the id is guessable and the file is the sensitive part.

FAILING TOWARDS VISIBILITY
--------------------------
Every ambiguous case resolves to VISIBLE, deliberately:

  - Non-email channels (WhatsApp, web chat) are addressed by phone number or
    visitor id, so a requester's EMAIL matches nothing on them. Comparing
    addresses there would hide the customer's entire conversation from their
    own portal. There is also no "forward" on those channels — the exchange is
    between the visitor and the desk by definition. Always visible.
  - A message with no recipients recorded cannot be judged. Visible.
  - A requester whose address is simply UNKNOWN to us ('') cannot be judged.
    Visible.

Hiding an analyst's genuine reply is a worse failure than showing a forward:
the customer is left believing nobody answered them. The strict direction is
the one an admin opts into per-message-type, not one we guess at.

⚠️ THE ONE CASE THAT IS NOT AMBIGUOUS
-------------------------------------
A requester who has NO MAILBOX AT ALL (`$requesterEmail === null`) is a
different thing entirely from one whose address we don't know. Directory
users may genuinely have none (GitHub #47). No email can ever have been
addressed to them, so every email-channel message on their ticket is
third-party by definition and the policy APPLIES — it is not ambiguous and
must not fail open. Their own messages arrive by the portal and are matched
on `direction` well before any address comparison.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [portalThirdPartyPolicy()](/code/functions/portalThirdPartyPolicy.md) | 71 | The configured policy: Tickets → Settings → Privacy. |
| [portalEmailInvolvesRequester()](/code/functions/portalEmailInvolvesRequester.md) | 97 | Is this message part of the requester's own correspondence? |
| [portalEmailVisibility()](/code/functions/portalEmailVisibility.md) | 148 | Apply the policy to one message. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 77 | `portalThirdPartyPolicy` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md), [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md)

