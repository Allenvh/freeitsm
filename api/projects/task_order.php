<?php
/**
 * POST {project_id, stage_id|null, ids: [task ids, top first]} - the order of the
 * tasks in one Plan lane (GH #184). Needs the right to change the project.
 */
require_once __DIR__ . '/../../includes/projects/api_bootstrap.php';
projectApiRequirePost();

projectApiRun(function () use ($conn, $ctx) {
    $in = projectApiBody();
    $stageId = isset($in['stage_id']) && $in['stage_id'] !== null && $in['stage_id'] !== '' ? (int)$in['stage_id'] : null;
    ProjectsService::orderTasks($conn, $ctx, (int)($in['project_id'] ?? 0), $stageId, is_array($in['ids'] ?? null) ? $in['ids'] : []);
    projectApiOk();
});
