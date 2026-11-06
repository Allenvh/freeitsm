# api/files/view.php

> API endpoint - 196 lines

The viewer's door to a file's bytes.

POST {action: open, id, version?}  View on the file's folder. Audits a 'view'
       and returns a short-lived token plus what the viewer needs to know: the
       kind of preview, whether this person may download, and the watermark.
  GET  ?t=<token>                    the bytes, for that token only.

WHY A TOKEN AND NOT ?id=. View-only exists (Ed, 2026-10-10): somebody may see a
document without taking a copy. download.php refuses them; this endpoint must
not become a download link by another name. So the bytes are only reachable
through a token that:
  - lives in THIS person's PHP session - a copied URL is useless in any other
    browser, and there is no signing secret to keep anywhere;
  - is for one version of one file, and expires (FILES_VIEW_TTL);
  - is re-checked against the folder's permissions on EVERY request, so taking
    someone's access away stops a viewer that is already open.
That is a deterrent, not DRM, and the help says so: anything a browser can
show, a determined person can capture. The watermark is what makes a leak
traceable.

The response is never something the browser will run as our page: a safe
media type for images, audio and video (so <img>/<video> can use it), and
application/octet-stream as an attachment for everything else, with nosniff
and a sandbox CSP in case anyone opens the URL directly. Range is supported so
video can seek and pdf.js can fetch only the pages it needs.

## The endpoint

| | |
|---|---|
| URL | `api/files/view.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET t` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [filesViewKind()](/code/functions/filesViewKind.md) | 47 | Which viewer draws a file, by extension. Anything else gets "no preview". |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 96 |  | read [files_versions](/code/tables/files_versions.md) |  |
| 143 |  | read [files_versions](/code/tables/files_versions.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesAbsolutePath()](/code/functions/filesAbsolutePath.md), [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesItem()](/code/functions/filesItem.md), [filesNeedItem()](/code/functions/filesNeedItem.md)

