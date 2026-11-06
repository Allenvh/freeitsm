<?php
/**
 * The system's name - System -> Branding -> "System name" (Tickets -> Settings -> General until 3.5.0).
 *
 * It was saved and read by nothing: the setting's help promised the name in
 * the page titles, and 90 pages had "Service Desk" (or "FreeITSM") typed into
 * their <title> instead. They now ask here.
 *
 * Never saved, empty, or unreadable (no database yet, mid-upgrade) means
 * "Service Desk" - exactly what every title said before - so nothing changes
 * for an install that has not set it.
 *
 * Loaded from includes/i18n.php and includes/theme.php, one of which every
 * page with a title already loads.
 */

if (!function_exists('systemName')) {
    /** The system's name from System -> Branding, or "Service Desk" when it is not set. Cached per request. */
    function systemName(): string
    {
        static $name = null;
        if ($name !== null) return $name;
        $name = 'Service Desk';
        try {
            if (!function_exists('connectToDatabase')) require_once __DIR__ . '/functions.php';
            $st = connectToDatabase()->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'system_name'");
            $st->execute();
            $v = trim((string)$st->fetchColumn());
            if ($v !== '') $name = $v;
        } catch (Throwable) {
            // keep the default
        }
        return $name;
    }
}

if (!function_exists('pageTitle')) {
    /**
     * A browser tab title, the same shape on every page (GH #173):
     *
     *     <system name> - <module> - <page>      e.g. "Acme IT - Contracts - Edit contract"
     *     <system name> - <module>               a module's own front page
     *     <system name> - <page>                 a page outside any module (sign in)
     *
     * $module is the module's key in common.modules (the names the waffle menu
     * shows), so the module part is translated once, in one place. Pass the page
     * part as plain words - "Settings", never "Service Desk - Settings".
     *
     * ⚠️ Why a string must never carry the system name or the module itself:
     * several language files once held "Service Desk - Contracts", which is
     * frozen English in a title that should follow the configured name, and
     * would read "Acme - Contracts - Service Desk - Contracts" in this shape.
     * A part equal to one already in the title is dropped, so a page whose own
     * heading is the module name does not say it twice.
     *
     * Returns plain text - escape it where it is printed.
     *
     * @param ?string $module  the module's key in common.modules, e.g. 'contracts'; null or '' for a page outside any module
     * @param ?string ...$parts  the page's own words, e.g. 'Edit contract'; blanks and repeats of earlier parts are dropped
     */
    function pageTitle(?string $module = null, ?string ...$parts): string
    {
        $out = [systemName()];
        if ($module !== null && $module !== '') {
            $key  = 'common.modules.' . $module . '.name';
            $name = function_exists('t') ? t($key) : '';
            if ($name === '' || $name === $key) {
                // A page that never loaded translations (Problem Management's
                // settings and help) still gets the module's real English name.
                static $en = null;
                if ($en === null) {
                    $en = [];
                    try { $c = @include __DIR__ . '/../lang/en/common.php'; $en = is_array($c) ? ($c['modules'] ?? []) : []; } catch (Throwable) {}
                }
                $name = $en[$module]['name'] ?? ucfirst(str_replace('-', ' ', $module));
            }
            $out[] = $name;
        }
        foreach ($parts as $p) {
            $p = trim((string)$p);
            if ($p !== '' && !in_array(mb_strtolower($p), array_map('mb_strtolower', $out), true)) {
                $out[] = $p;
            }
        }
        return implode(' - ', $out);
    }
}
