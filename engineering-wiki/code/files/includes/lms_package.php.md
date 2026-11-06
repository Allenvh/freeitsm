# includes/lms_package.php

> PHP - 192 lines

Safe extraction of an uploaded SCORM package.

WHY THIS EXISTS
---------------
A SCORM package is a zip of static files, and it is extracted into
lms/content/<id>/ — a directory INSIDE the webroot, which Apache serves and
will happily execute PHP from. ZipArchive::extractTo() writes whatever the zip
contains, wherever the zip says. Left unguarded that gives anyone with the LMS
module three separate ways to own the server:

  1. Ship a `.php` file in the package. It lands in a web-served directory and
     runs as the web server — reading config.php, the database, the AES key,
     every stored mailbox credential. LMS access becomes root access.
  2. Name an entry `../../../includes/functions.php` ("zip-slip") and overwrite
     any file in the app, escaping the content directory entirely.
  3. Ship a zip bomb — a few KB that expands to fill the disk.

So we never hand the archive to `extractTo()`. Every entry is inspected first,
against an allowlist of things a course can legitimately be made of, and the
whole upload is refused if any single entry fails. Refusing the package
outright (rather than skipping the bad file) is deliberate: a SCORM package
with a shell in it is not a course with a problem, it is an attack.

Defence in depth: `lmsHardenContentDir()` also drops an .htaccess that disables
PHP execution for the content tree, so even a future hole in the checks above
does not become code execution. `.htaccess` is itself on the deny list, so a
package cannot ship one to undo this.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [lmsValidatePackage()](/code/functions/lmsValidatePackage.md) | 66 | Check every entry in the archive. Returns the entries that should be written (directories excluded), or throws with a message naming the offending file. |
| [lmsExtractPackage()](/code/functions/lmsExtractPackage.md) | 139 | Extract only the entries that passed validation, one at a time. |
| [lmsHardenContentDir()](/code/functions/lmsHardenContentDir.md) | 156 | Stop the content tree ever executing code, whatever ends up in it. |

## How it hangs together

**Includes:** [includes/uploads.php](/code/files/includes/uploads.php.md)

**Included by:** [api/lms/courses.php](/code/files/api/lms/courses.php.md)

**Calls:** [uploadRepairHtaccessFile()](/code/functions/uploadRepairHtaccessFile.md)

