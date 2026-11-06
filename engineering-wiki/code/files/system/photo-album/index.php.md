# system/photo-album/index.php

> PHP - 814 lines

System — Photo Album. Just for fun.

Takes a picture with the webcam and turns it into ASCII art, live, as you
move. Three styles: Braille (each character is a 2x4 grid of dots, so eight
times the detail of ordinary ASCII), Classic (a 70-step character ramp) and
Blocks (shade characters). Four screens: green, amber, paper, and colour.

🔑 No AI and no server work: the conversion is plain arithmetic in the
browser (brightness -> character, with error-diffusion dithering). The PHOTO
NEVER LEAVES THE BROWSER - only the finished art is sent, and only on Save.
Each analyst's album is their own (api/system/photo_album.php).

The camera needs a secure context (https, or localhost). Without one the
page says so and Upload still works.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/confirm.js](/code/files/assets/js/confirm.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

