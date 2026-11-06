<?php
// PHP 8.1 or newer, or a plain message instead of a parse error. FIRST, because
// config.php loads this file before anything else of ours.
require_once __DIR__ . '/php_version_guard.php';

/**
 * Central SSL/TLS policy for outbound HTTPS.
 *
 * Every outbound cURL handle in the app routes its certificate-verification
 * setup through sslApplyCurl(). That gives us a single global switch
 * (SSL_VERIFY_PEER, in config.php) instead of the per-module "Verify SSL"
 * tick-boxes we used to scatter across settings pages — which were confusing
 * (some ANDed with the global, some independent) and, worse, let the UI imply
 * a security state the server couldn't actually deliver.
 *
 * Why CURLOPT_CAINFO rather than php.ini or an env var:
 *   On a stock Windows/WAMP install, Apache's php.ini has no `curl.cainfo`, so
 *   verification fails with "unable to get local issuer certificate". You can't
 *   fix that from PHP at runtime — `curl.cainfo` is PHP_INI_SYSTEM (ini_set has
 *   no effect) and this libcurl build ignores the CURL_CA_BUNDLE env var. The
 *   only mechanism that works without hand-editing php.ini is setting the bundle
 *   per-handle with CURLOPT_CAINFO, which is what we ship a cacert.pem for.
 *
 * WHY BOTH DEFINITIONS ARE GUARDED BY function_exists(), same as includes/db.php:
 * every caller loads config.php first, and config.php requires this file. An
 * operator whose own config.php carries a hand-pasted copy of either function
 * would otherwise trade "undefined function" for "cannot redeclare" - the same
 * outage in the other direction. Theirs wins; this fills the hole for everyone
 * else. See GH #129.
 *
 * AND WHY EVERY DIRECTLY-REQUESTABLE ENTRY POINT MUST REQUIRE THIS FILE ITSELF:
 * reaching sslApplyCurl() only through config.php means the operator's file is
 * load-bearing, which is exactly what #129 forbids. Most callers arrive via
 * includes/functions.php, which requires this file; the OAuth callbacks
 * deliberately do not load functions.php, so they require it directly.
 * tests/config-not-load-bearing.php enforces that structurally.
 */

/**
 * Resolve a CA bundle path for CURLOPT_CAINFO, or '' to leave cURL on its
 * compiled-in default.
 *
 * Priority:
 *   1. A bundle an admin (or the OS) already configured in php.ini — honour it.
 *   2. On Windows only, the cacert.pem we ship, since PHP-on-Windows has none.
 *   3. Otherwise '' — on Linux with no configured bundle, cURL's system trust
 *      store is correct and we must not override it with a possibly-staler copy.
 */
if (!function_exists('sslResolveCaBundle')) {
    /**
     * The CA bundle file to verify TLS with: PHP's configured one, else the bundled
     * cacert.pem on Windows, else '' for the system store.
     */
    function sslResolveCaBundle(): string
    {
        foreach (['curl.cainfo', 'openssl.cafile'] as $iniKey) {
            $p = ini_get($iniKey);
            if ($p && is_readable($p)) {
                return $p;
            }
        }
        if (stripos(PHP_OS, 'WIN') === 0) {
            $bundled = __DIR__ . '/cacert.pem';
            if (is_readable($bundled)) {
                return $bundled;
            }
        }
        return '';
    }
}

/**
 * Apply the global TLS verification policy to a cURL handle.
 *
 * Sets VERIFYPEER/VERIFYHOST from SSL_VERIFY_PEER and, when verifying, points
 * cURL at a usable CA bundle. Call this once per handle, after curl_init(),
 * instead of setting CURLOPT_SSL_VERIFYPEER by hand.
 *
 * @param \CurlHandle|resource $ch
 * @param bool $alwaysVerify  Force verification on regardless of the global
 *                            switch — for traffic that must never be sent
 *                            unverified (webhooks carry record data to third
 *                            parties over the public internet). Still attaches
 *                            the CA bundle so it works out of the box.
 */
if (!function_exists('sslApplyCurl')) {
    /**
     * Applies the install's TLS verification setting, and the CA bundle, to a cURL handle.
     *
     * @param mixed $ch  the cURL handle from curl_init() (CurlHandle, or a resource before PHP 8)
     * @param bool $alwaysVerify  true verifies even when SSL_VERIFY_PEER is off - for traffic that must never go unverified
     */
    function sslApplyCurl($ch, bool $alwaysVerify = false): void
    {
        $verify = $alwaysVerify || (defined('SSL_VERIFY_PEER') ? (bool)SSL_VERIFY_PEER : true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verify ? 2 : 0);
        if (!$verify) {
            return;
        }
        // SSL_CA_BUNDLE is defined by config.php, which is the OPERATOR'S file and
        // never upgrades - a hand-assembled or pre-release copy can lack that block
        // entirely. Falling back to the same resolver config.php would have used
        // means a missing line costs nothing, instead of verifying with no bundle
        // and failing with "unable to get local issuer certificate". Theirs still
        // wins when they have one. See GH #129.
        static $fallback = null;
        $bundle = defined('SSL_CA_BUNDLE') ? SSL_CA_BUNDLE : ($fallback ?? $fallback = sslResolveCaBundle());
        if ($bundle !== '') {
            curl_setopt($ch, CURLOPT_CAINFO, $bundle);
        }
    }
}
