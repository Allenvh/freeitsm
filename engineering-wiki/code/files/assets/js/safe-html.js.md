# assets/js/safe-html.js

> JavaScript - 144 lines

`safeHtmlFragment()` — the one cleaner for untrusted HTML we have to display.

WHAT COUNTS AS UNTRUSTED
------------------------
Email bodies and chat messages. Anyone can email a service desk, so this
content is written by people outside the organisation, and it is rendered
inside a signed-in session — an analyst's reading pane, or a requester's own
ticket page in the self-service portal.

WHY THIS IS SHARED
------------------
The analyst inbox and the portal both display the same email bodies. A
security control kept in two copies drifts, and the copy that falls behind is
the one nobody is looking at. One function, both surfaces.

THE MISTAKE THIS FIXES
----------------------
The inbox's original cleaner was written for LAYOUT hygiene — balancing
unclosed tags and stripping <style> blocks that bled into the app's chrome —
and it reasoned about <script> correctly: scripts genuinely do NOT execute
when HTML is assigned via innerHTML. But that is only true of <script>.
Inline event handlers DO fire:

    <img src=x onerror="...">        fires on insertion, every browser
    <svg onload="...">               same

Shadow DOM does not help either — it isolates CSS, not script execution. So
an email could run code in the reader's session. Removing <script> alone is
not a defence; the handlers are the actual hole.

WHAT IT DOES
------------
  1. Parses in an inert DOMParser document, so nothing executes and nothing
     touches the live DOM while we clean it. This also balances the unclosed
     tags real email is full of — without that, a runaway <div> swallows the
     siblings that follow it in the reading pane.
  2. Drops tags that execute, navigate, or leak styling into the page.
  3. Drops EVERY inline event handler (any attribute starting "on").
  4. Drops javascript: and data:text/html URLs, which are script by another
     route. Whitespace is stripped before the check because "java\tscript:"
     is parsed as javascript: by browsers but not by a naive startsWith.

It deliberately keeps ordinary formatting, links and images: this has to stay
usable for reading genuine mail, and a cleaner so aggressive that people stop
trusting the reading pane gets worked around.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `safeHtmlFragment(html, options)` | 55 | `safeHtmlFragment()` — the one cleaner for untrusted HTML we have to display. |
| `escapeHtmlText(text)` | 108 | Fallback for callers when this file failed to load. Returns the text with all markup escaped — visible but inert. Fails CLOSED: a missing security control must never mean "render it raw". |
| `messageBodyHtml(body, bodyType, options)` | 136 | Render one message body, honouring what the message actually IS. |

## Loaded by

- [knowledge/assistant/index.php](/code/files/knowledge/assistant/index.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)

