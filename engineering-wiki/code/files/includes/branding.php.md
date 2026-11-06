# includes/branding.php

> PHP - 429 lines

Branding — the organisation's logo, and the login screen designer.

TWO JOBS, ONE FILE, because they answer the same question from different
angles: "what does this install look like to somebody who has not logged in?"

─────────────────────────────────────────────────────────────────────────────
 1. `brandingLogoUrl()`  — the logo, wherever it is shown
─────────────────────────────────────────────────────────────────────────────
Branding has been able to store a logo for a long time, and almost nothing
read it: `assets/images/CompanyLogo.png` was hardcoded in NINE places and
exactly one page (the asset handover) looked the setting up. So an
administrator uploaded a logo, saw it saved, and it appeared essentially
nowhere (GH #87). One function now answers it for every caller.

─────────────────────────────────────────────────────────────────────────────
 2. The login screen designer
─────────────────────────────────────────────────────────────────────────────
🔴 THE SECURITY RULE THIS FILE EXISTS TO ENFORCE:

     THE ADMINISTRATOR SUPPLIES VALUES, NEVER SYNTAX.

The login page is the one page an attacker can view anonymously, and the one
page where every user types a password. Stored XSS there is the worst kind
there is: it runs unauthenticated, for everybody, on the credential form. So
nothing an administrator types is ever treated as HTML or as CSS.

Every setting below declares a TYPE and its permitted values, and
`brandingLoginDesign()` refuses anything else — a colour is `#rrggbb` or it is
the default, a position is one of a fixed list or it is the default, a number
is clamped, and text is plain text that the page escapes on the way out. The
page turns those values into CSS; the administrator never writes any.

⚠️ Validation happens at RENDER as well as at save. Saving is guarded, but a
value that reached the row by some other route — a restored backup, a direct
UPDATE, an injection elsewhere — still cannot reach the page. This mirrors
includes/landing.php, which took the same position for the same reason: "the
stored value is a KEY, never a path… anything unrecognised falls back to the
default". That is the doctrine for anything the front door reads.

⚠️ And the line NOT to cross: no "custom CSS" and no "custom HTML" field,
however often it is asked for. Every guarantee above is void the moment one
exists. If something cannot be expressed, the answer is another structured
control, not an escape hatch.

🔑 LOCKOUT IS A REAL HAZARD. White text on a white background, or a logo
scaled over the form, and nobody can sign in to undo it — including the
administrator who did it. `login.php?nobranding=1` always renders the stock
screen. It is not a secret and it is not a bypass: it changes appearance
only, and is documented next to the settings.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [brandingLogoUrl()](/code/functions/brandingLogoUrl.md) | 69 | The organisation's logo as a web URL, with the bundled one as the fallback. |
| [brandingPathIsSafe()](/code/functions/brandingPathIsSafe.md) | 99 | A stored upload pointer is only ever a file inside the branding directory. |
| [brandingScopes()](/code/functions/brandingScopes.md) | 118 | THE THREE SCREENS, and what each one has to style. |
| [brandingScopeValid()](/code/functions/brandingScopeValid.md) | 137 | Returns the scope when it is one of the branded screens (login, portal, home), otherwise 'login'. |
| [brandingLoginFields()](/code/functions/brandingLoginFields.md) | 158 | THE VALIDATION TABLE — the single source of truth for the login designer. |
| [brandingLoginKey()](/code/functions/brandingLoginKey.md) | 239 | `form_position` → the settings key it is stored under, for this screen. |
| [brandingLoginValidate()](/code/functions/brandingLoginValidate.md) | 252 | One value, validated against its declared type. Anything that does not fit becomes the default — never an error, because this runs on the login page. |
| [brandingLoginDesign()](/code/functions/brandingLoginDesign.md) | 292 | The whole design, validated. Safe to call on the login page: it swallows any database failure and returns the defaults. |
| [brandingLoginCss()](/code/functions/brandingLoginCss.md) | 330 | The design as CSS custom properties. |
| [brandingLoginPresets()](/code/functions/brandingLoginPresets.md) | 393 | One-click starting points. |
| [brandingContrastRatio()](/code/functions/brandingContrastRatio.md) | 415 | Is this pair of colours legible together? WCAG relative-luminance contrast. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `brandingLogoUrl` | read [system_settings](/code/tables/system_settings.md) |  |
| 306 | `brandingLoginDesign` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md), [api/system/save_branding.php](/code/files/api/system/save_branding.php.md), [forms/collection.php](/code/files/forms/collection.php.md), [forms/edit/index.php](/code/files/forms/edit/index.php.md), [forms/fill.php](/code/files/forms/fill.php.md), [forms/settings/index.php](/code/files/forms/settings/index.php.md), [forms/submissions.php](/code/files/forms/submissions.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), [includes/files/storage.php](/code/files/includes/files/storage.php.md), [index.php](/code/files/index.php.md), [morning-checks/index.php](/code/files/morning-checks/index.php.md), [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md), [self-service/forgot-password.php](/code/files/self-service/forgot-password.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/login.php](/code/files/self-service/login.php.md), [self-service/register.php](/code/files/self-service/register.php.md), [self-service/reset-password.php](/code/files/self-service/reset-password.php.md), [system/branding/index.php](/code/files/system/branding/index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

