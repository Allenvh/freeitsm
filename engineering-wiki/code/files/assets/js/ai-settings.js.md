# assets/js/ai-settings.js

> JavaScript - 301 lines

Drives the reusable AI settings panel (includes/ai_settings_panel.php).

Auto-initialises every [data-ai-panel] on the page: hydrates from
api/system/ai/get_settings.php, swaps the model suggestions when the
provider changes (curated list for Anthropic/OpenAI, live OpenRouter
catalogue with pricing for OpenRouter), and saves/tests via the shared
endpoints. No per-module code — the namespace + endpoints come from
data-attributes on the panel element.

The model picker is a self-contained dropdown (NOT a native <datalist>):
a long OpenRouter list rendered via <datalist> made the browser scroll the
overflow:hidden page body when focused, pushing the header off-screen. This
custom menu is absolutely positioned inside the panel with its own capped
scroll, so it can never move the page.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `tr(key, fallback)` | 24 | Translates a key under common.ai, falling back to the given English when there is no translation. |
| `toast(msg, type)` | 35 | Shows a toast when the page has the shared toast helper. |
| `esc(s)` | 40 | Escapes text for safe use inside HTML. |
| `fmtPrice(perToken)` | 50 | Turns an OpenRouter per-token price into dollars per million tokens, such as "$3.00" - null when there is no price. |
| `initPanel(panel)` | 61 | Wires one AI settings panel: the provider switch, the model dropdown, the Azure fields, Save and Test, then fills it from the saved settings for its namespace. |
| `setResult(text, cls)` | 90 | Shows a result line under the panel's buttons, styled by cls (for example 'err'). |
| `renderMenu()` | 100 | Fills the model dropdown with up to 50 suggestions matching what has been typed, with prices for OpenRouter models. |
| `showMenu()` | 117 | Opens the model dropdown. |
| `hideMenu()` | 119 | Closes the model dropdown. |
| `setCuratedModels(provider)` | 135 | Uses the built-in model list for a provider as the dropdown's suggestions. |
| `loadOpenRouterModels()` | 144 | Fetches the live OpenRouter model catalogue with prices and uses it as the dropdown's suggestions. |
| `applyProviderUi(provider, opts)` | 167 | Shows the fields a provider needs - the Azure boxes instead of the model for Azure OpenAI, the OpenRouter note and live catalogue for OpenRouter - and resets the model to that provider's default unless opts.keepModel. |

## Loaded by

- [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md)
- [forms/settings/index.php](/code/files/forms/settings/index.php.md)
- [knowledge/settings/index.php](/code/files/knowledge/settings/index.php.md)
- [projects/settings/index.php](/code/files/projects/settings/index.php.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)
- [war-room/settings/index.php](/code/files/war-room/settings/index.php.md)
- [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

