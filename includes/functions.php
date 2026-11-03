<?php
/**
 * Shared Functions
 * Include this file in any script that needs common functionality
 *
 * Usage: require_once '../includes/functions.php'; (from api folder)
 *        require_once 'includes/functions.php'; (from root folder)
 */

/**
 * Connect to MySQL database using PDO
 *
 * @return PDO Database connection
 * @throws Exception If connection fails
 */
function connectToDatabase() {
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

/**
 * Get the list of modules an analyst is allowed to access.
 *
 * @param PDO $conn Database connection
 * @param int $analyst_id Analyst ID
 * @return array|null Null means all access; array of module_key strings if restricted
 */
function getAnalystAllowedModules($conn, $analyst_id) {
    return getAnalystEffectiveModules($conn, $analyst_id);
}

/**
 * Get effective modules from existing per-user assignments plus active access groups.
 * Null still means unrestricted/full access for backwards compatibility.
 */
function getAnalystEffectiveModules($conn, $analyst_id) {
    // AD-managed analysts use only the authoritative directory result. The legacy
    // empty-means-all and automatic system access rules must never apply to AD.
    try {
        $ad = $conn->prepare("SELECT authorization_status, authorization_json FROM ad_profiles WHERE analyst_id = ?");
        $ad->execute([$analyst_id]);
        if ($profile = $ad->fetch(PDO::FETCH_ASSOC)) {
            return $profile['authorization_status'] === 'current'
                ? (json_decode($profile['authorization_json'] ?? '{}', true)['modules'] ?? []) : [];
        }
    } catch (PDOException $e) {
        if ($e->getCode() !== '42S02') throw $e; // unmigrated legacy installs only
    }
    $sql = "SELECT module_key FROM analyst_modules WHERE analyst_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$analyst_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);

    try {
        $groupSql = "SELECT DISTINCT agm.module_key
                     FROM analyst_access_groups aag
                     JOIN access_groups ag ON ag.id = aag.access_group_id AND ag.is_active = 1
                     JOIN access_group_modules agm ON agm.access_group_id = ag.id
                     WHERE aag.analyst_id = ?";
        $groupStmt = $conn->prepare($groupSql);
        $groupStmt->execute([$analyst_id]);
        $rows = array_merge($rows, $groupStmt->fetchAll(PDO::FETCH_COLUMN));
    } catch (Exception $e) {
        // Older databases may not have group tables until db_verify/migrations run.
    }

    $rows = array_values(array_unique(array_filter($rows)));
    if (empty($rows)) {
        return null; // No restrictions — full access
    }

    if (!in_array('system', $rows, true)) {
        $rows[] = 'system';
    }

    return $rows;
}
?>
