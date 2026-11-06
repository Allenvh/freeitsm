# api/forms/get_collection_submissions.php

> API endpoint - 50 lines

API: every submission in a collection, across its forms, plus the field definitions of each form involved.

A collection's rows do not share a set of questions, so the detail panel and
the PDF are handed the right form for each submission rather than assuming
one form for the page.

## The endpoint

| | |
|---|---|
| URL | `api/forms/get_collection_submissions.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [form_collections](/code/tables/form_collections.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

