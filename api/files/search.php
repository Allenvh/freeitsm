<?php
/**
 * GET ?q= - find folders and files by name, anywhere this person can see.
 * Names only (not file contents). Plain text matches anywhere in the name; with
 * * or ? it is a Windows-style wildcard over the whole name (*.png, report*,
 * invoice-202?-*), as in the folder filter box (nameMatcher() in files-desktop.js). Results are limited to folders the caller
 * holds View on - the search is the same permission check as browsing, run over
 * every folder at once. Every search is audited with what was asked.
 */
require_once __DIR__ . '/../../includes/files/api_bootstrap.php';

filesApiRun(function () use ($conn, $analystId) {
    $q = trim((string)($_GET['q'] ?? ''));
    $wild = strpbrk($q, '*?') !== false;
    if (mb_strlen($q) < 2 && !$wild) filesApiOk(['folders' => [], 'items' => []]);
    $levels = FilesAcl::levels($conn, $analystId);
    if (!$levels) filesApiOk(['folders' => [], 'items' => []]);

    // LIKE's own % and _ are escaped first, so only * and ? act as wildcards.
    $esc  = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q);
    $like = $wild ? str_replace(['*', '?'], ['%', '_'], $esc) : '%' . $esc . '%';
    $re   = $wild ? '/^' . str_replace(['\*', '\?'], ['.*', '.'], preg_quote($q, '/')) . '$/iu' : null;
    $tree = FilesAcl::tree($conn);

    $folders = [];
    foreach ($tree as $id => $f) {
        if (isset($levels[$id]) && ($wild ? preg_match($re, $f['name']) : mb_stripos($f['name'], $q) !== false)) {
            $folders[] = ['id' => $id, 'name' => $f['name'], 'path' => FilesAcl::path($conn, $id), 'level' => $levels[$id],
                          'parent' => ($f['parent_id'] !== null && isset($levels[$f['parent_id']])) ? $f['parent_id'] : 0];
            if (count($folders) >= 100) break;
        }
    }

    $ids = array_keys($levels);
    $in  = implode(',', array_map('intval', $ids));
    $st = $conn->prepare("SELECT id, folder_id, name, size_bytes, COALESCE(updated_datetime, created_datetime) AS modified
                            FROM files_items
                           WHERE deleted_datetime IS NULL AND folder_id IN ($in) AND name LIKE ?
                        ORDER BY name LIMIT 200");
    $st->execute([$like]);
    $items = [];
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $items[] = ['id' => (int)$r['id'], 'name' => $r['name'], 'folder_id' => (int)$r['folder_id'],
                    'size' => (int)$r['size_bytes'], 'modified' => $r['modified'],
                    'path' => FilesAcl::path($conn, (int)$r['folder_id']), 'level' => $levels[(int)$r['folder_id']]];
    }
    filesAudit($conn, $analystId, 'search', ['path' => '(Search)'], ['q' => mb_substr($q, 0, 200), 'found' => count($folders) + count($items)]);
    filesApiOk(['folders' => $folders, 'items' => $items]);
});
