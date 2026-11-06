# includes/html_sanitise.php

> PHP - 167 lines

Server-side allow-list sanitiser for HTML written by a CUSTOMER.

WHY THIS EXISTS
---------------
The self-service new-ticket form uses a rich-text editor, so the description
arrives as HTML rather than plain text. That HTML is stored and then rendered
in the ANALYST's reading pane — so an unprivileged person is now authoring
markup that a privileged person will open.

The reading pane already cleans message bodies at render time
(assets/js/safe-html.js), and that remains the last line of defence. This is
the first: cleaning on the way IN means the database never holds the payload
at all, so anything that reads `emails.body_content` in future — an export, a
digest email, a report, a channel that hasn't been written yet — inherits the
protection instead of having to remember it.

ALLOW-LIST, NOT DENY-LIST
-------------------------
Everything is removed unless named here. A deny-list has to anticipate every
dangerous construct; an allow-list only has to know what a customer legitimately
needs to describe a problem: paragraphs, emphasis, lists, links, tables.

Deliberately NOT allowed: <script>, <style>, <iframe>, <object>, <embed>,
<form>, <input>, and every event handler. Also <img> — a customer attaches
screenshots as files, and permitting remote images would let a ticket body
beacon the analyst's IP and read-time to a third-party server.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sanitiseUserHtml()](/code/functions/sanitiseUserHtml.md) | 53 | Clean customer-authored HTML down to the allow-list. |
| [sanitiseUserHtmlNode()](/code/functions/sanitiseUserHtmlNode.md) | 104 | Walk a node's children, removing or unwrapping anything not allow-listed. Iterates over a SNAPSHOT of the child list because the loop mutates it. |

## How it hangs together

**Included by:** [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md)

