# api/webchat/widget.js

> JavaScript - 465 lines

FreeITSM web chat widget loader.

Dropped onto any website via the embed snippet:
  <script src=".../api/webchat/widget.js" data-freeitsm-widget="wc_..."></script>

It reads its own key + API base from the script tag, pulls the widget's look-and-feel
from config.php, and renders a floating launcher + chat panel inside a Shadow DOM (so
the host site's CSS can't touch it and vice-versa). Messages go to send.php; analyst
replies arrive by polling poll.php. The per-conversation token lives in sessionStorage,
so a page navigation within the visit keeps the same chat.

Vanilla JS, no dependencies, no cookies.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `api(path)` | 42 | The full address of one of the web chat endpoints, next to this script. |
| `esc(s)` | 45 | Escapes text for safe use inside HTML. |
| `styles()` | 60 | The widget's CSS, injected into its Shadow DOM so the host page's styles cannot reach it. |
| `build()` | 122 | Adds the styles, the launcher button and the (closed) chat panel to the Shadow DOM. |
| `el(sel)` | 138 | Finds the first element inside the widget's Shadow DOM that matches a selector. |
| `scrollDown()` | 141 | Scrolls the message list to the newest message. |
| `showTyping()` | 150 | Shows the three-dot typing indicator while a reply is expected, removing it by itself after 30 seconds if none arrives. |
| `hideTyping()` | 164 | Removes the typing indicator. |
| `escBarHtml()` | 174 | The "Talk to a person" / "Email me back" buttons, when the AI assistant is on and the widget offers them - empty otherwise. |
| `renderChatShell()` | 187 | Draws the chat view - message list, escalation buttons and the message box (Enter sends, Shift+Enter is a new line) - then shows the greeting and, out of hours, the offline message. |
| `hideEscBar()` | 214 | Removes the escalation buttons once the visitor has chosen one. |
| `renderIntro()` | 220 | Draws the start form that asks for the visitor's name and email before the chat begins. |
| `addMessage(m, scroll)` | 236 | Adds one message to the list - a centred system notice, or a bubble from the agent (with their name) or the visitor - and scrolls down unless scroll is false. |
| `onEscalate(mode)` | 259 | Asks for a person ('agent') or an email reply ('email') through escalate.php, then removes the buttons and polls for the confirmation note. |
| `loadConfig()` | 282 | Fetches the widget's settings (name, colour, greeting, hours, AI options) from config.php. |
| `onStart(e)` | 298 | Starts a conversation through start.php with the name and email given, keeps its token in sessionStorage so the chat survives page navigation, then shows the chat and starts polling. |
| `onSend(e)` | 332 | Sends the visitor's message through send.php, showing it at once and a typing indicator while an AI reply is expected. |
| `poll()` | 381 | Fetches any messages newer than the last one seen from poll.php and adds them; when the conversation has been closed it says so, disables the message box and stops polling. |
| `startPolling()` | 416 | Polls once straight away, then every 3 seconds. |
| `stopPolling()` | 422 | Stops the 3-second poll. |
| `openPanel()` | 430 | Opens the chat panel - straight back into the conversation when there is a token, to the start form when an email is required, otherwise starting a conversation at once. |
| `closePanel()` | 448 | Closes the chat panel, shows the launcher again and stops polling. |

