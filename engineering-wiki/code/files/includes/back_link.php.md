# includes/back_link.php

> PHP - 61 lines

The "Back to ..." link (3.5.0, proposed in PR #174): System -> Branding can put a link back to your own site - an intranet, a community website the portal is reached from - at the top of the portal, the analyst screens, or both.

Three system_settings keys, all owned by System -> Branding:
  back_link_label   the words, e.g. "Back to the intranet" (max 60)
  back_link_url     http(s) only - checked on save AND here, so a bad stored
                    value never becomes a javascript: link in every header
  back_link_where   portal | analyst | both
Nothing shows until both the label and the address are set.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [backLinkUrlValid()](/code/functions/backLinkUrlValid.md) | 22 | True when `$url` is a plain http(s) address - the only kind ever rendered. |
| [backLinkSettings()](/code/functions/backLinkSettings.md) | 28 | The three settings, raw - for System -> Branding. |
| [backLinkHtml()](/code/functions/backLinkHtml.md) | 47 | The link's HTML for a header ('portal' or 'analyst'), or '' when it is not set up, not meant for this side, or its address is not a plain http(s) one. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 | `backLinkSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Included by:** [api/system/get_branding.php](/code/files/api/system/get_branding.php.md), [api/system/save_branding.php](/code/files/api/system/save_branding.php.md), [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

