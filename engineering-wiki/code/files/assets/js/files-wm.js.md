# assets/js/files-wm.js

> JavaScript - 739 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Files Developer Guide](https://github.com/edmozley/freeitsm/wiki/Files-Developer-Guide)

Files — the window manager.

Windows that drag, resize from any edge or corner, snap (top = maximise, left
or right edge = half the screen), minimise to the taskbar and restore. Hover a
taskbar button for a live preview of the window; click the preview to bring it
back. Also the shared pieces every app on the desktop uses: context menus,
dialogs, notifications, the clock, and the navbar's on/auto-hide/off.

Deliberately our own rather than a library (WinBox and friends): the taskbar
and its previews have to know everything about every window, and that
coupling is most of the work.

The apps (Explorer, uploads, permissions...) live in files-desktop.js and only
talk to this file through window.FilesWM.

TRAP: a minimised window is display:none, so it has no layout. The preview
clones it into a box sized from its remembered geometry instead of measuring
it - measuring a hidden element returns zeros and the preview is blank.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `el(tag, cls, html)` | 40 | Creates an element with an optional class and inner HTML. |
| `esc(s)` | 47 | Escapes a value for HTML, treating null as an empty string. |
| `L(key, english, params)` | 53 | Translates a Files string (key under 'files.'), falling back to the English when `tf()` is not loaded. |
| `area()` | 55 | Returns the desktop's rectangle - the space windows live in. |
| `clamp(v, lo, hi)` | 57 | Keeps a number between lo and hi. |
| `Win(opts)` | 60 | ── Windows ──────────────────────────────────────────────────────────── |
| `done()` | 185 | Hides the minimised window once its shrink animation ends and focuses the next one up. |
| `focusTopmost()` | 244 | Focuses the highest window that is not minimised, if there is one. |
| `saveGeo(w)` | 258 | Hands a window's position, size and maximised state to the hook set by `onGeometry()`, to be remembered per kind. |
| `snapZone(ev)` | 263 | ── Drag, snap and resize ────────────────────────────────────────────── |
| `showGhost(zone)` | 271 | Shows the snap outline for 'max', 'left' or 'right', or hides it when there is no zone. |
| `startDrag(w, ev)` | 288 | Starts moving a window by its title bar, with snapping to the top (maximise) or a side (half screen). |
| `move(e)` | 301 | Moves the window with the pointer (after a 4px dead zone) and shows the snap outline for the edge it is at. |
| `up(e)` | 330 | Ends the drag once - snaps or maximises if it ended in a zone, otherwise saves the new position. |
| `startResize(w, ev, dir)` | 362 | Starts resizing a window from an edge or corner handle (not while maximised), keeping the minimum size. |
| `move(e)` | 373 | Resizes the window from the grabbed edge or corner as the pointer moves, and clears any snap. |
| `up()` | 387 | Ends the resize once and saves the window's new geometry. |
| `schedulePreview(w)` | 419 | Shows a window's taskbar preview after a hover delay - short when a preview is already showing. |
| `schedulePreviewHide()` | 425 | Hides the taskbar preview shortly after the pointer leaves, so it can move onto the preview. |
| `hidePreview()` | 430 | Hides and empties the taskbar preview at once. |
| `snapshot(w, boxW, boxH)` | 444 | A live picture of the window: a deep clone, scaled down. Canvases do not clone (a cloned canvas is blank), so each is redrawn from its original; a video becomes a canvas holding its current frame. |
| `showPreview(w)` | 478 | Shows the live preview of a window above its taskbar button, with a close button. |
| `menu(x, y, items, opts)` | 504 | items: [{label, icon?, action?, disabled?, checked?, shortcut?, sub?: [...]}, {sep: true}] opts.above: open upwards from the point (taskbar). |
| `build(container, items)` | 526 | Fills a menu container with items, separators and nested submenus. |
| `closeMenus()` | 549 | Hides and empties the context menu. |
| `dialog(o)` | 571 | A small modal window. Resolves with the clicked button's value, or with {button, value} when there is an input. Escape = the button marked cancel. dialog({title, message, icon?, input?: {value, selectName?: true}, buttons: [{label, value, primary?, cancel?}]}) |
| `finish(v)` | 606 | Closes the dialog and resolves its promise - with the button's value, or {button, value} / {button, checked} when it has an input or a tick box. |
| `onKey(ev)` | 625 | Escape picks the cancel button; Enter picks the primary button (from the input, when there is one). |
| `notify(text, kind)` | 651 | Shows a toast in the corner that fades away - after 6 seconds for an error, 3.2 seconds otherwise. |
| `tick()` | 662 | Redraws the taskbar clock (time and date) in the person's time zone. |
| `layoutTop()` | 680 | Sets --fd-top to the navbar's height when it is always on, so the desktop starts below it; 0 otherwise. |
| `setNavbar(mode)` | 685 | Switches the navbar to 'on', 'auto' (auto-hide) or 'off' and re-lays the desktop out. |
| `open(opts)` | 712 | Opens a new window, or with single: true restores and focuses an existing one of the same kind and key. |
| `windows()` | 722 | Returns a copy of the list of open windows, in taskbar order. |
| `active()` | 724 | Returns the focused window, or null. |
| `onGeometry(fn)` | 733 | Sets the function told whenever a window is moved, resized or maximised, so its geometry can be saved. |

## Loaded by

- [files/index.php](/code/files/files/index.php.md)

## Traps

> [!WARNING]
> **Line 302:** a button released where we never hear about it (over the Help

