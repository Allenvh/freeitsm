<?php
/**
 * May this analyst open this task INSIDE this ticket? (PR #212, by @Dr4inder)
 *
 * The ticket workspace's Work tab opens a linked task's own editor in a frame
 * (tasks/embedded.php). The frame is the real Tasks page, writing through the real
 * Tasks endpoints, so it needs no permission of its own - the write endpoints
 * still decide every write. What this adds is that the frame stays TRUE TO ITS
 * TICKET: it only ever shows a task linked to the ticket it sits in, or one of
 * that task's subtasks.
 *
 * Checked by tasks/embedded.php when the frame opens, and again by
 * api/tasks/get.php on every read from inside it (embed_root_id + ticket_id), so
 * unlinking the task, or losing access to it, takes effect on the next click.
 *
 * @param int $taskId    the task to show - the linked task, or a subtask under it
 * @param int $rootId    the task linked to the ticket (tasks.ticket_id)
 * @param int $ticketId  the ticket the workspace is showing
 *
 * @wiki Tasks-on-Tickets-Developer-Guide
 */
function taskEmbedCanOpen(PDO $conn, int $analystId, int $taskId, int $rootId, int $ticketId): bool
{
    if ($taskId <= 0 || $rootId <= 0 || $ticketId <= 0) return false;

    // The ticket, the linked task and the task asked for, each by its own
    // module's rules (company scope, departments, confidential...).
    if (!analystCanAccessTicket($conn, $analystId, $ticketId)
        || !analystCanAccessTask($conn, $analystId, $rootId)
        || !analystCanAccessTask($conn, $analystId, $taskId)) return false;

    // The root really is linked to this ticket, and the ticket is not in the trash.
    $root = $conn->prepare('SELECT t.id FROM tasks t JOIN tickets tk ON tk.id = t.ticket_id
                            WHERE t.id = ? AND tk.id = ? AND tk.deleted_datetime IS NULL');
    $root->execute([$rootId, $ticketId]);
    if (!$root->fetchColumn()) return false;

    // Walk up from the task asked for until we reach the root. Every task on the
    // way must be one the analyst may see: a subtask is only reachable through
    // parents they can open, as it is on the board. The step limit and the
    // seen-list stop a corrupted parent chain (a loop) from spinning forever.
    $parent = $conn->prepare('SELECT parent_task_id FROM tasks WHERE id = ?');
    $seen = [];
    while ($taskId !== $rootId) {
        if (isset($seen[$taskId]) || count($seen) >= 100 || !analystCanAccessTask($conn, $analystId, $taskId)) return false;
        $seen[$taskId] = true;
        $parent->execute([$taskId]);
        $taskId = (int)$parent->fetchColumn();
        if (!$taskId) return false;   // reached the top without meeting the root
    }
    return true;
}
