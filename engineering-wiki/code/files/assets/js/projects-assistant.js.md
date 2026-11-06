# assets/js/projects-assistant.js

> JavaScript - 208 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 7 Alerts Reports and AI](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-7-Alerts-Reports-and-AI)

Projects - the AI project assistant panel (3.3.0): "Ask AI" on a project.

The same slide-in panel as Knowledge's Ask AI (the .ai-chat-* styles in
inbox.css / mobile.css), talking to api/projects/assistant_chat.php. The rules,
the memory and what the model is told are in includes/projects/assistant_chat.php.

  PrjAssistant.open()    the header's Ask AI button
  PrjAssistant.bind(ctx) from projects-view.js after each redraw - ctx.refresh redraws
                         the project once proposals are applied

Opening the panel loads the conversation, then asks the server to 'open' it:
a greeting for an empty conversation, a "since we last spoke" after a while,
or nothing. An assistant message with proposals gets a card: a box per change
(all ticked), Apply and Dismiss; each line then shows applied / dismissed / why it failed.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 23 | Translates a key under projects.assistant.*. |
| `$(id)` | 28 | Shorthand for document.getElementById. |
| `md(s)` | 30 | Renders an assistant reply's Markdown through the reports renderer, or escapes it with line breaks when that is not loaded. |
| `call(body)` | 32 | Posts an action to assistant_chat.php for the bound project. |
| `aiError(r)` | 35 | Shows a not-configured or unreachable note when the reply carries an AI error; returns true when it did. |
| `note(text, bad)` | 41 | Adds a plain or error note to the bottom of the conversation. |
| `chips()` | 51 | The suggested-question chips for the project's maturity stage - blank, early, finished or in flight. |
| `proposalCard(msg)` | 61 | The card under an assistant message that proposes changes - a tick box per pending change, the state of the rest, and Apply / Dismiss for analysts who can change the project. |
| `draw()` | 74 | Redraws the conversation - not-ready and not-configured states, messages grouped by day, proposal cards, the thinking dots, the chips and the memory/shared notes. |
| `load()` | 107 | Loads the project's conversation state and redraws. |
| `openTurn()` | 113 | Asks the server to open the conversation, which may add a greeting or a "since we last spoke" message. |
| `send(text)` | 124 | Sends the typed (or chip) question, showing it straight away, and replaces the messages with the server's reply. |
| `act(card, dismiss)` | 139 | Applies the ticked changes on a proposal card, or dismisses them all, then reports how many worked and redraws the project when any did. |
| `clear()` | 161 | Asks, then clears the conversation and its memory and opens a fresh one. |
| `open()` | 168 | Opens the Ask AI panel, loading and opening the conversation the first time. |
| `close()` | 177 | Closes the Ask AI panel. |
| `wire()` | 183 | Wires the panel's close, send, Enter key, clear, chip, Apply and Dismiss controls, and Escape to close. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

