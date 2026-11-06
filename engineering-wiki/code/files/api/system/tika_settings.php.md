# api/system/tika_settings.php

> API endpoint - 131 lines

API: read, save and test the Apache Tika connection (discussion #53, tier 2).

Three actions on one endpoint, because they are one screen's worth of
behaviour: `get`, `save`, `test`.

⚠️ There are no credentials here, which is unusual for an integration and is
the whole reason Tika must not be exposed to a network. It has no
authentication of any kind: anything that can reach it will parse whatever
bytes it is sent. The screen says so; this endpoint stores a URL and nothing
more, so there is no secret to encrypt.

## The endpoint

| | |
|---|---|
| URL | `api/system/tika_settings.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET action` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [tikaSettingPut()](/code/functions/tikaSettingPut.md) | 29 | Write one system_settings row. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 | `tikaSettingPut` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 97 |  | read [attachment_text](/code/tables/attachment_text.md), read [email_attachments](/code/tables/email_attachments.md) |  |
| 110 |  | **update** [attachment_text](/code/tables/attachment_text.md) (`status`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/search/tika.php](/code/files/includes/search/tika.php.md), [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [extractQueueDepth()](/code/functions/extractQueueDepth.md), [tikaHandles()](/code/functions/tikaHandles.md), [tikaPing()](/code/functions/tikaPing.md), [tikaTimeout()](/code/functions/tikaTimeout.md), [tikaUrl()](/code/functions/tikaUrl.md)

