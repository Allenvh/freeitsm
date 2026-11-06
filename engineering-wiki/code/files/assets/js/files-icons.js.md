# assets/js/files-icons.js

> JavaScript - 128 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Files Developer Guide](https://github.com/edmozley/freeitsm/wiki/Files-Developer-Guide)

Files — the icon set. Our own drawings, not Microsoft's: a manila folder, a page with a coloured band saying what kind of file it is, and the desktop app icons. Everything is inline SVG so it is crisp at any size and needs no image requests.

FilesIcons.folder(px)            a folder
  FilesIcons.file(name, px)        a page for that file name's type
  FilesIcons.app(key, px)          desktop / start menu icons
  FilesIcons.type(name)            {key, label, colour} for a file name

## Functions

| Function | Line | What it does |
|---|--:|---|
| `esc(s)` | 38 | Escapes &, <, > and " so a label can go inside the SVG markup. |
| `type(name)` | 46 | Works out a file's kind from its extension - the key, the extension, the band label and colour. |
| `folder(px)` | 60 | Returns the manila folder icon as inline SVG (48px unless a size is given). |
| `file(name, px)` | 75 | Returns a page icon for a file name, with a band in its kind's colour. |
| `app(key, px)` | 118 | Returns a desktop or start menu icon - a rounded coloured tile with a white glyph. |

## Loaded by

- [files/index.php](/code/files/files/index.php.md)

