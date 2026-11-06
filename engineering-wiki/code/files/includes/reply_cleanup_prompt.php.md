# includes/reply_cleanup_prompt.php

> PHP - 142 lines

Single source of truth for the AI Reply Cleanup system prompt.

Used both by the streaming endpoint at runtime (with real values) and by the
settings page (with placeholder values) so the read-only panel shows
exactly the same scaffold Claude sees, just with a sample greeting + tone.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [replyCleanupToneDescription()](/code/functions/replyCleanupToneDescription.md) | 16 | Map a tone name (Friendly / Formal / Brief) to the short clause inserted into the system prompt. Defaults to Friendly for unrecognised values. |
| [buildReplyCleanupSystemPrompt()](/code/functions/buildReplyCleanupSystemPrompt.md) | 34 | Build the system prompt. `$customInstructions` is appended verbatim under a dedicated heading at the end so any user-supplied rules sit AFTER the safety scaffold rather than overriding it. |

## How it hangs together

**Included by:** [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md), [api/tickets/get_reply_cleanup_settings.php](/code/files/api/tickets/get_reply_cleanup_settings.php.md)

