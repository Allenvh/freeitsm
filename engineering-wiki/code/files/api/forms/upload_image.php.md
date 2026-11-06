# api/forms/upload_image.php

> API endpoint - 93 lines

API: store an image for a form's image block. POST multipart: form_id, image

🔒 Every byte goes through `uploadStoreFile()`, which is the ONE place the
upload rules live: an extension AND mime whitelist, a random stored filename
drawn from our own list rather than the caller's string, and an .htaccess in
the destination that disables execution. None of the three is trusted alone.

🔴 UPLOAD_TYPES_IMAGE, not UPLOAD_TYPES_ATTACHMENT. The default whitelist is
the ceiling for a ticket attachment and includes documents and archives; an
image block can only ever be an image. Narrower is the right default and the
constant is shared, so SVG stays excluded — it is XML, it can carry <script>,
and a browser will run it if the file is ever served inline. Branding accepts
one because an administrator uploads the logo; nothing a form shows to a
customer should.

🔑 Writing is analyst-only: building a form is not something a portal user
does. READING is a different and wider question — see image.php.

## The endpoint

| | |
|---|---|
| URL | `api/forms/upload_image.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `POST form_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 |  | read [forms](/code/tables/forms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

