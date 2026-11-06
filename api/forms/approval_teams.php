<?php
/**
 * API: the teams a catalogue item can be approved by (GH #220).
 *
 * GET -> { success, teams: [{ id, name, member_count }] }
 *
 * Active teams with how many active members each has, for the Approval settings
 * window - so it can say "this team has 1 member" before somebody asks for 2
 * approvals. Its own endpoint rather than api/tickets/get_teams.php, which needs
 * Tickets: a forms designer may have Forms alone.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');
requireModuleAccessJson('forms');

try {
    $conn = connectToDatabase();
    $teams = $conn->query(
        "SELECT t.id, t.name,
                (SELECT COUNT(*) FROM analyst_teams at JOIN analysts a ON a.id = at.analyst_id AND COALESCE(a.is_active, 1) = 1
                  WHERE at.team_id = t.id) AS member_count
           FROM teams t WHERE t.is_active = 1 ORDER BY t.name"
    )->fetchAll(PDO::FETCH_ASSOC);
    foreach ($teams as &$t) { $t['id'] = (int)$t['id']; $t['member_count'] = (int)$t['member_count']; }
    unset($t);
    echo json_encode(['success' => true, 'teams' => $teams]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
