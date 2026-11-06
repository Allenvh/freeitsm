<?php
/**
 * Engineering wiki - where the pages live and how they are found.
 *
 * Pages are plain Markdown files under engineering-wiki/ in the install:
 *
 *   engineering-wiki/handbook/   written by people - the "why". Never touched by the scanner.
 *   engineering-wiki/code/       written by the scanner - the "what". Rebuilt on every scan.
 *
 * A page is named by its path from that folder without ".md" ("handbook/start",
 * "code/tables/leases"). The folder carries .htaccess + web.config so the files are
 * never served directly; everything reaches the browser through the API, which is
 * administrator-only like the rest of System.
 *
 * TRAP: every path that comes from a request goes through ewSafePath(). It allows
 * only a fixed character set, refuses "..", and the resolved file must still be
 * inside the wiki folder - the same three checks, in that order, everywhere.
 */

require_once __DIR__ . '/markdown.php';

const EW_SECTIONS = ['handbook', 'code'];

/** The wiki folder (absolute, no trailing slash). */
function ewRoot(): string
{
    return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'engineering-wiki';
}

/**
 * A page path from a request, made safe, or null.
 * Accepts "handbook/start", "/handbook/start.md", "code/files/api/x.php" (a dot is fine inside a name).
 *
 * @param string $p  the page path as received - untrusted request input
 */
function ewSafePath(string $p): ?string
{
    $p = trim(str_replace('\\', '/', $p), '/');
    $p = preg_replace('/\.md$/', '', $p);
    if ($p === '' || strlen($p) > 300) return null;
    if (!preg_match('#^[A-Za-z0-9_][A-Za-z0-9_./-]*$#', $p)) return null;
    if (str_contains($p, '..') || str_contains($p, '//')) return null;
    $section = explode('/', $p)[0];
    if (!in_array($section, EW_SECTIONS, true)) return null;
    return $p;
}

/**
 * The file behind a safe page path, if it exists and really is inside the wiki.
 *
 * @param string $page  a page path already passed through ewSafePath(), e.g. "handbook/start"
 */
function ewFile(string $page): ?string
{
    $root = realpath(ewRoot());
    if ($root === false) return null;
    $f = realpath($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $page) . '.md');
    if ($f === false || strncmp($f, $root . DIRECTORY_SEPARATOR, strlen($root) + 1) !== 0 || !is_file($f)) return null;
    return $f;
}

/**
 * Turn a link written on page $from into a page path (+ #anchor), or null when it
 * does not lead to a wiki page. "../tables/leases.md#columns" on code/files/x -> "code/tables/leases#columns".
 * A leading "/" means "from the wiki root" ([[wiki links]] arrive like that).
 *
 * @param string $from  the page path the link is written on, e.g. "code/files/x"
 * @param string $href  the link target as written in the Markdown, optionally with a #fragment
 */
function ewResolveLink(string $from, string $href): ?string
{
    $frag = '';
    if (($h = strpos($href, '#')) !== false) { $frag = substr($href, $h); $href = substr($href, 0, $h); }
    if ($href === '') return null;
    if (!preg_match('/\.md$/i', $href)) return null;     // only .md files are pages
    $href = rawurldecode($href);
    if ($href[0] === '/') {
        $parts = [];
        $segs = explode('/', ltrim($href, '/'));
    } else {
        $parts = explode('/', $from);
        array_pop($parts);                               // the page's own name
        $segs = explode('/', $href);
    }
    foreach ($segs as $s) {
        if ($s === '' || $s === '.') continue;
        if ($s === '..') { if (!$parts) return null; array_pop($parts); continue; }
        $parts[] = $s;
    }
    $page = ewSafePath(implode('/', $parts));
    return $page === null ? null : $page . $frag;
}

/**
 * A page, rendered.
 *
 * @param string $page  a safe page path (from ewSafePath()), e.g. "code/tables/leases"
 * @return ?array{page:string,title:string,html:string,toc:array,modified:int,section:string,generated:bool}
 */
function ewReadPage(string $page): ?array
{
    $f = ewFile($page);
    if ($f === null) return null;
    $md = (string)file_get_contents($f);
    $r = EwMarkdown::render($md, fn(string $href) => ewResolveLink($page, $href));
    $section = explode('/', $page)[0];
    return [
        'page'      => $page,
        'title'     => $r['title'] ?? ewNiceName($page),
        'html'      => $r['html'],
        'toc'       => $r['toc'],
        'modified'  => (int)filemtime($f),
        'section'   => $section,
        'generated' => $section === 'code',
    ];
}

/**
 * "code/tables/lease_assets" -> "lease_assets"; used when a page has no # title.
 *
 * @param string $page  a page path; an "index" page is named after its folder
 */
function ewNiceName(string $page): string
{
    $base = basename($page);
    return $base === 'index' ? basename(dirname($page)) : $base;
}

/**
 * The title of a page without rendering it: the first "# " line, else its file name.
 *
 * @param string $file  the absolute path of the page's .md file (only its first 8 lines are read)
 * @param string $page  the page path, used for the fallback name
 */
function ewTitleOf(string $file, string $page): string
{
    $h = @fopen($file, 'rb');
    if ($h) {
        for ($k = 0; $k < 8 && ($line = fgets($h)) !== false; $k++) {
            if (preg_match('/^#\s+(.+?)\s*#*\s*$/', $line, $m)) { fclose($h); return trim(str_replace(['`', '*'], '', $m[1])); }
            if (trim($line) !== '' && $line[0] !== '<' && $line[0] !== '>') break;
        }
        fclose($h);
    }
    return ewNiceName($page);
}

/**
 * One folder of the page tree, for the sidebar (it opens folder by folder, because
 * the code map runs to thousands of pages).
 * @param string $dir  the folder's page path, e.g. "code/tables"; '' lists the top-level sections
 * @return array{dirs:array<int,array{path:string,name:string}>, pages:array<int,array{page:string,title:string}>}
 */
function ewListDir(string $dir): array
{
    $out = ['dirs' => [], 'pages' => []];
    if ($dir === '') {
        foreach (EW_SECTIONS as $s) if (is_dir(ewRoot() . '/' . $s)) $out['dirs'][] = ['path' => $s, 'name' => $s];
        return $out;
    }
    $safe = ewSafePath($dir);
    if ($safe === null) return $out;
    $root = realpath(ewRoot());
    $abs = realpath(ewRoot() . '/' . $safe);
    if ($root === false || $abs === false || strncmp($abs, $root, strlen($root)) !== 0 || !is_dir($abs)) return $out;
    $items = scandir($abs) ?: [];
    foreach ($items as $it) {
        if ($it[0] === '.' || $it[0] === '_') continue;
        $full = $abs . DIRECTORY_SEPARATOR . $it;
        if (is_dir($full)) {
            $out['dirs'][] = ['path' => $safe . '/' . $it, 'name' => $it];
        } elseif (substr($it, -3) === '.md') {
            $page = $safe . '/' . substr($it, 0, -3);
            $out['pages'][] = ['page' => $page, 'title' => ewTitleOf($full, $page)];
        }
    }
    // index first, then by title
    usort($out['pages'], fn($a, $b) => (basename($b['page']) === 'index') <=> (basename($a['page']) === 'index') ?: strnatcasecmp($a['title'], $b['title']));
    usort($out['dirs'], fn($a, $b) => strnatcasecmp($a['name'], $b['name']));
    return $out;
}

/**
 * Search every page for words. Titles count more than bodies; up to $limit hits.
 * @param string $q  the search text as typed; split on spaces, every word must match, under 2 characters gives []
 * @param int $limit  the most hits to return
 * @return array<int,array{page:string,title:string,snippet:string}>
 */
function ewSearch(string $q, int $limit = 60): array
{
    $q = trim($q);
    if (mb_strlen($q) < 2) return [];
    $words = array_values(array_filter(preg_split('/\s+/', mb_strtolower($q))));
    $root = ewRoot();
    if (!is_dir($root)) return [];
    $hits = [];
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if (substr($file->getFilename(), -3) !== '.md') continue;
        $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1, -3));
        if (ewSafePath($rel) === null) continue;
        $text = (string)file_get_contents($file->getPathname());
        $low = mb_strtolower($text);
        $title = ewTitleOf($file->getPathname(), $rel);
        $tlow = mb_strtolower($title . ' ' . $rel);
        $score = 0;
        foreach ($words as $w) {
            $inTitle = mb_strpos($tlow, $w) !== false;
            $inBody = mb_strpos($low, $w) !== false;
            if (!$inTitle && !$inBody) { $score = 0; break; }
            $score += ($inTitle ? 10 : 0) + ($inBody ? 1 : 0);
        }
        if ($score === 0) continue;
        $pos = mb_strpos($low, $words[0]);
        $snip = $pos === false ? '' : mb_substr($text, max(0, $pos - 60), 180);
        $snip = trim(preg_replace('/[#*`>|_\[\]]+|\s+/', ' ', $snip));
        $hits[] = ['page' => $rel, 'title' => $title, 'snippet' => $snip, 'score' => $score + (str_starts_with($rel, 'handbook/') ? 3 : 0)];
    }
    usort($hits, fn($a, $b) => $b['score'] <=> $a['score'] ?: strnatcasecmp($a['title'], $b['title']));
    return array_map(fn($h) => ['page' => $h['page'], 'title' => $h['title'], 'snippet' => $h['snippet']], array_slice($hits, 0, $limit));
}
