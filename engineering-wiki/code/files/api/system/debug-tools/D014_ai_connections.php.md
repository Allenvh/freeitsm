# api/system/debug-tools/D014_ai_connections.php

> API endpoint - 322 lines

Debug Tool D014 — AI connections: what is sent and what comes back

Answers one question end to end: "an AI feature's Test button fails — what
exactly did FreeITSM send, and what exactly did the provider say?"

Began as the Azure OpenAI tool (discussion #86). GH #171 widened it to every
provider, because the question asked there - "is there a way to inspect the
payload and response without dev tools?" - has the same answer for all of
them: NO, not in the browser. The call is made by the SERVER; the browser only
ever talks to FreeITSM, which is why dev tools show no api-key header. This
tool records the server's own exchange (aiProviderTraceStart) and prints it.
The id stays D014 so links already given out in #86 still land here.

READ-ONLY apart from the live calls, which spend a few tokens exactly as the
Test button does. For a failing Azure deployment it may make up to four more,
one per alternative API version, stopping at the first that works.

⚠️ PRINTS NO SECRETS. The key is shown as its first four characters and a
length wherever it would appear - headers, address, body or answer.

Input: ns (optional) - one feature. Without it, every feature with a key is
tested, one call per distinct setup (features sharing a key and model are
one connection, so they are called once).

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D014_ai_connections.php` |
| Protected by | Signed-in analyst (session check) |
| Reads | `GET ns` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 63 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 73 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |
| [keyHint()](/code/functions/keyHint.md) | 83 | First four characters and a length — enough to tell a wrong key from no key. |
| [redactKey()](/code/functions/redactKey.md) | 93 | Replace the key wherever it appears with its hint. |
| [prettyBody()](/code/functions/prettyBody.md) | 102 | JSON pretty-printed for reading; anything else as it came, trimmed to a length. |
| [indent()](/code/functions/indent.md) | 114 | Indents every line of a block of text by `$pad`. |
| [traceLines()](/code/functions/traceLines.md) | 124 | Print one recorded HTTP exchange. |
| [setupKey()](/code/functions/setupKey.md) | 146 | The address and key of a setup, so features sharing one are called once. |
| [providerLabel()](/code/functions/providerLabel.md) | 157 | The display name of an AI provider key (anthropic, openai, openrouter, azure), or the key itself when it is not one of those. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Calls:** [aiAzureApiVersionLooksValid()](/code/functions/aiAzureApiVersionLooksValid.md), [aiExplainFailure()](/code/functions/aiExplainFailure.md), [aiProviderChat()](/code/functions/aiProviderChat.md), [aiProviderTraceStart()](/code/functions/aiProviderTraceStart.md), [aiProviderTraceTake()](/code/functions/aiProviderTraceTake.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md), [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

