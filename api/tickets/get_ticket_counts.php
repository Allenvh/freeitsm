<?php
/**
 * API Endpoint: Get ticket counts by department and status
 * Returns hierarchical count data for folder view
 * Respects team-based filtering for users with team assignments
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$analystId = (int)$_SESSION['analyst_id'];

try {
    $conn = connectToDatabase();

    // Check if user has team assignments
    $teamCheckSql = "SELECT COUNT(*) as team_count FROM analyst_teams WHERE analyst_id = ?";
    $teamCheckStmt = $conn->prepare($teamCheckSql);
    $teamCheckStmt->execute([$analystId]);
    $teamCount = $teamCheckStmt->fetch(PDO::FETCH_ASSOC)['team_count'];
    $teamCheckStmt->closeCursor();

    $hasTeamFilter = ($teamCount > 0);

    // Multi-tenancy: a single shared predicate scoping every ticket query below
    // to the analyst's active company. It's a no-op (empty fragment) at N=1, so
    // single-company installs are unaffected. The fragment is identical whether
    // it lands in a WHERE clause or a (LEFT JOIN) ON clause; only the ordering of
    // $ttParams within each execute() differs, because positional placeholders
    // bind in order of appearance — ON-clause placeholders precede WHERE ones.
    list($ttSql, $ttParams) = ticketTenantFilter($conn, $analystId, 't');

    if ($hasTeamFilter) {
        // User has team assignments - filter to only their departments
        // First get the list of accessible department IDs
        $accessibleDeptsSql = "SELECT DISTINCT dt.department_id as dept_id
                               FROM department_teams dt
                               INNER JOIN analyst_teams ant ON dt.team_id = ant.team_id
                               WHERE ant.analyst_id = ?";
        $accessibleDeptsStmt = $conn->prepare($accessibleDeptsSql);
        $accessibleDeptsStmt->execute([$analystId]);
        $accessibleDepts = $accessibleDeptsStmt->fetchAll(PDO::FETCH_COLUMN);
        $accessibleDeptsStmt->closeCursor();

        if (empty($accessibleDepts)) {
            // No accessible departments - just count unassigned
            $totalCount = 0;
            $departments = [];
            $deptStatusCounts = [];
            $statusCounts = [];
        } else {
            // Build IN clause with the department IDs
            $deptIdPlaceholders = implode(',', array_fill(0, count($accessibleDepts), '?'));

            // Get total counts for accessible departments only
            $totalSql = "SELECT COUNT(*) as total FROM tickets t
                         WHERE (t.department_id IN ($deptIdPlaceholders) OR t.department_id IS NULL)$ttSql";
            $totalStmt = $conn->prepare($totalSql);
            $totalStmt->execute(array_merge($accessibleDepts, $ttParams));
            $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
            $totalCount = $totalResult['total'];
            $totalStmt->closeCursor();

            // Get counts by department (filtered by team)
            $deptSql = "SELECT
                            d.id,
                            d.name,
                            d.display_order,
                            COUNT(t.id) as count
                        FROM departments d
                        LEFT JOIN tickets t ON t.department_id = d.id$ttSql
                        WHERE d.is_active = 1 AND d.id IN ($deptIdPlaceholders)
                        GROUP BY d.id, d.name, d.display_order
                        ORDER BY d.display_order, d.name";
            $deptStmt = $conn->prepare($deptSql);
            $deptStmt->execute(array_merge($ttParams, $accessibleDepts));
            $departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);
            $deptStmt->closeCursor();

            // Get counts by department and status (filtered by team)
            $deptStatusSql = "SELECT
                                d.id as dept_id,
                                ts.name AS status,
                                COUNT(t.id) as count
                              FROM departments d
                              LEFT JOIN tickets t ON t.department_id = d.id$ttSql
                              LEFT JOIN ticket_statuses ts ON ts.id = t.status_id
                              WHERE d.is_active = 1 AND d.id IN ($deptIdPlaceholders)
                              GROUP BY d.id, ts.name";
            $deptStatusStmt = $conn->prepare($deptStatusSql);
            $deptStatusStmt->execute(array_merge($ttParams, $accessibleDepts));
            $deptStatusCounts = $deptStatusStmt->fetchAll(PDO::FETCH_ASSOC);
            $deptStatusStmt->closeCursor();

            // Get counts by status for accessible departments
            $statusSql = "SELECT
                            ts.name AS status,
                            COUNT(*) as count
                          FROM tickets t
                          LEFT JOIN ticket_statuses ts ON ts.id = t.status_id
                          WHERE (t.department_id IN ($deptIdPlaceholders) OR t.department_id IS NULL)$ttSql
                          GROUP BY ts.name";
            $statusStmt = $conn->prepare($statusSql);
            $statusStmt->execute(array_merge($accessibleDepts, $ttParams));
            $statusCounts = $statusStmt->fetchAll(PDO::FETCH_ASSOC);
            $statusStmt->closeCursor();
        }
    } else {
        // No team assignments - show all departments
        // Get total counts
        $totalSql = "SELECT COUNT(*) as total FROM tickets t WHERE 1=1$ttSql";
        $totalStmt = $conn->prepare($totalSql);
        $totalStmt->execute($ttParams);
        $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $totalCount = $totalResult['total'];
        $totalStmt->closeCursor();

        // Get counts by department
        $deptSql = "SELECT
                        d.id,
                        d.name,
                        d.display_order,
                        COUNT(t.id) as count
                    FROM departments d
                    LEFT JOIN tickets t ON t.department_id = d.id$ttSql
                    WHERE d.is_active = 1
                    GROUP BY d.id, d.name, d.display_order
                    ORDER BY d.display_order, d.name";
        $deptStmt = $conn->prepare($deptSql);
        $deptStmt->execute($ttParams);
        $departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);
        $deptStmt->closeCursor();

        // Get counts by department and status
        $deptStatusSql = "SELECT
                            d.id as dept_id,
                            ts.name AS status,
                            COUNT(t.id) as count
                          FROM departments d
                          LEFT JOIN tickets t ON t.department_id = d.id$ttSql
                          LEFT JOIN ticket_statuses ts ON ts.id = t.status_id
                          WHERE d.is_active = 1
                          GROUP BY d.id, ts.name";
        $deptStatusStmt = $conn->prepare($deptStatusSql);
        $deptStatusStmt->execute($ttParams);
        $deptStatusCounts = $deptStatusStmt->fetchAll(PDO::FETCH_ASSOC);
        $deptStatusStmt->closeCursor();

        // Get counts by status (all departments)
        $statusSql = "SELECT
                        ts.name AS status,
                        COUNT(*) as count
                      FROM tickets t
                      LEFT JOIN ticket_statuses ts ON ts.id = t.status_id
                      WHERE 1=1$ttSql
                      GROUP BY ts.name";
        $statusStmt = $conn->prepare($statusSql);
        $statusStmt->execute($ttParams);
        $statusCounts = $statusStmt->fetchAll(PDO::FETCH_ASSOC);
        $statusStmt->closeCursor();
    }

    // Get unassigned count (always visible to users regardless of teams)
    $unassignedSql = "SELECT COUNT(*) as count FROM tickets t WHERE t.department_id IS NULL$ttSql";
    $unassignedStmt = $conn->prepare($unassignedSql);
    $unassignedStmt->execute($ttParams);
    $unassignedResult = $unassignedStmt->fetch(PDO::FETCH_ASSOC);
    $unassignedStmt->closeCursor();

    // Unassigned-by-analyst count (tickets with no assigned analyst, within accessible depts)
    if ($hasTeamFilter) {
        if (empty($accessibleDepts)) {
            $unassignedAnalystCount = 0;
        } else {
            $deptIdPlaceholdersUA = implode(',', array_fill(0, count($accessibleDepts), '?'));
            $uaSql = "SELECT COUNT(*) FROM tickets t
                      WHERE t.assigned_analyst_id IS NULL
                        AND (t.department_id IN ($deptIdPlaceholdersUA) OR t.department_id IS NULL)$ttSql";
            $uaStmt = $conn->prepare($uaSql);
            $uaStmt->execute(array_merge($accessibleDepts, $ttParams));
            $unassignedAnalystCount = (int)$uaStmt->fetchColumn();
            $uaStmt->closeCursor();
        }
    } else {
        $uaStmt = $conn->prepare("SELECT COUNT(*) FROM tickets t WHERE t.assigned_analyst_id IS NULL$ttSql");
        $uaStmt->execute($ttParams);
        $unassignedAnalystCount = (int)$uaStmt->fetchColumn();
        $uaStmt->closeCursor();
    }

    // Counts by analyst, and by analyst+status — bounded by accessible depts when team-filtered.
    // The dept filter sits in the LEFT JOIN ON clause so analysts with zero matching tickets
    // still appear in the folder list (as drop targets).
    //
    // "Always see your own": the viewer's own tickets (assigned to OR owned by
    // them) are ALWAYS counted, even in a department their team can't otherwise
    // see — an analyst must never lose sight of a ticket assigned to them. This
    // is additive (it only ever includes more), and keeps the per-analyst counts
    // consistent with the ticket list, which doesn't team-filter at all.
    $deptJoinFilter = '';
    $analystParams = [];
    $skipAnalystQueries = false;
    if ($hasTeamFilter) {
        $ownClause = "t.assigned_analyst_id = ? OR t.owner_id = ?";
        if (empty($accessibleDepts)) {
            $deptJoinFilter = " AND ($ownClause)";
            $analystParams = [$analystId, $analystId];
        } else {
            $deptIn = implode(',', array_fill(0, count($accessibleDepts), '?'));
            $deptJoinFilter = " AND (t.department_id IN ($deptIn) OR t.department_id IS NULL OR $ownClause)";
            $analystParams = array_merge($accessibleDepts, [$analystId, $analystId]);
        }
    }

    if ($skipAnalystQueries) {
        $analystCounts = [];
        $analystStatusCounts = [];
    } else {
        $analystCountSql = "SELECT a.id, a.full_name, COUNT(t.id) as count
                            FROM analysts a
                            LEFT JOIN tickets t ON t.assigned_analyst_id = a.id $deptJoinFilter$ttSql
                            WHERE a.is_active = 1
                            GROUP BY a.id, a.full_name
                            ORDER BY a.full_name";
        $analystCountStmt = $conn->prepare($analystCountSql);
        $analystCountStmt->execute(array_merge($analystParams, $ttParams));
        $analystCounts = $analystCountStmt->fetchAll(PDO::FETCH_ASSOC);
        $analystCountStmt->closeCursor();

        $analystStatusSql = "SELECT a.id as analyst_id, ts.name AS status, COUNT(t.id) as count
                             FROM analysts a
                             LEFT JOIN tickets t ON t.assigned_analyst_id = a.id $deptJoinFilter$ttSql
                             LEFT JOIN ticket_statuses ts ON ts.id = t.status_id
                             WHERE a.is_active = 1
                             GROUP BY a.id, ts.name";
        $analystStatusStmt = $conn->prepare($analystStatusSql);
        $analystStatusStmt->execute(array_merge($analystParams, $ttParams));
        $analystStatusCounts = $analystStatusStmt->fetchAll(PDO::FETCH_ASSOC);
        $analystStatusStmt->closeCursor();
    }

    // Master list of active statuses — drives the folder UI
    $statusListStmt = $conn->query(
        "SELECT id, name, colour, is_closed, is_default, display_order
         FROM ticket_statuses
         WHERE is_active = 1
         ORDER BY display_order, id"
    );
    $activeStatuses = $statusListStmt->fetchAll(PDO::FETCH_ASSOC);
    $statusListStmt->closeCursor();

    $statusMeta = array_map(function ($s) {
        return [
            'name'          => $s['name'],
            'colour'        => $s['colour'],
            'is_closed'     => (int)$s['is_closed'],
            'is_default'    => (int)$s['is_default'],
            'display_order' => (int)$s['display_order'],
        ];
    }, $activeStatuses);
    $activeStatusNames = array_column($statusMeta, 'name');

    // Build status counts by department map (only counting active statuses)
    $statusByDept = [];
    foreach ($deptStatusCounts as $row) {
        if ($row['status'] === null) continue;
        if (!in_array($row['status'], $activeStatusNames, true)) continue;
        if (!isset($statusByDept[$row['dept_id']])) {
            $statusByDept[$row['dept_id']] = [];
        }
        $statusByDept[$row['dept_id']][$row['status']] = (int)$row['count'];
    }

    // Build department structure with status subfolders
    $departmentStructure = [];
    foreach ($departments as $dept) {
        $deptId = $dept['id'];
        $deptStatusMap = isset($statusByDept[$deptId]) ? $statusByDept[$deptId] : [];
        $statuses = [];
        foreach ($activeStatusNames as $name) {
            $statuses[$name] = $deptStatusMap[$name] ?? 0;
        }

        $departmentStructure[] = [
            'id' => $deptId,
            'name' => $dept['name'],
            'count' => (int)$dept['count'],
            'statuses' => $statuses
        ];
    }

    // Build overall status counts dynamically from active statuses
    $overallStatuses = [];
    foreach ($activeStatusNames as $name) {
        $overallStatuses[$name] = 0;
    }
    foreach ($statusCounts as $row) {
        if ($row['status'] !== null && isset($overallStatuses[$row['status']])) {
            $overallStatuses[$row['status']] = (int)$row['count'];
        }
    }

    // Build analyst structure with status subfolders (mirrors departmentStructure)
    $statusByAnalyst = [];
    foreach ($analystStatusCounts as $row) {
        if ($row['status'] === null) continue;
        if (!in_array($row['status'], $activeStatusNames, true)) continue;
        if (!isset($statusByAnalyst[$row['analyst_id']])) {
            $statusByAnalyst[$row['analyst_id']] = [];
        }
        $statusByAnalyst[$row['analyst_id']][$row['status']] = (int)$row['count'];
    }

    $analystStructure = [];
    foreach ($analystCounts as $a) {
        $aId = $a['id'];
        $aStatusMap = $statusByAnalyst[$aId] ?? [];
        $statuses = [];
        foreach ($activeStatusNames as $name) {
            $statuses[$name] = $aStatusMap[$name] ?? 0;
        }
        $analystStructure[] = [
            'id' => (int)$aId,
            'name' => $a['full_name'],
            'count' => (int)$a['count'],
            'statuses' => $statuses
        ];
    }

    echo json_encode([
        'success' => true,
        'total_count' => $totalCount,
        'unassigned_count' => (int)$unassignedResult['count'],
        'unassigned_analyst_count' => $unassignedAnalystCount,
        'statuses' => $statusMeta,
        'departments' => $departmentStructure,
        'analysts' => $analystStructure,
        'overall_statuses' => $overallStatuses
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>
