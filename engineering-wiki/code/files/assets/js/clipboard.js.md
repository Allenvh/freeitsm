# assets/js/clipboard.js

> JavaScript - 93 lines

Copying text to the clipboard, in the one place.

⚠️ THE BUG THIS EXISTS TO FIX. Twelve places called
`navigator.clipboard.writeText(x).then(...).catch(...)` directly, and ten of
them did not check that `navigator.clipboard` was there at all. It is absent
outside a secure context — plain HTTP, or HTTPS with a certificate the phone
does not trust, which is exactly the situation of a self-hosted server on an
internal name. Reading `.writeText` of `undefined` throws SYNCHRONOUSLY, so
the `.catch()` never runs: the fallback never fired, no message appeared, and
nothing was copied. Ed hit it sharing a Knowledge link from an iPhone.

Two further things this gets right that the old inline copies did not:

 1. iOS Safari ignores `input.select()`. Selecting text there needs a Range
    over the node plus `setSelectionRange`, and the element must be a real,
    rendered, readonly field — not `display: none`, not zero-opacity-with-no-
    size. A 16px font stops iOS zooming the page while it is focused.

 2. IT NEVER CLAIMS SUCCESS IT DID NOT HAVE. The old fallback ran
    `execCommand('copy')`, ignored the false it can return, and showed
    "Copied!" regardless. Being told something is on your clipboard when it
    is not is worse than being told the copy failed, because you only find
    out when you paste somewhere else and lose whatever you were doing.

Resolves to true only if the text really was copied.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `legacyCopy(text)` | 36 | Copies text with execCommand('copy') through a hidden-but-rendered textarea, then restores the page's selection. |
| `copyToClipboard(text)` | 77 | Copies text to the clipboard - the Clipboard API where the page is a secure context, otherwise (or if it is refused) the execCommand fallback. |

## Loaded by

- [forms/approvals.php](/code/files/forms/approvals.php.md)

