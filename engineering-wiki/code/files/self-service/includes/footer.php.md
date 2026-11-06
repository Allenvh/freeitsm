# self-service/includes/footer.php

> PHP - 83 lines

Self-service portal — the shared page bottom.

Closes the layout opened by header.php and bootstraps client-side i18n, which
every portal page needs and each one used to wire up itself.

A page may set BEFORE including this:
  `$pageScripts` — a string of page-specific JS, emitted after the i18n bootstrap
                 so window.t() and API_BASE are already available to it.
  `$pageData`    — page-specific VALUES for that JS, as an array. Emitted as
                 window.PAGE, JSON-encoded.
  `$needsRecorder` — true to load assets/js/screen-recorder.js. Pair it with
                 `require __DIR__ . '/includes/record-modal.php';` in the
                 markup; the script binds to that partial's element ids.

`$pageData` exists because `$pageScripts` is echoed as a plain STRING, and pages
build it with a nowdoc (<<<'JS') so that JS template literals — ${...} — are
not eaten by PHP interpolation. The cost of a nowdoc is that PHP tags inside
it are NOT executed: a `<?php echo `$id`; ?>` written in there reaches the
browser verbatim, and because the stray `<` is a syntax error at the top of
the block, the ENTIRE script silently fails to parse and the whole page's JS
dies. That happened to the ticket page. So values come through here instead
of being interpolated into the script text.

## How it hangs together

**Included by:** [self-service/catalogue.php](/code/files/self-service/catalogue.php.md), [self-service/course.php](/code/files/self-service/course.php.md), [self-service/help-centre.php](/code/files/self-service/help-centre.php.md), [self-service/help.php](/code/files/self-service/help.php.md), [self-service/index.php](/code/files/self-service/index.php.md), [self-service/my-equipment.php](/code/files/self-service/my-equipment.php.md), [self-service/new-ticket.php](/code/files/self-service/new-ticket.php.md), [self-service/tickets.php](/code/files/self-service/tickets.php.md), [self-service/training.php](/code/files/self-service/training.php.md)

**Loads scripts:** `self-service/assets/js/i18n.js`, `self-service/assets/js/safe-html.js`, `self-service/assets/js/toast.js`, `self-service/assets/js/confirm.js`, `self-service/assets/js/form-logic.js`, `self-service/assets/js/form-render.js`, `self-service/assets/js/screen-recorder.js`

