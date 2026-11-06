# includes/branding_preview.php

> PHP - 128 lines

The live preview, shared by every screen the branding designer can style.

Included ONLY when a page has decided it is being previewed by an
administrator (`?preview=1`); it renders a <script> and nothing else.

🔑 One copy, because three copies of this would drift — and the whole point
of previewing the real page rather than a mock-up is that what you see is
what you get. Two of them silently disagreeing would be the same failure
wearing a different hat.

⚠️ Safe by construction:
  · BroadcastChannel is SAME-ORIGIN, so no other site can post to it, and the
    postMessage path (used by the iframe in the settings screen) checks the
    origin explicitly.
  · It changes colours, layout and text in ONE browser and writes nothing.
    Persisting still goes through the guarded, validated save endpoint.
  · Values are re-checked here anyway — a colour must match #rrggbb, a layout
    must be one of the known words — so this path keeps the same discipline
    as the server even where it does not have to.
  · Text is written with textContent, never innerHTML.

`$previewDefaultHeading` is the heading the page falls back to when the
designer supplies none; only the calling page knows its own translated
default. Set it before requiring this file.

## How it hangs together

**Included by:** [index.php](/code/files/index.php.md), [self-service/login.php](/code/files/self-service/login.php.md)

