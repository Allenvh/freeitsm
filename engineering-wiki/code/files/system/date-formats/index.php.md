# system/date-formats/index.php

> PHP - 298 lines

System - Date and time formats (GH #105)

The install-wide DEFAULT date and time format. Analysts who want something
else set their own in System > Preferences; this is what everyone who has not
chosen sees, and - because portal users are not analysts and have no
preferences - it is the only setting the self-service portal reads.

Format is deliberately NOT tied to the interface language: inside one country
people disagree about 25/08/2026 vs 25.08.2026 vs 25 Aug 2026. It is also
separate from the timezone, which decides WHICH INSTANT is shown rather than
how it is written down.

The current values are rendered SERVER-SIDE into the checked radio, not
fetched after load. A page that loads its state over the wire shows a
plausible-looking default when the fetch fails, and Save then writes that
guess back as fact - the exact trap documented in system/security/index.php.
Rendering server-side means the control cannot disagree with the database.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

