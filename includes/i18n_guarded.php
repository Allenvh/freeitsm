<?php
/**
 * Translation for the screens that must render even when i18n does not.
 *
 * Most of FreeITSM can require includes/i18n.php outright: if the locale files
 * are broken the analyst sees a fatal, and an analyst who sees a fatal is an
 * analyst who is already logged in and can report it. A handful of screens
 * cannot afford that:
 *
 *   auth/forgot-password.php       a fatal here and nobody can recover a password
 *   auth/reset-password.php        the reset email's link leads nowhere
 *   auth/force_password_change.php the analyst is locked out mid-login
 *   tickets/csat/survey.php        a CUSTOMER sees it, and it is public
 *
 * So the load is wrapped and every call site passes the English it replaces.
 * A missing key, a corrupt locale file or a missing includes/i18n.php all
 * degrade to exactly what the page showed before it was wired.
 *
 * auth/login.php deliberately keeps its own copy of this rather than requiring
 * this file. It is the one screen where a fatal locks every analyst out of the
 * product, and requiring a second file to survive a missing file is the
 * dependency the defensive block exists to avoid.
 *
 * NONE of these four pages has a session with a language preference in it, so
 * the locale comes from the browser's Accept-Language header. That makes the
 * response vary by a request header, which a proxy or CDN in front of FreeITSM
 * would otherwise be entitled to ignore - hence the Vary header below.
 */

$GLOBALS['i18nGuardedReady']  = false;
$GLOBALS['i18nGuardedPrefix'] = '';

/**
 * Load i18n behind a guard and fix the namespace prefix tr() will use.
 *
 * @param string $prefix e.g. 'auth' or 'tickets.csat.survey' - no trailing dot.
 */
function i18nGuardedInit(string $prefix): void
{
    $GLOBALS['i18nGuardedPrefix'] = $prefix;
    try {
        if (is_file(__DIR__ . '/i18n.php')) {
            require_once __DIR__ . '/i18n.php';
            I18n::initFromSession();
            $GLOBALS['i18nGuardedReady'] = class_exists('I18n');
            if (!headers_sent()) header('Vary: Accept-Language');
        }
    } catch (Throwable) {
        $GLOBALS['i18nGuardedReady'] = false;   // English it is
    }
    // pageTitle() for the <title> (GH #173). Loaded on its own, outside the try:
    // i18n.php normally brings it, but these pages must render when it cannot.
    // Safe without a database - systemName() falls back to "Service Desk".
    require_once __DIR__ . '/system_name.php';
}

/**
 * Translate, with the English as the last line of defence.
 *
 * @param string $key     key within the prefix passed to i18nGuardedInit()
 * @param string $english the exact text this call replaces
 * @param array  $params  {placeholder} => value, applied to whichever wins
 */
function tr(string $key, string $english, array $params = []): string
{
    $full = $GLOBALS['i18nGuardedPrefix'] . '.' . $key;
    if ($GLOBALS['i18nGuardedReady']) {
        try {
            $out = I18n::t($full, $params);
            // I18n::t() returns the key itself when it cannot resolve one.
            if ($out !== '' && $out !== $full) return $out;
        } catch (Throwable) {
            // fall through to English
        }
    }
    foreach ($params as $k => $v) $english = str_replace('{' . $k . '}', (string)$v, $english);
    return $english;
}

/**
 * tr() escaped for HTML - the normal case for anything going into the page.
 *
 * @param string $key      key within the prefix passed to i18nGuardedInit()
 * @param string $english  the exact text this call replaces
 * @param array $params    {placeholder} => value, applied before escaping
 */
function trh(string $key, string $english, array $params = []): string
{
    return htmlspecialchars(tr($key, $english, $params), ENT_QUOTES, 'UTF-8');
}

/**
 * tr() as a JavaScript literal, for the inline scripts these pages carry.
 *
 * @param string $key      key within the prefix passed to i18nGuardedInit()
 * @param string $english  the exact text this call replaces
 * @param array $params    {placeholder} => value, applied before JSON-encoding
 */
function trj(string $key, string $english, array $params = []): string
{
    return json_encode(tr($key, $english, $params), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/** The locale for <html lang="...">, or 'en' when i18n did not load. */
function trLocale(): string
{
    if ($GLOBALS['i18nGuardedReady']) {
        try { return htmlspecialchars(I18n::getLocale(), ENT_QUOTES, 'UTF-8'); }
        catch (Throwable) { /* fall through */ }
    }
    return 'en';
}
