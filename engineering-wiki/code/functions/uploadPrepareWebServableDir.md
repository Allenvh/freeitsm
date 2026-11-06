# uploadPrepareWebServableDir()

Prepare a directory whose contents the BROWSER IS MEANT TO FETCH.

⚠️ Read this before using it. `uploadPrepareDir()` above refuses everything, which
is right for attachments — they are read by get_attachment.php, never fetched. It
is wrong for a directory whose whole job is to be fetched directly, and the
earlier round left those alone rather than guess. This is the policy for
PASSIVE-CONTENT directories:

  system/uploads/branding   the logo, rendered as <img src> on every page  ✅

⚠️ NOT SUITABLE FOR lms/content, and that is a deliberate exclusion rather than
an oversight. SCORM packages ARE HTML and JavaScript that has to run for the
course to play at all, so the `sandbox` directive below would not harden the LMS,
it would break it. Hardening that directory needs a different answer — most
likely serving packages through a PHP endpoint on a separate origin — and it is a
design job, not a one-line policy. It stays on the outstanding list.

⚠️ WHAT EACH LAYER ACTUALLY BUYS, because they are not equal and it would be easy
to read this function as stronger than it is:

  1. The upload whitelist (UPLOAD_TYPES_IMAGE, no SVG) — the real defence. It
     works on every server and needs no configuration, and it is why nothing
     script-bearing gets in here in the first place.
  2. No-execute rules — verified working: a .php placed in the branding folder is
     refused 403 by Apache and is not run.
  3. The Content-Security-Policy headers — DEFENCE IN DEPTH ONLY, AND CONDITIONAL.
     They sit inside <IfModule mod_headers.c>, so on a server without mod_headers
     (including a stock WAMP, where this was tested) they are silently absent.
     Confirmed by fetching a stored logo and reading the response headers: only
     Content-Type came back.

The practical consequence is worth stating plainly rather than leaving implied: a
legacy `logo.svg` uploaded before this change is still servable, and on a server
without mod_headers it is still script-capable if someone navigates straight to
it. Layer 1 stops any NEW one arriving; it cannot retroactively make an old one
safe. Removing an existing SVG logo is an operator action, and it is called out
in the release notes rather than hidden in a code comment.

```php
function uploadPrepareWebServableDir(string $dir): void
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 369.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dir` | `string` |  | absolute path of the directory; created (with parents) if missing |

**Returns:** `void`

## Calls

[uploadRepairHtaccessFile()](/code/functions/uploadRepairHtaccessFile.md)

## Called from

- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [api/system/self_service_portal.php](/code/files/api/system/self_service_portal.php.md)
- [api/system/tenant_logo.php](/code/files/api/system/tenant_logo.php.md)

