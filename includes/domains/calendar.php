<?php
/**
 * Domains — expiry dates as all-day Calendar entries. Two kinds:
 *
 *   domain_expiry  the registration       "Domain renewals"       domain_expiry_surface
 *   domain_cert    the live certificate   "Certificate renewals"  domain_cert_surface
 *
 * The same machinery as asset warranties and leases, reused rather than copied
 * (see Warranty-and-Lease-Alerts-Developer-Guide on the wiki):
 *
 *  - 🔑 EACH KIND HAS ITS OWN `source` MARKER AND ITS OWN SETTING. Sync clears
 *    and rebuilds one marker at a time, so switching certificates off removes
 *    only certificate entries; registrations, warranties and anything typed by
 *    hand are untouched.
 *
 *  - 🔑 THE CATEGORY KNOWS WHAT IT IS. awcAdoptExistingCategory /
 *    awcEnsureCategory remember it by id (`<source>_category_id`), so an
 *    operator can rename "Certificate renewals" to their own language or
 *    wording - the only translation a stored row can have - and the next sync
 *    files into the renamed one instead of making a second. Adoption runs
 *    BEFORE the delete, because the entries about to be cleared are the only
 *    record of which category they were in.
 *
 *  - Clearing happens even when the kind is switched off: that is what makes
 *    switching it off actually remove the entries.
 *
 * Only domains whose status still wants alerts are drawn: a domain deliberately
 * "Letting lapse" should not keep shouting from the calendar.
 *
 * ⚠️ Calendar entries have no company. On a multi-company install every
 * analyst who can open the Calendar sees these, as they already see contract
 * and warranty dates — the Domains help page says so.
 */

require_once __DIR__ . '/../asset_warranty_calendar.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/subdomains.php';

/** Both kinds. The name is kept: every caller already asks for "the calendar". */
function domainSyncExpiryCalendar(PDO $conn): array
{
    // GH #217: nothing at all while the Calendar is switched off - not even the clear-out,
    // so switching it back on finds the entries as they were until the next sync.
    if (!moduleIsEnabled($conn, 'calendar')) return ['success' => true, 'synced' => 0, 'synced_certificates' => 0];
    try {
        if (!awcColumnExists($conn, 'calendar_events', 'source') || !awcColumnExists($conn, 'domains', 'expiry_date')) {
            return ['success' => false, 'error' => 'Schema not ready'];
        }
        $live = "(s.id IS NULL OR s.alerts_enabled = 1)";
        // A subdomain renews with its parent: only the parent has a renewal entry.
        $n = domainSyncCalendarKind($conn, 'domain_expiry', domainSetting($conn, 'domain_expiry_surface'),
            'Domain renewals', '#4d7c0f', 'expiry_date', 'Domain expires: ',
            'Auto-generated from the Domains register. Change the expiry date on the domain (or renew it and refresh its lookup) to move this.',
            $live . (domainSubdomainsReady($conn) ? ' AND d.parent_domain_id IS NULL' : ''));
        // A separate column check: an install that has the expiry column but not
        // the certificate one must keep its renewals (the lease_expiry lesson).
        $c = awcColumnExists($conn, 'domains', 'ssl_expiry_date')
            ? domainSyncCalendarKind($conn, 'domain_cert', domainSetting($conn, 'domain_cert_surface'),
                'Certificate renewals', '#0e7490', 'ssl_expiry_date', 'Certificate expires: ',
                'Auto-generated from the Domains register: the soonest-expiring certificate its checks found. Renewing the certificate and running Check now moves this.',
                $live)
            : 0;
        $c += domainSyncManualCertCalendar($conn, $live);
        return ['success' => true, 'synced' => $n, 'synced_certificates' => $c];
    } catch (Throwable $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Certificates recorded by hand join the checked ones in "Certificate renewals":
 * the same source (so the same clear-and-redraw keeps them in step), the same
 * category and the same surface setting - one entry per certificate.
 * Runs AFTER the domain_cert kind, which has just cleared the source.
 *
 * @param string $live  the same status condition the checked certificates use
 */
function domainSyncManualCertCalendar(PDO $conn, string $live): int
{
    if (!domainManualCertsReady($conn)) return 0;
    if (!in_array(domainSetting($conn, 'domain_cert_surface'), ['calendar', 'both'], true)) return 0;
    $cat = awcEnsureCategory($conn, 'domain_cert', 'Certificate renewals', '#0e7490');
    $rows = $conn->query(
        "SELECT m.purpose, m.expiry_date, d.domain_name, d.display_name
           FROM domain_manual_certificates m
           JOIN domains d ON d.id = m.domain_id
      LEFT JOIN domain_statuses s ON s.id = d.status_id
          WHERE $live"
    )->fetchAll(PDO::FETCH_ASSOC);
    $ins = $conn->prepare(
        "INSERT INTO calendar_events (title, description, category_id, start_datetime, end_datetime, all_day, created_by, source)
         VALUES (?, ?, ?, ?, ?, 1, 0, 'domain_cert')"
    );
    foreach ($rows as $r) {
        $dt = substr($r['expiry_date'], 0, 10) . ' 00:00:00';
        $ins->execute(['Certificate expires: ' . $r['purpose'] . ' (' . ($r['display_name'] ?: $r['domain_name']) . ')',
                       'Auto-generated from a certificate recorded in the Domains register. Change its expiry date there to move this.',
                       $cat, $dt, $dt]);
    }
    return count($rows);
}

/**
 * One kind: adopt its category, clear its entries, and - if its surface says
 * calendar - draw one all-day entry per domain with a date in $column.
 * The column name is interpolated, never bound; it comes only from this file.
 *
 * @param string $source        the calendar_events.source marker for this kind, 'domain_expiry' or 'domain_cert'
 * @param string $surface       that kind's surface setting - off | dashboard | calendar | both; only the last two draw
 * @param string $categoryName  the calendar category to create if none is remembered for this source
 * @param string $colour        hex colour for a newly created category, e.g. '#4d7c0f'
 * @param string $column        the domains date column to draw from - interpolated into SQL, so only literals from here
 * @param string $prefix        put before the domain name in each entry's title, e.g. 'Domain expires: '
 * @param string $description   the description written on every entry
 * @param string $live          an SQL condition on domain_statuses alias s that a domain must meet to be drawn
 */
function domainSyncCalendarKind(PDO $conn, string $source, string $surface, string $categoryName, string $colour,
                                string $column, string $prefix, string $description, string $live): int
{
    awcAdoptExistingCategory($conn, $source);                       // BEFORE the delete — see its comment
    $conn->prepare("DELETE FROM calendar_events WHERE source = ?")->execute([$source]);
    if (!in_array($surface, ['calendar', 'both'], true)) return 0;

    $cat = awcEnsureCategory($conn, $source, $categoryName, $colour);
    $rows = $conn->query(
        "SELECT d.id, d.domain_name, d.display_name, d.`$column` AS on_date
           FROM domains d
      LEFT JOIN domain_statuses s ON s.id = d.status_id
          WHERE d.`$column` IS NOT NULL AND $live"
    )->fetchAll(PDO::FETCH_ASSOC);

    $ins = $conn->prepare(
        "INSERT INTO calendar_events (title, description, category_id, start_datetime, end_datetime, all_day, created_by, source)
         VALUES (?, ?, ?, ?, ?, 1, 0, ?)"
    );
    $n = 0;
    foreach ($rows as $r) {
        $dt = substr($r['on_date'], 0, 10) . ' 00:00:00';
        $ins->execute([$prefix . ($r['display_name'] ?: $r['domain_name']), $description, $cat, $dt, $dt, $source]);
        $n++;
    }
    return $n;
}
