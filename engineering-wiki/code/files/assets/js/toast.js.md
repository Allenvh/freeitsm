# assets/js/toast.js

> JavaScript - 136 lines

Global Toast Notification System

Position + animation are per-analyst preferences stored in
user_preferences (keys: toast_position, toast_animation). They're
pushed onto window.TOAST_POSITION / window.TOAST_ANIMATION by the
shared waffle-menu.php at page-render time so toast.js doesn't have
to AJAX for them. localStorage stays as a fallback for any pre-#432
browsers that still have the values cached locally.

Usage: showToast('Message text', 'success') — types: success, error, warning, info

## Functions

| Function | Line | What it does |
|---|--:|---|
| `getPosition()` | 66 | Returns the toast corner - window.TOAST_POSITION, then the saved localStorage choice, then 'bottom-right'. |
| `getAnimation()` | 71 | Returns the toast animation style - window.TOAST_ANIMATION, then the saved localStorage choice, then 'slide'. |
| `getContainer()` | 76 | Returns the toast container, rebuilding it when the position or animation setting has changed since it was made. |
| `showToast(message, type, ms)` | 95 | `ms` is optional and defaults to the usual 4 seconds, so every existing two-argument call behaves exactly as before. It exists because some messages are not acknowledgements but instructions — "the address book has a different number, import again to see theirs" cannot be read, understood and acted on inside four seconds, and a toast that vanishes mid-sentence is the same as not having shown it. |
| `onclick()` | 115 | Removes the toast at once when its close button is clicked. |

## Loaded by

- [asset-management/manager-access.php](/code/files/asset-management/manager-access.php.md)
- [asset-management/users.php](/code/files/asset-management/users.php.md)
- [index.php](/code/files/index.php.md)
- [system/ai/index.php](/code/files/system/ai/index.php.md)
- [system/cost-centres/index.php](/code/files/system/cost-centres/index.php.md)
- [system/feature-bingo/index.php](/code/files/system/feature-bingo/index.php.md)
- [system/managers/index.php](/code/files/system/managers/index.php.md)
- [system/photo-album/index.php](/code/files/system/photo-album/index.php.md)
- [system/roles/index.php](/code/files/system/roles/index.php.md)
- [system/self-service/index.php](/code/files/system/self-service/index.php.md)
- [system/sso/carddav.php](/code/files/system/sso/carddav.php.md)
- [system/sso/provider.php](/code/files/system/sso/provider.php.md)
- [tickets/manager-access.php](/code/files/tickets/manager-access.php.md)

