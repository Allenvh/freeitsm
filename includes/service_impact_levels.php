<?php
/**
 * The baseline impact level — "nothing is wrong with this service right now".
 *
 * A service with no open incident has no row in status_incident_services, so its
 * status is not stored anywhere; it is derived. That derived value used to be the
 * hardcoded string 'Operational' in six separate places (GH #70). Rename the level
 * on Service status → Settings → Impact levels and every one of them kept saying
 * "Operational" — a name that no longer matched any row, so the badge also lost
 * its colour, while a service that WAS in an incident showed the new name
 * correctly. Two services, two different names for the same level.
 *
 * service_impact_levels.is_default is the flag that already means "baseline":
 * saving one clears the rest, deleting it is refused, and if the table somehow
 * ends up with none, the least severe level is promoted. So resolve the fallback
 * from that row rather than from a literal, and hand back its colour too — the
 * callers need the badge colour for a level that is renamed OR deactivated.
 *
 * No dependencies beyond PDO, deliberately: the self-service portal dashboard
 * needs this and should not have to pull in the workflow engine to get it.
 */

/**
 * The impact level a service shows when nothing is wrong with it.
 *
 * @return array{id:?int,name:string,colour:?string} The default impact level.
 *         Per-connection cached — it is read several times to build one page.
 */
function defaultImpactLevel(PDO $conn): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $row = $conn->query(
        "SELECT id, name, colour FROM service_impact_levels WHERE is_default = 1 ORDER BY id LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);

    // No default flagged (an install mid-upgrade, or every level edited away from
    // default at once). Match saveImpactLevel()'s own repair rule: least severe wins.
    if (!$row) {
        $row = $conn->query(
            "SELECT id, name, colour FROM service_impact_levels WHERE is_active = 1
             ORDER BY severity_order DESC, id LIMIT 1"
        )->fetch(PDO::FETCH_ASSOC);
    }

    // Empty table — the seed has not run. Only here is a literal defensible.
    if (!$row) {
        $cache = ['id' => null, 'name' => 'Operational', 'colour' => '#16a34a'];
        return $cache;
    }

    $cache = [
        'id'     => (int)$row['id'],
        'name'   => (string)$row['name'],
        'colour' => $row['colour'] !== null && $row['colour'] !== '' ? (string)$row['colour'] : null,
    ];
    return $cache;
}
