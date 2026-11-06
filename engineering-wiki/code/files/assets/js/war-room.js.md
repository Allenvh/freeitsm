# assets/js/war-room.js

> JavaScript - 1195 lines

War room — the client half.

🔒 EVERY PIECE OF MESSAGE TEXT IS INSERTED WITH textContent. Never innerHTML,
never a template string built from a message body, never an attachment's
filename dropped into markup. A chat box that renders other people's text as
HTML is a stored-XSS hole that every analyst walks into during an incident,
which is the worst possible moment for it. There is no sanitiser here on
purpose: not producing HTML is stronger than cleaning it afterwards.

⚠️ NOTHING ON THIS PAGE MAY LOAD FROM THE INTERNET. No CDN, no font, no remote
image. If it cannot be served from this box, it does not belong here.

The poll is the module's heartbeat: it fetches new messages, records presence,
reports who else is here, and refreshes the channel list with its unread
counts — one request rather than four. See api/war-room/poll.php for why it is
a poll and not an EventSource.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `insertedForm(person)` | 53 | What the picker actually types into the box for this person. |
| `t(key, vars)` | 74 | Translates a key through window.t, or returns the key when no translator is loaded. |
| `el(tag, className, text)` | 81 | ── small DOM helpers ──────────────────────────────────────────────────── `el()` takes TEXT, not markup, which is what keeps the XSS rule above from depending on anybody remembering it at each call site. |
| `clear(node)` | 88 | Removes every child of a DOM node. |
| `localTime(utc)` | 91 | Local time from the server's UTC string, via the app's shared tz helper. |
| `bytes(n)` | 98 | Formats a byte count as B, KB or MB for an attachment chip. |
| `renderChannels()` | 113 | Redraws the channel list - the all-hands channel first, then the Teams, Channels and Direct groups. |
| `addChannelButton(c)` | 128 | Appends one channel button with its private marker and its mention or unread badge (neither shows for the open channel). |
| `currentChannel()` | 151 | Returns the channel object for the open channel, or null. |
| `applyChannelHeader()` | 159 | Fills the title and topic for the open channel, swaps the composer for the archived note when archived, and shows Manage only for custom channels. |
| `switchChannel(id)` | 176 | Opens another channel - clears the message list, resets the since_id watermark and polls at once. |
| `renderMessage(m)` | 199 | Builds one message row - author, Warbot tag, time, edited flag, body with `@mentions`, edit/delete buttons and attachments - all as text nodes. |
| `renderBodyWithMentions(node, text)` | 286 | Write a message body into `node`, marking the `@names` inside it. |
| `nameKnown(name)` | 319 | True when a name (full or first) belongs to someone in the directory, the current analyst, or Warbot. |
| `mentionsMe(name)` | 327 | True when a mentioned name is the current analyst's full or first name. |
| `guessKind(name)` | 333 | Only used to decide thumbnail vs link; the server decides what is served. |
| `appendMessages(list)` | 341 | Adds polled messages to the list, replacing any already shown with the same id, and scrolls down only for new ones when the reader was at the bottom. |
| `renderPresence(p)` | 377 | Draws the presence panel - who is in this channel, who is elsewhere in the war room, or an offline warning. |
| `poll()` | 403 | The heartbeat - fetches poll.php for new messages, channel list with counts and presence, and flags the page offline when it fails. |
| `startPolling()` | 427 | Starts (or restarts) the poll every POLL_MS milliseconds. |
| `renderPending()` | 435 | Draws a removable chip for each file chosen but not yet sent. |
| `acContext()` | 479 | The `@word` being typed at the caret, or null. |
| `acRender()` | 496 | Shows the @ autocomplete for the word at the caret - up to six directory matches, plus `@everyone` and Warbot when they fit. |
| `acHide()` | 528 | Hides the @ autocomplete and forgets its matches. |
| `acAccept(i)` | 531 | Replaces the typed `@word` with the chosen name in the analyst's mention style, then closes the picker. |
| `mentionBeforeCaret()` | 557 | The mention immediately before the caret, if there is one. |
| `openEdit(m)` | 649 | Opens the edit modal for one of the analyst's own messages; Save sends the new body through messageAction. |
| `openDelete(m)` | 674 | Opens the delete confirmation for a message; confirming sends the delete through messageAction. |
| `messageAction(action, payload)` | 696 | Posts an edit or delete to message.php, then reloads the whole channel because the changed message is behind the poll watermark. |
| `send()` | 722 | Sends the composer text and any pending files to send.php (multipart when files are attached), reports refused files, and asks Warbot when addressed. |
| `done()` | 728 | Re-enables the Send button when the request settles. |
| `addressesWarbot(text)` | 779 | True when the text mentions `@warbot` or starts with a Warbot slash command. |
| `askWarbot(messageId)` | 784 | Shows a "thinking" placeholder and asks warbot.php to answer a message; the next poll brings in the reply. |
| `openPanel(title)` | 814 | Opens the side panel (search or situation report) with a title and returns its empty body. |
| `closePanel()` | 821 | Hides the side panel. |
| `run()` | 847 | Runs the message search through search.php (all channels, or this one when ticked) and lists the hits, each one switching to its channel. |
| `openModal(title)` | 967 | Opens the shared dialog with a title and returns its empty body. |
| `closeModal()` | 974 | Hides the shared dialog. |
| `field(parent, labelText, node)` | 982 | Appends a label and a control to a dialog body and returns the control. |
| `post(action, payload, onOk, failKey)` | 991 | Posts a channel action (create, dm, update, archive, restore) to channels.php; calls onOk on success, alerts with failKey on failure. |
| `draw(filter)` | 1082 | Lists the directory people matching the filter, each a button that opens a direct message with them. |
| `save(value)` | 1153 | Saves the analyst's desktop-alerts preference (warroom_desktop_alerts) through WR_PREF_URL. |

## Loaded by

- [war-room/index.php](/code/files/war-room/index.php.md)

