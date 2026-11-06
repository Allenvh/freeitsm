# api/system/photo_album.php

> API endpoint - 179 lines

API: System -> Photo Album.

GET  ?action=list                 my pictures, newest first (thumbnails, no art)
  GET  ?action=get&id=N             one of my pictures, with its art and colours
  POST {action:'save', title, style, palette, width_chars, height_chars, art, colours?, thumbnail}
  POST {action:'delete', id}

The webcam photo is turned into ASCII art in the browser and NEVER comes here:
only the art (text), its colours for the Colour palette, and a small JPEG of
the art for the album grid. Everything is personal - an analyst sees, opens
and deletes only their own, so every query is scoped by analyst_id.

UI-only, with no REST API twin, so there is no duplication for a service
class to remove (Service-Layer-Architecture, "Watch out for #9").

## The endpoint

| | |
|---|---|
| URL | `api/system/photo_album.php` |
| Protected by | Module access: system (requireModuleAccessJson) |
| Reads | `GET action`, `GET id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [paFail()](/code/functions/paFail.md) | 41 | Sends an HTTP status (200 by default) and {success: false, error} as JSON, then stops. |
| [paStylePattern()](/code/functions/paStylePattern.md) | 53 | Every character of a style's art must be one that style can produce. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 |  | read [photo_album](/code/tables/photo_album.md) |  |
| 97 |  | read [photo_album](/code/tables/photo_album.md) |  |
| 158 |  | **insert** [photo_album](/code/tables/photo_album.md) (`analyst_id`, `title`, `style`, `palette`, `width_chars`, `height_chars`, `art`, `colours`, `thumbnail`, `created_datetime`) |  |
| 167 |  | **delete** [photo_album](/code/tables/photo_album.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

