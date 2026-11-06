# includes/i18n_guarded.php

> PHP - 113 lines

Translation for the screens that must render even when i18n does not.

Most of FreeITSM can require includes/i18n.php outright: if the locale files
are broken the analyst sees a fatal, and an analyst who sees a fatal is an
analyst who is already logged in and can report it. A handful of screens
cannot afford that:

  auth/forgot-password.php       a fatal here and nobody can recover a password
  auth/reset-password.php        the reset email's link leads nowhere
  auth/force_password_change.php the analyst is locked out mid-login
  tickets/csat/survey.php        a CUSTOMER sees it, and it is public

So the load is wrapped and every call site passes the English it replaces.
A missing key, a corrupt locale file or a missing includes/i18n.php all
degrade to exactly what the page showed before it was wired.

auth/login.php deliberately keeps its own copy of this rather than requiring
this file. It is the one screen where a fatal locks every analyst out of the
product, and requiring a second file to survive a missing file is the
dependency the defensive block exists to avoid.

NONE of these four pages has a session with a language preference in it, so
the locale comes from the browser's Accept-Language header. That makes the
response vary by a request header, which a proxy or CDN in front of FreeITSM
would otherwise be entitled to ignore - hence the Vary header below.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [i18nGuardedInit()](/code/functions/i18nGuardedInit.md) | 38 | Load i18n behind a guard and fix the namespace prefix `tr()` will use. |
| [tr()](/code/functions/tr.md) | 64 | Translate, with the English as the last line of defence. |
| [trh()](/code/functions/trh.md) | 87 | `tr()` escaped for HTML - the normal case for anything going into the page. |
| [trj()](/code/functions/trj.md) | 99 | `tr()` as a JavaScript literal, for the inline scripts these pages carry. |
| [trLocale()](/code/functions/trLocale.md) | 105 | The locale for <html lang="...">, or 'en' when i18n did not load. |

## How it hangs together

**Includes:** [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/system_name.php](/code/files/includes/system_name.php.md)

**Included by:** [lms/test.php](/code/files/lms/test.php.md), [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md)

