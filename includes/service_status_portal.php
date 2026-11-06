<?php
/**
 * What the self-service portal shows about service status (discussion #99).
 *
 * The requester asked for internal and external comments on incidents, with the
 * external ones visible to end users. Worth being clear about what changed:
 * before this, updates were not "all treated the same" so much as ALL INTERNAL,
 * because the portal never showed any of them. It showed a service and a
 * colour. So this is mostly about starting to communicate, not about splitting
 * something that was already public.
 *
 * ⚠️ EVERY portal-facing read goes through here. There is exactly one query
 * that decides what an end user may see, and it is the one below.
 *
 * 🔴 OFF BY DEFAULT, and that is not timidity. Turning it on publishes incident
 * TITLES, which were written when nobody expected a customer to read them — an
 * upgrade that started showing "Exchange DAG failover borked again" on the
 * portal would be doing something no upgrade should be able to do. An
 * administrator turns it on under System once they have looked.
 */

const SS_PORTAL_DEFAULTS = [
    // Show incidents and their external updates on the portal at all.
    'service_status_portal_updates' => '0',
    // 'open'   — only what is wrong now
    // 'recent' — open, plus anything resolved in the last N days
    // 'all'    — every incident that has ever had an external update
    'service_status_portal_mode'    => 'recent',
    'service_status_portal_days'    => '7',
];

/**
 * All three settings, with defaults filled in for anything unset.
 *
 * ⚠️ NOT CACHED, deliberately. The first version held them in a `static`, which
 * is per PROCESS rather than per connection or per request — so once it had
 * read "off", nothing could change that answer for the rest of the request. The
 * settings page would have saved a change and then re-rendered the old value,
 * and the tests could not exercise more than one configuration.
 *
 * It is one small query against three keys. That is not worth a stale read.
 */
function ssPortalSettings(PDO $conn): array
{
    $out = SS_PORTAL_DEFAULTS;
    try {
        $keys = array_keys(SS_PORTAL_DEFAULTS);
        $in   = implode(',', array_fill(0, count($keys), '?'));
        $stmt = $conn->prepare("SELECT setting_key, setting_value FROM system_settings WHERE setting_key IN ($in)");
        $stmt->execute($keys);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            if ((string)$r['setting_value'] !== '') {
                $out[$r['setting_key']] = (string)$r['setting_value'];
            }
        }
    } catch (Throwable) {
        // A pre-upgrade install without the rows gets the defaults, which are
        // the safe ones.
    }
    return $out;
}

/**
 * One setting, falling back to its default.
 *
 * @param string $key  one of the SS_PORTAL_DEFAULTS keys, e.g. 'service_status_portal_mode'
 */
function ssPortalSetting(PDO $conn, string $key): string
{
    return ssPortalSettings($conn)[$key] ?? '';
}

/** Are incidents shown on the portal at all? */
function ssPortalUpdatesEnabled(PDO $conn): bool
{
    return ssPortalSetting($conn, 'service_status_portal_updates') === '1';
}

/**
 * Incidents the portal may show, newest first, each with its EXTERNAL updates.
 *
 * Returns [] when the feature is switched off, which is the default — so a
 * caller that forgets to check gets nothing rather than everything.
 *
 * @param int $limit  the most incidents to return
 */
function ssPortalIncidents(PDO $conn, int $limit = 25): array
{
    if (!ssPortalUpdatesEnabled($conn)) {
        return [];
    }

    $cfg  = ssPortalSettings($conn);
    $mode = $cfg['service_status_portal_mode'];
    $days = max(1, min(365, (int)$cfg['service_status_portal_days']));

    // ⚠️ An incident only reaches the portal once somebody has written an
    // EXTERNAL update on it. An incident with nothing but internal notes is
    // still being worked on privately, and its title alone tells a customer
    // nothing they can use.
    $where  = "EXISTS (SELECT 1 FROM status_incident_updates u
                        WHERE u.incident_id = si.id AND u.is_internal = 0)";
    $params = [];

    if ($mode === 'open') {
        $where .= " AND (sst.is_resolved = 0 OR sst.id IS NULL)";
    } elseif ($mode === 'recent') {
        // Open, or resolved recently. A resolved entry is the most reassuring
        // thing on a status page - somebody hit by the outage this morning
        // needs to see that it was fixed, not an empty page.
        $where .= " AND ((sst.is_resolved = 0 OR sst.id IS NULL)
                         OR si.resolved_datetime >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL ? DAY))";
        $params[] = $days;
    }
    // 'all' adds nothing.

    $stmt = $conn->prepare(
        "SELECT si.id, si.title, si.created_datetime, si.updated_datetime, si.resolved_datetime,
                sst.name AS status, sst.is_resolved
           FROM status_incidents si
      LEFT JOIN service_incident_statuses sst ON sst.id = si.status_id
          WHERE $where
       ORDER BY (sst.is_resolved = 1), si.updated_datetime DESC, si.id DESC
          LIMIT " . (int)$limit
    );
    $stmt->execute($params);

    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$incidents) {
        return [];
    }

    // The affected services, so the portal can say WHAT is affected rather than
    // only that something is.
    $ids = array_map(fn($i) => (int)$i['id'], $incidents);
    $in  = implode(',', array_fill(0, count($ids), '?'));

    $svc = $conn->prepare(
        "SELECT sis.incident_id, ss.name AS service_name, il.name AS impact_name, il.colour AS impact_colour
           FROM status_incident_services sis
           JOIN status_services ss ON ss.id = sis.service_id
      LEFT JOIN service_impact_levels il ON il.id = sis.impact_level_id
          WHERE sis.incident_id IN ($in) AND ss.is_active = 1
       ORDER BY ss.display_order, ss.name"
    );
    $svc->execute($ids);
    $byIncident = [];
    foreach ($svc->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $byIncident[(int)$r['incident_id']][] = [
            'service_name'   => $r['service_name'],
            'impact_name'    => $r['impact_name'],
            'impact_colour'  => $r['impact_colour'],
        ];
    }

    foreach ($incidents as &$i) {
        $i['id']       = (int)$i['id'];
        $i['services'] = $byIncident[$i['id']] ?? [];
        // The count is sent with the list so the portal can say "3 updates"
        // on a collapsed row without fetching them all first.
        $i['update_count'] = 0;
    }
    unset($i);

    $cnt = $conn->prepare(
        "SELECT incident_id, COUNT(*) n FROM status_incident_updates
          WHERE incident_id IN ($in) AND is_internal = 0 GROUP BY incident_id"
    );
    $cnt->execute($ids);
    $counts = [];
    foreach ($cnt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $counts[(int)$r['incident_id']] = (int)$r['n'];
    }
    foreach ($incidents as &$i) {
        $i['update_count'] = $counts[$i['id']] ?? 0;
    }
    unset($i);

    return $incidents;
}

/**
 * The EXTERNAL updates on one incident, oldest first — the progression from
 * "we are looking at it" to "it is fixed", which is what a status page is for.
 *
 * ⚠️ `is_internal = 0` is not optional and is not a caller's decision. It is
 * here, in the query, because this function is the only thing the portal calls.
 *
 * Returns [] when the feature is off, or when the incident has no external
 * updates — which is the same answer as "no such incident", deliberately.
 *
 * @param int $incidentId  the status_incidents row whose external updates to list
 */
function ssPortalIncidentUpdates(PDO $conn, int $incidentId): array
{
    if (!ssPortalUpdatesEnabled($conn)) {
        return [];
    }

    $stmt = $conn->prepare(
        "SELECT u.id, u.comment, u.created_datetime, sst.name AS status
           FROM status_incident_updates u
      LEFT JOIN service_incident_statuses sst ON sst.id = u.status_id
          WHERE u.incident_id = ? AND u.is_internal = 0
       ORDER BY u.created_datetime ASC, u.id ASC"
    );
    $stmt->execute([$incidentId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        $r['id'] = (int)$r['id'];
        // ⚠️ No author. Who fixed it is internal detail; a customer needs to
        // know what happened and when, not which engineer was on.
    }
    return $rows;
}
