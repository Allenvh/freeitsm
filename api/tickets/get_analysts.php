<?php
/**
 * API Endpoint: Get all analysts
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireAnyModuleAccessJson(['tickets', 'contracts', 'forms', 'morning-checks', 'problems', 'system']);

try {
    $conn = connectToDatabase();

    $sql = "SELECT id, username, full_name, email, is_active, auth_provider_id, can_access_all_tenants, is_admin, can_access_all_modules, created_datetime, last_login_datetime, last_modified_datetime
            FROM analysts
            ORDER BY full_name";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $analysts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Multi-tenancy: which companies each analyst is granted DIRECTLY (only
    // consulted when they're NOT all-access). Degrades to empty if not migrated.
    $grantsByAnalyst = [];
    try {
        foreach ($conn->query("SELECT analyst_id, tenant_id FROM analyst_tenant_access") as $row) {
            $grantsByAnalyst[(int)$row['analyst_id']][] = (int)$row['tenant_id'];
        }
    } catch (Exception $e) {
        $grantsByAnalyst = [];
    }

    // Company access an analyst also gets VIA their teams (additive). Two parts:
    // membership of an all-access team, and specific companies granted by a team.
    // Lets the UI show effective access, not just direct grants.
    $teamAllAccess = [];       // analyst_id => true (in a team flagged all-access)
    $teamGrantsByAnalyst = []; // analyst_id => [tenant_id, ...]
    try {
        foreach ($conn->query("SELECT at.analyst_id FROM analyst_teams at JOIN teams t ON at.team_id = t.id WHERE t.can_access_all_tenants = 1") as $row) {
            $teamAllAccess[(int)$row['analyst_id']] = true;
        }
        foreach ($conn->query("SELECT at.analyst_id, tta.tenant_id FROM team_tenant_access tta JOIN analyst_teams at ON at.team_id = tta.team_id") as $row) {
            $teamGrantsByAnalyst[(int)$row['analyst_id']][] = (int)$row['tenant_id'];
        }
    } catch (Exception $e) {
        $teamAllAccess = [];
        $teamGrantsByAnalyst = [];
    }

    // Convert fields to proper types
    foreach ($analysts as &$analyst) {
        $analyst['id'] = (int)$analyst['id'];
        $analyst['is_active'] = (bool)$analyst['is_active'];
        $analyst['auth_provider_id'] = $analyst['auth_provider_id'] !== null ? (int)$analyst['auth_provider_id'] : null;
        // Default to all-access if the column is somehow NULL (matches the migration default).
        $analyst['can_access_all_tenants'] = !isset($analyst['can_access_all_tenants']) || (int)$analyst['can_access_all_tenants'] === 1;
        $direct = $grantsByAnalyst[$analyst['id']] ?? [];
        $viaTeam = array_values(array_unique($teamGrantsByAnalyst[$analyst['id']] ?? []));
        $analyst['tenant_ids'] = $direct;                                   // directly granted (editable here)
        $analyst['team_tenant_ids'] = $viaTeam;                             // granted via a team (read-only here)
        $analyst['team_all_access'] = !empty($teamAllAccess[$analyst['id']]); // in an all-access team
    }
    unset($analyst);

    // Follow team (GH #41): whether each analyst takes their sign-in method from
    // their teams, and what those teams say. Only for System -> Analysts, which asks
    // with ?signin=1 - every assignee list in the app calls this endpoint too, and
    // none of them needs it.
    if (!empty($_GET['signin'])) {
        require_once '../../includes/analyst_signin.php';
        if (analystSignInReady($conn)) {
            $follow = [];
            foreach ($conn->query("SELECT id FROM analysts WHERE auth_follow_team = 1") as $row) {
                $follow[(int)$row['id']] = true;
            }
            $byAnalyst = analystSignInTeamMethods($conn);
            foreach ($analysts as &$analyst) {
                $analyst['auth_follow_team'] = !empty($follow[$analyst['id']]);
                $analyst['team_signin'] = analystSignInVerdict($byAnalyst[$analyst['id']] ?? []);
            }
            unset($analyst);
        }
    }

    // GH #208. ?teams=1 (System -> Analysts) adds each analyst's active teams, job
    // title and department, so the list can be grouped and filtered by them. One
    // query for every analyst: the page used to fetch teams one analyst at a time,
    // which is what made a long list slow to appear.
    if (!empty($_GET['teams'])) {
        $teamsByAnalyst = [];
        foreach ($conn->query("SELECT at.analyst_id, t.id, t.name FROM analyst_teams at
                               JOIN teams t ON t.id = at.team_id
                               WHERE t.is_active = 1 ORDER BY t.display_order, t.name") as $row) {
            $teamsByAnalyst[(int)$row['analyst_id']][] = ['id' => (int)$row['id'], 'name' => $row['name']];
        }
        // job_title / department arrived with #80; an install whose Database
        // Verification has not added them yet simply groups everyone under "Not set".
        $profile = [];
        try {
            foreach ($conn->query("SELECT id, job_title, department FROM analysts") as $row) {
                $profile[(int)$row['id']] = $row;
            }
        } catch (Exception $e) {
            $profile = [];
        }
        foreach ($analysts as &$analyst) {
            $analyst['teams'] = $teamsByAnalyst[$analyst['id']] ?? [];
            $analyst['job_title'] = $profile[$analyst['id']]['job_title'] ?? null;
            $analyst['department'] = $profile[$analyst['id']]['department'] ?? null;
        }
        unset($analyst);
    }

    // GH #176. ?with_access=1 (the ticket inbox) adds the companies each active
    // analyst can work in, so the assign lists offer only people who can open the
    // ticket's company. TRAP: decided by getAccessibleTenantIds(), never a copy of
    // its rule here or in the JS - it unions the analyst's grants with every team's.
    $multiCompany = false;
    if (!empty($_GET['with_access'])) {
        require_once '../../includes/tenancy.php';
        $multiCompany = isMultiTenant($conn);
        if ($multiCompany) {
            foreach ($analysts as &$analyst) {
                $analyst['accessible_tenant_ids'] = $analyst['is_active']
                    ? array_values(array_map('intval', getAccessibleTenantIds($conn, $analyst['id'])))
                    : [];
            }
            unset($analyst);
        }
    }

    echo json_encode(['success' => true, 'analysts' => $analysts, 'multi_company' => $multiCompany]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>
