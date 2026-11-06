# htaccessUnguardedPhpDirectives()

Line numbers (1-based) of every php_flag / php_value / php_admin_* directive in an .htaccess that is NOT inside an <IfModule> for mod_php.

🔴 WHY THIS MATTERS (GH #115). Those directives belong to mod_php. On Apache
running PHP through PHP-FPM (proxy_fcgi), mod_php is not loaded, so Apache
does not know the command — and an unknown command in an .htaccess is not
skipped, it is a configuration error that answers EVERY request for every
file in that folder, and every folder below it, with a 500. Every upload
folder used to carry one unguarded `php_flag engine off`, so on PHP-FPM the
logo, the portal logo and LMS course content all failed to load. Wrapped in
<IfModule mod_php.c>, mod_php obeys it and every other server skips it.

This is a line scanner, not an Apache parser: it follows the nesting of
<Container> ... </Container> tags so a directive inside <FilesMatch> inside
<IfModule mod_php.c> still counts as guarded. A negated <IfModule !mod_php.c>
is not a guard — it means the opposite.

```php
function htaccessUnguardedPhpDirectives(string $text): array
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 500.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the whole .htaccess file's contents, any line endings |

**Returns:** int[]

## Called from

- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [htaccessGuardPhpDirectives()](/code/functions/htaccessGuardPhpDirectives.md)

