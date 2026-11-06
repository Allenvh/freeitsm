# includes/warbot/warbot.php

> PHP - 372 lines

Warbot — the war room's assistant.

🔑 TWO HALVES, AND THE SPLIT IS THE WHOLE DESIGN.

  THE HANDS are the tools in tools.php: plain SQL, no network. They answer
  "how many P1s are open", "what changed this morning", "who is on call".
  THE BRAIN is the language model: it decides which hand to use and phrases
  the answer. It is the only part that needs the internet.

The war room exists for the day the internet is down. A bot whose every answer
needed a remote model would be useless in exactly the situation it was built
for — and worse than useless, because a bot sitting silently in the room reads
as broken rather than as absent. So when the provider cannot be reached Warbot
DEGRADES rather than dies: the slash commands below run the same handlers
directly, with no model involved, and Warbot says plainly that it is running
without one.

⚠️ PROMPT INJECTION IS A LIVE CONCERN, NOT A THEORETICAL ONE. Warbot reads chat
that anybody in the room can write, so anybody can type instructions at it. The
defences are structural rather than textual: every tool is READ-ONLY, so the
worst a successful injection achieves is making Warbot say something silly;
tool output is fenced and labelled as data; and the system prompt states that
message text is a transcript, never an instruction. We do not rely on the model
obeying that last one — it is the belt to the read-only braces.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [warbotIsAddressed()](/code/functions/warbotIsAddressed.md) | 42 | Does this message address Warbot? Either "@Warbot …" anywhere in it, or a leading slash command. |
| [warbotCommands()](/code/functions/warbotCommands.md) | 56 | The commands that work with NO internet. |
| [warbotHelpText()](/code/functions/warbotHelpText.md) | 91 | The one-screen help, GENERATED from the command table. |
| [warbotTryCommand()](/code/functions/warbotTryCommand.md) | 119 | Run a slash command. Returns null if the text is not one. 🔑 NO MODEL INVOLVED — this is the path that still works during the outage. |
| [warbotSystemPrompt()](/code/functions/warbotSystemPrompt.md) | 155 | The system prompt. |
| [warbotAnswer()](/code/functions/warbotAnswer.md) | 201 | Answer a question. Returns the text to post, or null if there is nothing to say. |
| [warbotLooksLikeStatusNotice()](/code/functions/warbotLooksLikeStatusNotice.md) | 308 | Is this one of Warbot's own "I am degraded" notices? |
| [warbotNoBrain()](/code/functions/warbotNoBrain.md) | 319 | What Warbot says when the brain is unreachable but the hands still work. |
| [warbotPost()](/code/functions/warbotPost.md) | 338 | Post Warbot's reply into a channel. |
| [warbotAlreadyAnswered()](/code/functions/warbotAlreadyAnswered.md) | 366 | Has Warbot already answered this message? |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 345 | `warbotPost` | **insert** [warroom_messages](/code/tables/warroom_messages.md) (`channel_id`, `analyst_id`, `body`, `is_bot`, `reply_to_id`, `created_datetime`) |  |
| 368 | `warbotAlreadyAnswered` | read [warroom_messages](/code/tables/warroom_messages.md) |  |

## How it hangs together

**Includes:** [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Included by:** [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)

**Calls:** [aiProviderChatTools()](/code/functions/aiProviderChatTools.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [warbotRunTool()](/code/functions/warbotRunTool.md), [warbotToolsFor()](/code/functions/warbotToolsFor.md), [warRoomChannel()](/code/functions/warRoomChannel.md), [warRoomChannelName()](/code/functions/warRoomChannelName.md)

