# system/ai/index.php

> PHP - 293 lines

System — AI thinking.

One switch per AI feature: may this feature use a model's EXTENDED THINKING?

─────────────────────────────────────────────────────────────────────────────
🔑 WHY THIS EXISTS, AND WHY IT DEFAULTS TO OFF

A reasoning model spends its output budget thinking before it writes a single
character. Measured here, summarising a two-message ticket on
qwen/qwen3.7-plus through OpenRouter:

    thinking on   54.7s   2,927 reasoning tokens   1,020 characters of answer
    thinking off   6.9s           0 tokens         1,376 characters of answer

Eight times faster, and the FAST answer was the better one — it caught details
the slow one missed. That is not a fluke of one prompt: every AI feature in
FreeITSM summarises, extracts or drafts from text that is already in front of
it. None of them is a puzzle, which is what extended thinking is for.

So thinking is off unless somebody asks for it, per feature — and a feature
whose job turns out to reward deliberation can have it back with one click.

⚠️ WHAT IT DOES NOT DO. The switch is sent to OpenRouter and nowhere else,
because `reasoning` is OpenRouter's own parameter and OpenAI's API rejects an
unknown field outright. A feature configured against Anthropic or OpenAI is
listed here with its provider named and the switch marked as having no effect,
rather than being hidden — because "why is this one not here?" is a worse
question than "why is this one greyed out?".

THE LIST IS THE REGISTRY. Rows come from `aiSettingsRegistry()`, so a tenth AI
feature appears here the moment it is registered, with nothing to remember.
The same is true of the setting keys: includes/settings_keys.php generates
<ns>_reasoning from the same registry, so a new feature is writable here
without a second edit.

State is rendered SERVER-SIDE into the checkboxes rather than fetched after
load. A page that loads its state over the wire shows a plausible-looking
default when the fetch fails, and Save then writes that guess back as fact —
the same trap documented on the Security and Date format pages.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

