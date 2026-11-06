<?php
/**
 * Shortcuts - Windows-style links to a file or folder.
 *
 *   POST {action: create, target_type: folder|item|article, target_id, where: folder|desktop, folder_id?}
 *        folder  = beside the target, in the folder that holds it (Upload there).
 *                  Not for a top-level folder - there is nowhere beside it.
 *                  An article (Knowledge) has no folder of its own, so it goes in
 *                  folder_id instead.
 *        desktop = on the caller's own desktop. Nobody else sees it.
 *        Either way the caller must be able to see the target.
 *   POST {action: rename, id, name}   folder shortcut: Modify on its folder;
 *                                     desktop shortcut: its owner.
 *   POST {action: delete, id}         the same. The target is never touched.
 *   POST {action: place, id, to: <folder id>|desktop, copy?}  drag and drop:
 *        move it (or copy it, Ctrl) into a folder (Upload there) or onto the
 *        caller's own desktop.
 *   GET  ?desktop=1                   the caller's desktop shortcuts, each with
 *                                     whether its target can still be opened.
 *   GET  ?articles=1&q=               Knowledge articles the caller can read, for
 *                                     the "Shortcut to article" picker.
 *   GET  ?article=<id>                one of them, title and body, for the preview
 *                                     window an article shortcut opens.
 *
 * ⚠️ A SHORTCUT GRANTS NOTHING. Opening one goes through the target's own
 * permissions. In a folder listing (list.php) a shortcut whose target the viewer
 * cannot see is left out altogether - otherwise its name would tell them the
 * target exists, which is exactly what "hidden means not found" forbids.
 */
require_once __DIR__ . '/../../includes/files/api_bootstrap.php';

filesApiRun(function () use ($conn, $analystId) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['article'])) {
        // The preview window for an article shortcut. The same read check that
        // decides whether the shortcut is listed at all; a miss is "not found".
        $aid = (int)$_GET['article'];
        if (!filesArticleTarget($conn, $analystId, $aid)) filesApiFail('Article not found.');
        $st = $conn->prepare("SELECT id, title, body, COALESCE(modified_datetime, created_datetime) AS modified FROM knowledge_articles WHERE id = ?");
        $st->execute([$aid]);
        $a = $st->fetch(PDO::FETCH_ASSOC);
        if (!$a) filesApiFail('Article not found.');
        // The body is stored as HTML and shown as such - the client renders it in
        // a sandboxed frame with scripts off, never into the desktop's own DOM.
        filesApiOk(['article' => ['id' => (int)$a['id'], 'title' => $a['title'], 'body' => (string)$a['body'], 'modified' => $a['modified']]]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['articles'])) {
        // The "Shortcut to article" picker: Knowledge articles this person can
        // read, by title - newest first when nothing is typed. Knowledge's own
        // visibility clause decides, exactly as filesArticleTarget() will later.
        if (!analystCanAccessModule($conn, $analystId, 'knowledge')) filesApiFail('You do not have access to Knowledge.');
        require_once __DIR__ . '/../../includes/knowledge/visibility.php';
        [$sql, $params] = knowledgeVisibilitySql($conn, KnowledgeViewer::forAnalyst($conn, $analystId), 'a', ['no_admin_floor' => true]);
        $q = trim((string)($_GET['q'] ?? ''));
        if ($q !== '') {
            $sql .= ' AND a.title LIKE ?';
            $params[] = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';
        }
        $st = $conn->prepare("SELECT a.id, a.title, COALESCE(a.modified_datetime, a.created_datetime) AS modified
                                FROM knowledge_articles a WHERE 1 = 1" . $sql . "
                            ORDER BY " . ($q !== '' ? "a.title" : "COALESCE(a.modified_datetime, a.created_datetime) DESC") . " LIMIT 50");
        $st->execute($params);
        filesApiOk(['articles' => array_map(fn($r) => ['id' => (int)$r['id'], 'title' => $r['title'], 'modified' => $r['modified']],
                                            $st->fetchAll(PDO::FETCH_ASSOC))]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $st = $conn->prepare("SELECT id, target_type, target_id, name FROM files_shortcuts
                               WHERE analyst_id = ? AND folder_id IS NULL ORDER BY created_datetime, id");
        $st->execute([$analystId]);
        $out = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $s) {
            $t = filesShortcutTarget($conn, $analystId, $s['target_type'], (int)$s['target_id']);
            $out[] = [
                'id' => (int)$s['id'], 'name' => $s['name'], 'target_type' => $s['target_type'], 'target_id' => (int)$s['target_id'],
                // Unavailable = deleted, or no longer shared with this person. The
                // name it was given stays (they saw it when they made it); nothing
                // about the target's current state is revealed beyond "gone".
                'ok' => $t !== null,
                'folder_id' => $t['folder_id'] ?? null,
                'target_name' => $t['name'] ?? null,
                'path' => $t['path'] ?? null,
            ];
        }
        filesApiOk(['shortcuts' => $out]);
    }

    $in = filesApiBody();
    $action = (string)($in['action'] ?? '');

    if ($action === 'create') {
        $type = (string)($in['target_type'] ?? '');
        if (!in_array($type, ['folder', 'article'], true)) $type = 'item';
        $tid  = (int)($in['target_id'] ?? 0);
        $t = filesShortcutTarget($conn, $analystId, $type, $tid);
        if (!$t) filesApiFail($type === 'folder' ? 'Folder not found.' : ($type === 'article' ? 'Article not found.' : 'File not found.'));

        // An article has no copy in Files to tell apart from, so it keeps its title.
        $name = filesCleanName($type === 'article' ? $t['name'] : $t['name'] . ' - Shortcut');
        $what = $type === 'folder' ? ['folder_id' => $tid]
              : ($type === 'article' ? ['path' => '(Knowledge) / ' . $t['name']]
              : ['folder_id' => $t['folder_id'], 'item_id' => $tid, 'item_name' => $t['name']]);
        if (($in['where'] ?? '') === 'desktop') {
            $conn->prepare("INSERT INTO files_shortcuts (folder_id, analyst_id, target_type, target_id, name, created_by, created_datetime)
                            VALUES (NULL, ?, ?, ?, ?, ?, UTC_TIMESTAMP())")->execute([$analystId, $type, $tid, $name, $analystId]);
            $id = (int)$conn->lastInsertId();
            filesAudit($conn, $analystId, 'shortcut', $what, ['created' => 'desktop', 'name' => $name]);
            filesApiOk(['id' => $id, 'name' => $name, 'where' => 'desktop']);
        }

        // A file or folder's goes beside it, in the folder that holds it. An
        // article lives outside Files, so it goes in the folder the caller names.
        $home = $type === 'article' ? (int)($in['folder_id'] ?? 0)
              : ($type === 'item' ? $t['folder_id'] : (FilesAcl::tree($conn)[$tid]['parent_id'] ?? null));
        if (!$home && $type === 'article') filesApiFail('Shortcuts go inside a folder or on the desktop.');
        if (!$home) filesApiFail('A top-level folder has nowhere beside it for a shortcut. Put it on your desktop instead.');
        filesNeed($conn, $analystId, (int)$home, FilesAcl::UPLOAD, 'shortcut');
        $name = filesFreeName($conn, (int)$home, $name);
        $conn->prepare("INSERT INTO files_shortcuts (folder_id, analyst_id, target_type, target_id, name, created_by, created_datetime)
                        VALUES (?, NULL, ?, ?, ?, ?, UTC_TIMESTAMP())")->execute([(int)$home, $type, $tid, $name, $analystId]);
        $id = (int)$conn->lastInsertId();
        filesAudit($conn, $analystId, 'shortcut', ['folder_id' => (int)$home, 'item_name' => $name],
                   ['created' => 'folder', 'target_type' => $type, 'target_id' => $tid, 'target' => $t['path']]);
        filesApiOk(['id' => $id, 'name' => $name, 'where' => 'folder', 'folder_id' => (int)$home]);
    }

    $id = (int)($in['id'] ?? 0);
    $st = $conn->prepare("SELECT * FROM files_shortcuts WHERE id = ?");
    $st->execute([$id]);
    $s = $st->fetch(PDO::FETCH_ASSOC);
    if (!$s) filesApiFail('Shortcut not found.');
    if ($s['folder_id'] === null) {
        if ((int)$s['analyst_id'] !== $analystId) filesApiFail('Shortcut not found.');   // someone else's desktop
    } else {
        // In a folder: invisible (so "not found") unless the caller can see the
        // folder AND the target, and changing it needs Modify on the folder.
        if (!isset(FilesAcl::tree($conn)[(int)$s['folder_id']]) || !filesShortcutTarget($conn, $analystId, $s['target_type'], (int)$s['target_id'])) {
            filesApiFail('Shortcut not found.');
        }
        // Copying (Ctrl-drag) only reads it; everything else changes it.
        $readOnly = $action === 'place' && !empty($in['copy']);
        filesNeed($conn, $analystId, (int)$s['folder_id'], $readOnly ? FilesAcl::VIEW : FilesAcl::MODIFY, 'shortcut');
    }
    $where = $s['folder_id'] === null ? ['path' => '(Desktop) / ' . $s['name']] : ['folder_id' => (int)$s['folder_id'], 'item_name' => $s['name']];

    if ($action === 'place') {
        // Drag and drop: move (or with copy, duplicate) this shortcut to a folder
        // or to the caller's desktop. Moving needs what deleting needs (checked
        // above for a folder shortcut; a desktop one is the caller's own); the
        // destination needs Upload, or is the caller's own desktop.
        $copy = !empty($in['copy']);
        $to   = $in['to'] ?? '';
        $t = filesShortcutTarget($conn, $analystId, $s['target_type'], (int)$s['target_id']);
        if (!$t) filesApiFail('What this shortcut points to is no longer available.');
        if ($to === 'desktop') {
            if (!$copy && $s['folder_id'] === null) filesApiOk(['id' => $id]);   // already there
            $name = $s['name'];
            if ($copy) {
                $conn->prepare("INSERT INTO files_shortcuts (folder_id, analyst_id, target_type, target_id, name, created_by, created_datetime)
                                VALUES (NULL, ?, ?, ?, ?, ?, UTC_TIMESTAMP())")->execute([$analystId, $s['target_type'], (int)$s['target_id'], $name, $analystId]);
                $newId = (int)$conn->lastInsertId();
            } else {
                $conn->prepare("UPDATE files_shortcuts SET folder_id = NULL, analyst_id = ? WHERE id = ?")->execute([$analystId, $id]);
                $newId = $id;
            }
            filesAudit($conn, $analystId, 'shortcut', $where, [$copy ? 'copied_to' : 'moved_to' => 'desktop', 'name' => $name]);
            filesApiOk(['id' => $newId]);
        }
        $dest = (int)$to;
        if ($dest <= 0) filesApiFail('Shortcuts go inside a folder or on the desktop.');
        if (!$copy && (int)$s['folder_id'] === $dest) filesApiOk(['id' => $id]);
        filesNeed($conn, $analystId, $dest, FilesAcl::UPLOAD, 'shortcut');
        $name = filesFreeName($conn, $dest, $s['name']);
        if ($copy) {
            $conn->prepare("INSERT INTO files_shortcuts (folder_id, analyst_id, target_type, target_id, name, created_by, created_datetime)
                            VALUES (?, NULL, ?, ?, ?, ?, UTC_TIMESTAMP())")->execute([$dest, $s['target_type'], (int)$s['target_id'], $name, $analystId]);
            $newId = (int)$conn->lastInsertId();
        } else {
            $conn->prepare("UPDATE files_shortcuts SET folder_id = ?, analyst_id = NULL, name = ? WHERE id = ?")->execute([$dest, $name, $id]);
            $newId = $id;
        }
        filesAudit($conn, $analystId, 'shortcut', ['folder_id' => $dest, 'item_name' => $name],
                   [$copy ? 'copied_from' : 'moved_from' => $s['folder_id'] === null ? 'desktop' : FilesAcl::path($conn, (int)$s['folder_id'])]);
        filesApiOk(['id' => $newId, 'folder_id' => $dest]);
    }

    if ($action === 'rename') {
        $name = filesCleanName((string)($in['name'] ?? ''));
        if ($name === '') filesApiFail('Please give the shortcut a name.');
        if ($name === $s['name']) filesApiOk(['name' => $name]);
        if ($s['folder_id'] !== null && filesNameTaken($conn, (int)$s['folder_id'], $name, 'shortcut', $id)) {
            filesApiFail('There is already something called "' . $name . '" here.');
        }
        $conn->prepare("UPDATE files_shortcuts SET name = ? WHERE id = ?")->execute([$name, $id]);
        filesAudit($conn, $analystId, 'shortcut', $where, ['renamed' => ['from' => $s['name'], 'to' => $name]]);
        filesApiOk(['name' => $name]);
    }

    if ($action === 'delete') {
        $conn->prepare("DELETE FROM files_shortcuts WHERE id = ?")->execute([$id]);
        filesAudit($conn, $analystId, 'shortcut', $where, ['deleted' => $s['name'], 'target_type' => $s['target_type'], 'target_id' => (int)$s['target_id']]);
        filesApiOk();
    }

    filesApiFail('Unknown action.');
});
