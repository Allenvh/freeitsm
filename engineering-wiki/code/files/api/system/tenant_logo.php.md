# api/system/tenant_logo.php

> API endpoint - 67 lines

API Endpoint: a company's own logo (System -> Companies).

POST multipart {tenant_id, logo}   upload / replace
  POST multipart {tenant_id, remove: 1}

Shown in the corner of the Files desktop for people working in that company
(System -> Branding -> Files desktop decides whether a logo shows at all),
falling back to the organisation logo when a company has none.

Stored in system/uploads/branding/companies/ under OUR random name, like the
organisation logo: a web-servable folder, because the desktop shows it by URL,
with execution blocked (uploadPrepareWebServableDir). Raster images only - SVG
can carry script, and this file is served from our own origin.

## The endpoint

| | |
|---|---|
| URL | `api/system/tenant_logo.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `POST tenant_id`, `POST remove` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [tenants](/code/tables/tenants.md) |  |
| 51 |  | **update** [tenants](/code/tables/tenants.md) (`logo_path`) |  |
| 62 |  | **update** [tenants](/code/tables/tenants.md) (`logo_path`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [uploadPrepareWebServableDir()](/code/functions/uploadPrepareWebServableDir.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

