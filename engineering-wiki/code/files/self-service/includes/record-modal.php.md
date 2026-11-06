# self-service/includes/record-modal.php

> PHP - 42 lines

Self-service portal — the screen-recording modal.

Included by any page that offers screen recording: raising a ticket
(new-ticket.php) and replying to one (tickets.php). The behaviour lives in
assets/js/screen-recorder.js, which binds to these ids — so include this
ONCE per page, and keep the ids in step with that file.

A modal rather than an inline panel because recording is a self-contained
task with its own start / stop / keep decision: expanding it in place shoved
the rest of the form around and left the video preview no room. Per Ed it
also has a Cancel that closes it outright.

i18n keys are `self-service.recorder.*` — shared, not the caller's namespace,
since both pages show exactly the same dialog.

## How it hangs together

**Included by:** [self-service/new-ticket.php](/code/files/self-service/new-ticket.php.md), [self-service/tickets.php](/code/files/self-service/tickets.php.md)

**Calls:** [t()](/code/functions/t.md)

