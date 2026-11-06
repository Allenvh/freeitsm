# self-service/help-centre.php

> PHP - 651 lines

Self-Service Portal — Help Centre (the knowledge base).

The first reader at the Audience::CUSTOMER rung, which has existed unread
since #869. Distinct from help.php, which is the guide to using the portal
itself — hence "Using the portal" vs "Help Centre" in the nav.

Chrome (head, theme, header, nav, footer) comes from includes/header.php and
includes/footer.php; shared styling from assets/css/self-service.css.

## How it hangs together

**Includes:** [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/includes/footer.php](/code/files/self-service/includes/footer.php.md)

**Calls:** [t()](/code/functions/t.md)

