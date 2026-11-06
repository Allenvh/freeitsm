# self-service/catalogue.php

> PHP - 759 lines

Self-Service Portal — Request Catalogue.

Customers pick a form ("New laptop", "Access request"), fill it in, and it is
recorded as a submission for the service desk to action. Only forms an
analyst has deliberately offered appear here (forms.is_portal_visible).

The field renderer mirrors the analyst-side forms/fill.php switch, minus its
analyst chrome.

⚠️ THAT USED TO BE THE WHOLE STORY. Every type was a plain literal input —
text, number, a dropdown of the form's own options — so nothing a customer
could see came out of our own records, and nothing here needed withholding.

`lookup` broke that assumption: it is a search box over the install's own
assets, CMDB objects or users. So it renders here ONLY when both gates pass —
the source is marked portal-safe (a staff directory never is) and the field
itself was ticked for portal use by whoever built the form. Off by default.

⚠️ This markup appearing is NOT permission. api/forms/lookup_search.php
re-checks both gates and scopes results to the customer's own company, because
a request can be made without ever loading this page.

## How it hangs together

**Includes:** [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/includes/footer.php](/code/files/self-service/includes/footer.php.md)

**Calls:** [t()](/code/functions/t.md)

