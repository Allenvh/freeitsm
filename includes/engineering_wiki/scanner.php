<?php
/**
 * Engineering wiki - the scanner that writes the Code map (engineering-wiki/code/).
 *
 * It reads FreeITSM's own PHP and JavaScript and writes one Markdown page per file,
 * function, class and database table, plus indexes, the API endpoint list and every
 * TRAP: comment. It has no AI: it works from PHP's own tokenizer, the SQL strings it
 * finds, the schema in includes/db_verify_schema.php, and comments written to the
 * convention in engineering-wiki/handbook/comments.md.
 *
 * It is INCREMENTAL and RESUMABLE. What it learnt about each file is kept in
 * code/_scan/manifest.json with that file's modified time, size and SHA-1; a rescan
 * re-reads only files whose fingerprint changed, picks up new files and forgets
 * deleted ones. The work runs in steps (list -> parse -> write) under a time budget,
 * and the queue is saved after every batch, so a scan stopped half way - a closed
 * tab, a timed-out request - carries on from where it stopped.
 *
 * Run it from System -> Engineering wiki (Rescan), or from the command line:
 *   php scripts/engineering-wiki-scan.php
 *
 * TRAP: the manifest is machine-specific (modified times differ per checkout), so it
 * is never shipped - only the pages are. A fresh install's first Rescan re-reads
 * everything once; the SHA-1 check stops a mere change of modified time from
 * counting as a change after that.
 */

require_once __DIR__ . '/store.php';

final class EwScanner
{
    /** Folders that are never code. Anything whose .htaccess denies all is skipped as well (upload folders). */
    private const SKIP_DIRS = ['.git', 'vendor', 'node_modules', 'engineering-wiki', 'lang', 'tests', 'local', 'docs',
                               'database', 'uploads', 'recordings', 'logs', 'data', 'cache', 'tmp', 'releases', 'samples', 'system-wiki',
                               'tinymce'];   // third-party editor - not ours to document
    private const VERSION = 10;   // bump when the parse output changes shape - forces a full re-read

    private string $app;
    private string $out;
    private string $stateDir;
    private array $manifest = ['version' => self::VERSION, 'files' => []];
    private array $state = [];
    private array $files = [];     // every file's parse, while the pages are written
    private float $deadline = 0;

    /** Sets the app root, the output folder (engineering-wiki/code) and the folder that holds the scan state. */
    public function __construct()
    {
        $this->app = dirname(__DIR__, 2);
        $this->out = ewRoot() . '/code';
        $this->stateDir = $this->out . '/_scan';
    }

    // ================================================================ driving

    /**
     * Do up to $seconds of work. Call again until 'done' is true.
     *
     * @param float $seconds  time budget in seconds; parsing stops at it, a write phase once started runs to the end
     * @param bool $fresh  true drops saved progress (state.json) and lists again; unchanged files are still skipped
     * @return array{done:bool, phase:string, done_files:int, total_files:int, changed:int, message:string}
     */
    public function step(float $seconds = 8.0, bool $fresh = false): array
    {
        $this->deadline = microtime(true) + $seconds;
        $this->ensureDirs();
        $lock = fopen($this->stateDir . '/lock', 'c');
        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            return $this->report('busy', 'Another scan is running.');
        }
        try {
            $this->loadManifest();
            $this->state = $fresh ? [] : $this->readJson('state.json', []);
            if (empty($this->state['phase'])) $this->state = ['phase' => 'list', 'queue' => [], 'total' => 0, 'parsed' => 0, 'changed' => 0, 'started' => time()];

            if ($this->state['phase'] === 'list') $this->phaseList();
            if ($this->state['phase'] === 'parse') $this->phaseParse();
            if ($this->state['phase'] === 'write' && microtime(true) < $this->deadline + 30) $this->phaseWrite();

            $this->saveState();
            return $this->report($this->state['phase'], '');
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * Run to the end (command line).
     *
     * @param ?callable $progress  called after every 20-second step with that step's report() array; null for none
     */
    public function runAll(?callable $progress = null): array
    {
        do {
            $r = $this->step(20.0);
            if ($progress) $progress($r);
        } while (!$r['done'] && $r['phase'] !== 'busy');
        return $r;
    }

    /**
     * Builds the progress answer step() returns - phase, files done of total, files changed, pages written and a message.
     *
     * @param string $phase  'list', 'parse', 'write', 'done', or 'busy' when another scan holds the lock
     * @param string $message  a line to show the person running the scan; '' when there is nothing to say
     */
    private function report(string $phase, string $message): array
    {
        return [
            'done'        => $phase === 'done',
            'phase'       => $phase,
            'done_files'  => (int)($this->state['parsed'] ?? 0),
            'total_files' => (int)($this->state['total'] ?? 0),
            'changed'     => (int)($this->state['changed'] ?? 0),
            'pages'       => (int)($this->state['pages'] ?? 0),
            'message'     => $message,
        ];
    }

    /** Creates the output and state folders if needed; throws when the web server cannot write to them. */
    private function ensureDirs(): void
    {
        foreach ([$this->out, $this->stateDir] as $d) {
            if (!is_dir($d) && !@mkdir($d, 0775, true)) throw new RuntimeException("Cannot create $d - the web server needs write access to engineering-wiki/code/.");
        }
        if (!is_writable($this->out)) throw new RuntimeException('The web server cannot write to engineering-wiki/code/.');
    }

    // ================================================================ phase 1: what changed

    /**
     * Phase 1 - walks the app and queues every file that is new or whose size or SHA-1 changed since the manifest, and drops deleted files from it.
     */
    private function phaseList(): void
    {
        $seen = [];
        $queue = [];
        $this->walk($this->app, '', function (string $rel, string $abs) use (&$seen, &$queue) {
            $seen[$rel] = true;
            $mt = filemtime($abs); $sz = filesize($abs);
            $old = $this->manifest['files'][$rel] ?? null;
            if ($old && $old['mtime'] === $mt && $old['size'] === $sz) return;
            if ($old && $old['size'] === $sz && $old['sha1'] === sha1_file($abs)) {      // touched, not changed
                $this->manifest['files'][$rel]['mtime'] = $mt;
                return;
            }
            $queue[] = $rel;
        });
        foreach (array_keys($this->manifest['files']) as $rel) {
            if (!isset($seen[$rel])) unset($this->manifest['files'][$rel]);              // deleted since last scan
        }
        $this->state['queue'] = $queue;
        $this->state['total'] = count($queue);
        $this->state['changed'] = count($queue);
        $this->state['parsed'] = 0;
        $this->state['phase'] = 'parse';
        $this->saveManifest();
    }

    /**
     * Calls $fn for every .php and .js file under a folder, skipping SKIP_DIRS, hidden folders, folders whose .htaccess denies all, minified scripts and files over 1.5 MB.
     *
     * @param string $abs  the folder's absolute path
     * @param string $rel  the same folder from the app root; '' for the root, whose own .htaccess is not checked
     * @param callable $fn  called as fn(string $rel, string $abs) for each file found
     */
    private function walk(string $abs, string $rel, callable $fn): void
    {
        if ($rel !== '' && is_file($abs . '/.htaccess') && preg_match('/Require\s+all\s+denied|Deny\s+from\s+all/i', (string)file_get_contents($abs . '/.htaccess'))) return;
        foreach (scandir($abs) ?: [] as $name) {
            if ($name === '.' || $name === '..') continue;
            $a = $abs . '/' . $name;
            $r = $rel === '' ? $name : $rel . '/' . $name;
            if (is_dir($a)) {
                if ($name[0] === '.' || in_array($name, self::SKIP_DIRS, true) || $name === 'vendor') continue;
                $this->walk($a, $r, $fn);
            } elseif (preg_match('/\.(php|js)$/', $name) && !preg_match('/\.min\.js$/', $name)) {
                if (filesize($a) > 1500000) continue;                                        // generated or bundled - not ours to document
                $fn($r, $a);
            }
        }
    }

    // ================================================================ phase 2: read each changed file

    /**
     * Phase 2 - parses queued files until the time budget runs out, storing each file's parse in the manifest and saving progress every few seconds.
     */
    private function phaseParse(): void
    {
        $sinceSave = microtime(true);
        while ($this->state['queue']) {
            if (microtime(true) > $this->deadline) break;
            $rel = array_shift($this->state['queue']);
            $abs = $this->app . '/' . $rel;
            if (is_file($abs)) {
                $src = (string)file_get_contents($abs);
                $data = substr($rel, -3) === '.js' ? $this->parseJs($src, $rel) : $this->parsePhp($src, $rel);
                $this->manifest['files'][$rel] = ['mtime' => filemtime($abs), 'size' => filesize($abs), 'sha1' => sha1($src), 'data' => $data];
            }
            $this->state['parsed']++;
            if (microtime(true) - $sinceSave > 3) { $this->saveManifest(); $this->saveState(); $sinceSave = microtime(true); }
        }
        $this->saveManifest();
        if (!$this->state['queue']) $this->state['phase'] = 'write';
    }

    /**
     * Everything worth knowing about one PHP file.
     *
     * @param string $src  the file's contents
     * @param string $rel  the file's path from the app root - includes and script srcs are resolved from it
     */
    private function parsePhp(string $src, string $rel): array
    {
        $d = ['kind' => 'php', 'doc' => null, 'functions' => [], 'classes' => [], 'includes' => [], 'calls' => [], 'sql' => [],
              'traps' => [], 'inputs' => [], 'guards' => [], 'scripts' => [], 'session' => [], 'lines' => substr_count($src, "\n") + 1];
        try { $tokens = token_get_all($src); } catch (Throwable) { return $d; }
        $n = count($tokens);
        $sig = function (int $i, int $dir) use ($tokens, $n) {     // next/previous significant token index
            for ($k = $i + $dir; $k >= 0 && $k < $n; $k += $dir) {
                $t = $tokens[$k];
                if (is_array($t) && in_array($t[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) continue;
                return $k;
            }
            return -1;
        };
        $id = fn($t) => is_array($t) ? $t[0] : $t;
        $txt = fn($t) => is_array($t) ? $t[1] : $t;
        $line = 1;
        $depth = 0;
        $class = null; $classDepth = -1;
        $fnStack = [];                 // [name, depth]
        $pendingDoc = null;
        $sqlNotes = [];                // line => "SQL:" text
        $sawCode = false;
        $lineRun = [];                 // the // lines (or /* */ block) directly above what comes next
        $topRun = []; $topDone = false; // the comment group at the very top of the file

        // SQL: comments first, so a query can find the note above it or on its line.
        foreach ($tokens as $t) {
            if (is_array($t) && ($t[0] === T_COMMENT) && preg_match('/SQL:\s*(.+)/', $t[1], $m)) {
                $sqlNotes[$t[2]] = trim(preg_replace('#\*/\s*$#', '', $m[1]));
            }
        }

        for ($i = 0; $i < $n; $i++) {
            $t = $tokens[$i];
            $tid = $id($t);
            if (is_array($t)) $line = $t[2];

            if ($tid === T_DOC_COMMENT) {
                if (!$sawCode && $d['doc'] === null) {
                    // TRAP: the first doc block in a file is ALWAYS the file's description.
                    // It used to go to the next function/class instead whenever one came
                    // next, so files with a proper header showed no summary. It ALSO
                    // describes that declaration when nothing else sits between them (the
                    // common "one header, then the class" shape) - but not when another
                    // comment follows, because that comment is the declaration's own.
                    $d['doc'] = $this->parseDoc($t[1]);
                    $k = $i + 1;
                    while ($k < $n && $id($tokens[$k]) === T_WHITESPACE) $k++;
                    $nt = $k < $n ? $id($tokens[$k]) : null;
                    if (in_array($nt, [T_FUNCTION, T_CLASS, T_INTERFACE, T_TRAIT, T_ABSTRACT, T_FINAL, T_READONLY], true)) $pendingDoc = $t[1];
                    continue;
                }
                $pendingDoc = $t[1];
            }
            if ($tid === T_COMMENT) {
                $prevWs = $i > 0 && $id($tokens[$i - 1]) === T_WHITESPACE ? substr_count($tokens[$i - 1][1], "
") : 0;
                if ($prevWs > 1) $lineRun = [];
                $lineRun[] = $t[1];
                if (!$sawCode && !$topDone) { if ($prevWs > 1 && $topRun) $topDone = true; else $topRun[] = $t[1]; }
            }
            if ($tid === T_COMMENT || $tid === T_DOC_COMMENT) {
                if (stripos($t[1], 'TRAP:') !== false) {
                    // A // trap usually runs on over the next // lines - take the whole run.
                    $text = $t[1]; $k = $i + 1;
                    if ($tid === T_COMMENT && str_starts_with(ltrim($t[1]), '//')) {
                        while ($k + 1 < $n && $id($tokens[$k]) === T_WHITESPACE && substr_count($tokens[$k][1], "\n") <= 1
                               && $id($tokens[$k + 1]) === T_COMMENT && str_starts_with(ltrim($tokens[$k + 1][1]), '//')) {
                            $text .= "\n" . $tokens[$k + 1][1]; $k += 2;
                        }
                    }
                    $d['traps'][] = ['line' => $line, 'fn' => $fnStack ? end($fnStack)[0] : null, 'text' => $this->trapText($text)];
                }
                continue;
            }
            if ($tid === T_WHITESPACE || $tid === T_OPEN_TAG || $tid === T_INLINE_HTML) {
                if ($tid === T_WHITESPACE && substr_count($t[1], "
") > 1) $lineRun = [];
                if ($tid === T_INLINE_HTML && preg_match_all('#<script[^>]+src=["\']([^"\'?]+\.js)#i', $t[1], $sm)) {
                    foreach ($sm[1] as $s) $d['scripts'][] = $this->resolveRel($rel, $s);
                }
                continue;
            }
            if (!$sawCode && !in_array($tid, [T_DECLARE, T_NAMESPACE, ';'], true)) {
                $sawCode = true;
                // No /** */ header: a /* */ block or // lines at the top describe the file instead.
                if ($d['doc'] === null && $topRun && !in_array($tid, [T_FUNCTION, T_CLASS, T_INTERFACE, T_TRAIT, T_ABSTRACT, T_FINAL], true)) $d['doc'] = $this->lineDoc($topRun);
            }

            if ($tid === '{' || $tid === T_CURLY_OPEN || $tid === T_DOLLAR_OPEN_CURLY_BRACES) { $depth++; continue; }
            if ($tid === '}') {
                $depth--;
                if ($fnStack && end($fnStack)[1] === $depth) array_pop($fnStack);
                if ($class !== null && $depth === $classDepth) { $class = null; $classDepth = -1; }
                continue;
            }

            // class / interface / trait
            if (in_array($tid, [T_CLASS, T_INTERFACE, T_TRAIT], true)) {
                $p = $sig($i, -1);
                if ($p >= 0 && in_array($id($tokens[$p]), [T_DOUBLE_COLON, T_NEW], true)) continue;    // Foo::class, new class
                $nx = $sig($i, 1);
                if ($nx >= 0 && $id($tokens[$nx]) === T_STRING) {
                    $class = $tokens[$nx][1];
                    $classDepth = $depth;
                    $d['classes'][] = ['name' => $class, 'line' => $line, 'doc' => $pendingDoc ? $this->parseDoc($pendingDoc) : ($lineRun ? $this->lineDoc($lineRun) : null),
                                       'kind' => strtolower($txt($t))];
                }
                $pendingDoc = null;
                continue;
            }

            // function declaration
            if ($tid === T_FUNCTION) {
                $nx = $sig($i, 1);
                if ($nx >= 0 && $id($tokens[$nx]) === '&') $nx = $sig($nx, 1);
                if ($nx < 0 || $id($tokens[$nx]) !== T_STRING) { $pendingDoc = null; continue; }      // a closure
                $name = $tokens[$nx][1];
                $op = $sig($nx, 1);                                                                   // "("
                [$params, $close] = $this->params($tokens, $op);
                $ret = '';
                $k = $sig($close, 1);
                if ($k >= 0 && $id($tokens[$k]) === ':') {
                    for ($k = $k + 1; $k < $n && !in_array($id($tokens[$k]), ['{', ';'], true); $k++) $ret .= $txt($tokens[$k]);
                }
                $d['functions'][] = ['name' => $name, 'class' => $class, 'line' => $line, 'params' => $params, 'return' => trim($ret),
                                     'doc' => $pendingDoc ? $this->parseDoc($pendingDoc) : ($lineRun ? $this->lineDoc($lineRun) : null),
                                     'static' => $this->hasModifier($tokens, $i, $sig, T_STATIC), 'visibility' => $this->visibility($tokens, $i, $sig)];
                $pendingDoc = null;
                // find its body
                for ($k = $close; $k < $n && !in_array($id($tokens[$k]), ['{', ';'], true); $k++);
                if ($k < $n && $id($tokens[$k]) === '{') {
                    $fnStack[] = [($class ? $class . '::' : '') . $name, $depth];
                    $depth++;
                    $i = $k;
                }
                continue;
            }
            if (!in_array($tid, [T_PUBLIC, T_PRIVATE, T_PROTECTED, T_STATIC, T_ABSTRACT, T_FINAL, T_READONLY, T_ATTRIBUTE], true)) { $pendingDoc = null; $lineRun = []; }

            $inFn = $fnStack ? end($fnStack)[0] : null;

            // include / require
            if (in_array($tid, [T_REQUIRE, T_REQUIRE_ONCE, T_INCLUDE, T_INCLUDE_ONCE], true)) {
                $expr = [];
                for ($k = $i + 1; $k < $n && $id($tokens[$k]) !== ';'; $k++) $expr[] = $tokens[$k];
                $path = $this->evalPath($expr, $rel);
                if ($path !== null) $d['includes'][] = $path;
                continue;
            }

            // $_GET['x'] / $_POST['x'] / $_REQUEST['x']
            if ($tid === T_VARIABLE && $t[1] === '$_SESSION') {
                $k = $sig($i, 1);
                if ($k >= 0 && $id($tokens[$k]) === '[' && ($k2 = $sig($k, 1)) >= 0 && $id($tokens[$k2]) === T_CONSTANT_ENCAPSED_STRING) $d['session'][trim($tokens[$k2][1], '\'"')] = true;
                continue;
            }
            if ($tid === T_VARIABLE && in_array($t[1], ['$_GET', '$_POST', '$_REQUEST'], true)) {
                $k = $sig($i, 1);
                if ($k >= 0 && $id($tokens[$k]) === '[') {
                    $k2 = $sig($k, 1);
                    if ($k2 >= 0 && $id($tokens[$k2]) === T_CONSTANT_ENCAPSED_STRING) {
                        $d['inputs'][substr($t[1], 2) . ':' . trim($tokens[$k2][1], '\'"')] = true;
                    }
                }
                continue;
            }

            // calls: name(  /  Class::name(
            if ($tid === T_STRING || $tid === T_NAME_QUALIFIED || $tid === T_NAME_FULLY_QUALIFIED) {
                $nx = $sig($i, 1);
                if ($nx < 0) continue;
                $prev = $sig($i, -1);
                $pid = $prev >= 0 ? $id($tokens[$prev]) : null;
                if ($id($tokens[$nx]) === T_DOUBLE_COLON) {
                    $m = $sig($nx, 1);
                    if ($m >= 0 && $id($tokens[$m]) === T_STRING && ($p2 = $sig($m, 1)) >= 0 && $id($tokens[$p2]) === '(') {
                        $call = ltrim($t[1], '\\') . '::' . $tokens[$m][1];
                        $d['calls'][] = [$call, $inFn, $line];
                        $i = $m;
                    }
                    continue;
                }
                if ($id($tokens[$nx]) !== '(' || in_array($pid, [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR, T_NEW, T_FUNCTION, T_DOUBLE_COLON], true)) continue;
                $fname = ltrim($t[1], '\\');
                $d['calls'][] = [$fname, $inFn, $line];
                // How is this endpoint protected? The first string argument of a require*() guard.
                if (preg_match('/^require\w*(Admin|Access|Capability|Auth|Login|Session|Cap)\w*$/i', $fname) || in_array($fname, ['requireAdminJson', 'requireAdmin'], true)) {
                    $a = $sig($nx, 1);
                    $arg = ($a >= 0 && $id($tokens[$a]) === T_CONSTANT_ENCAPSED_STRING) ? trim($tokens[$a][1], '\'"') : null;
                    if ($a >= 0 && in_array($id($tokens[$a]), [T_STRING, T_NAME_QUALIFIED], true) && ($dc = $sig($a, 1)) >= 0 && $id($tokens[$dc]) === T_DOUBLE_COLON) {
                        $cn = $sig($dc, 1);
                        if ($cn >= 0) $arg = $tokens[$a][1] . '::' . $txt($tokens[$cn]);
                    }
                    $d['guards'][] = [$fname, $arg, $inFn];   // inFn: a check inside a function only guards callers of that function
                }
                continue;
            }

            // SQL inside strings
            if ($tid === T_CONSTANT_ENCAPSED_STRING || $tid === T_ENCAPSED_AND_WHITESPACE || $tid === T_START_HEREDOC) {
                $s = $tid === T_START_HEREDOC ? '' : $t[1];
                if ($tid === T_START_HEREDOC) {
                    for ($k = $i + 1; $k < $n && $id($tokens[$k]) !== T_END_HEREDOC; $k++) $s .= $txt($tokens[$k]);
                    $i = $k;
                } elseif ($tid === T_ENCAPSED_AND_WHITESPACE) {
                    // "... {$x} ..." - join the pieces of one interpolated string
                    for ($k = $i + 1; $k < $n && $id($tokens[$k]) !== '"'; $k++) $s .= $txt($tokens[$k]);
                    $i = $k;
                }
                $q = $this->sqlOf($s);
                if ($q !== null) {
                    $note = $sqlNotes[$line] ?? $sqlNotes[$line - 1] ?? $sqlNotes[$line - 2] ?? null;
                    $q['line'] = $line; $q['fn'] = $inFn; $q['note'] = $note;
                    $d['sql'][] = $q;
                }
            }
        }
        $d['inputs'] = array_keys($d['inputs']);
        $d['session'] = array_keys($d['session']);
        $d['includes'] = array_values(array_unique($d['includes']));
        $d['scripts'] = array_values(array_unique(array_filter($d['scripts'])));
        // calls: keep each (callee, caller) once, first line
        $seenCall = [];
        $calls = [];
        foreach ($d['calls'] as $c) { $k = $c[0] . '|' . $c[1]; if (!isset($seenCall[$k])) { $seenCall[$k] = true; $calls[] = $c; } }
        $d['calls'] = $calls;
        return $d;
    }

    /**
     * True when the declaration at token $i carries the given modifier (static, abstract...) among the few tokens before it.
     *
     * @param array $tokens  the file's token_get_all() output
     * @param int $i  the index of the T_FUNCTION / T_CLASS token
     * @param callable $sig  parsePhp()'s significant-token finder: fn(int $i, int $dir): int, -1 when there is none
     * @param int $mod  the modifier's token id, e.g. T_STATIC
     */
    private function hasModifier(array $tokens, int $i, callable $sig, int $mod): bool
    {
        for ($k = $sig($i, -1), $g = 0; $k >= 0 && $g < 4; $k = $sig($k, -1), $g++) {
            $tid = is_array($tokens[$k]) ? $tokens[$k][0] : $tokens[$k];
            if ($tid === $mod) return true;
            if (!in_array($tid, [T_PUBLIC, T_PRIVATE, T_PROTECTED, T_STATIC, T_ABSTRACT, T_FINAL], true)) return false;
        }
        return false;
    }

    /**
     * Returns 'public', 'protected' or 'private' for the declaration at token $i, or '' when none is written.
     *
     * @param array $tokens  the file's token_get_all() output
     * @param int $i  the index of the T_FUNCTION token
     * @param callable $sig  parsePhp()'s significant-token finder: fn(int $i, int $dir): int, -1 when there is none
     */
    private function visibility(array $tokens, int $i, callable $sig): string
    {
        for ($k = $sig($i, -1), $g = 0; $k >= 0 && $g < 4; $k = $sig($k, -1), $g++) {
            $tid = is_array($tokens[$k]) ? $tokens[$k][0] : $tokens[$k];
            if ($tid === T_PRIVATE) return 'private';
            if ($tid === T_PROTECTED) return 'protected';
            if ($tid === T_PUBLIC) return 'public';
            if (!in_array($tid, [T_STATIC, T_ABSTRACT, T_FINAL], true)) break;
        }
        return '';
    }

    /**
     * The parameters between ( and the matching ).
     *
     * @param array $tokens  the file's token_get_all() output
     * @param int $open  the index of the "(" that opens the parameter list
     * @return array{0:array,1:int} [params, index of ")"]
     */
    private function params(array $tokens, int $open): array
    {
        $params = []; $cur = ['type' => '', 'name' => '', 'default' => '', 'ref' => false, 'variadic' => false];
        $depth = 0; $n = count($tokens); $inDefault = false;
        for ($k = $open; $k < $n; $k++) {
            $t = $tokens[$k]; $tid = is_array($t) ? $t[0] : $t; $tx = is_array($t) ? $t[1] : $t;
            if ($tid === '(' || $tid === '[') { $depth++; if ($depth === 1 && $tid === '(') continue; }
            if ($tid === ')' || $tid === ']') {
                $depth--;
                if ($depth === 0 && $tid === ')') { if ($cur['name'] !== '') $params[] = $cur; return [$this->tidyParams($params), $k]; }
            }
            if ($depth === 1 && $tid === ',') { if ($cur['name'] !== '') $params[] = $cur; $cur = ['type' => '', 'name' => '', 'default' => '', 'ref' => false, 'variadic' => false]; $inDefault = false; continue; }
            if (in_array($tid, [T_COMMENT, T_DOC_COMMENT], true)) {
                // A comment on a parameter's own line is its meaning: after the comma it
                // belongs to the parameter just finished, before it to the current one.
                if ($depth === 1 && $tid === T_COMMENT) {
                    $note = trim(preg_replace(['#^//+\s?#', '#^/\*+\s?#', '#\s*\*+/$#'], '', trim($tx)));
                    if ($cur['name'] !== '') $cur['note'] = trim(($cur['note'] ?? '') . ' ' . $note);
                    elseif ($params) $params[count($params) - 1]['note'] = trim(($params[count($params) - 1]['note'] ?? '') . ' ' . $note);
                }
                continue;
            }
            if ($inDefault) { $cur['default'] .= $tx; continue; }
            if ($depth === 1 && $tid === '=') { $inDefault = true; continue; }
            if ($tid === T_VARIABLE) { $cur['name'] = $tx; continue; }
            if ($tid === T_ELLIPSIS) { $cur['variadic'] = true; continue; }
            if ($tid === '&' || $tid === T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG || $tid === T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG) { $cur['ref'] = true; continue; }
            if (in_array($tid, [T_PUBLIC, T_PRIVATE, T_PROTECTED, T_READONLY], true)) continue;     // promoted properties
            if ($cur['name'] === '' && $tid !== T_WHITESPACE) $cur['type'] .= $tx;
        }
        return [$this->tidyParams($params), $k];
    }

    /**
     * Trims each parsed parameter's type and default (whitespace in a default collapsed to single spaces).
     *
     * @param array $ps  the raw parameters params() collected - type, name, default, ref, variadic and an optional note
     */
    private function tidyParams(array $ps): array
    {
        return array_map(fn($p) => ['type' => trim($p['type']), 'name' => $p['name'], 'default' => trim(preg_replace('/\s+/', ' ', $p['default'])),
                                    'ref' => $p['ref'], 'variadic' => $p['variadic'], 'note' => $p['note'] ?? ''], $ps);
    }

    /**
     * Work out the file an include points at: string pieces, __DIR__, dirname(__DIR__[, n]).
     *
     * @param array $expr  the tokens between include / require and its ";"
     * @param string $rel  the including file's path from the app root
     */
    private function evalPath(array $expr, string $rel): ?string
    {
        $dir = dirname($rel) === '.' ? '' : dirname($rel);
        $s = '';
        $n = count($expr);
        for ($k = 0; $k < $n; $k++) {
            $t = $expr[$k]; $tid = is_array($t) ? $t[0] : $t;
            if (in_array($tid, [T_WHITESPACE, '(', ')', '.', T_COMMENT], true)) continue;
            if ($tid === T_DIR) { $s .= '@DIR@' . $dir; continue; }
            if ($tid === T_CONSTANT_ENCAPSED_STRING) { $s .= trim($t[1], '\'"'); continue; }
            if ($tid === T_STRING && strtolower($t[1]) === 'dirname') {
                // dirname(__DIR__) or dirname(__DIR__, 2)
                $levels = 1; $hasDir = false;
                for ($j = $k + 1; $j < $n; $j++) {
                    $u = $expr[$j]; $uid = is_array($u) ? $u[0] : $u;
                    if ($uid === T_DIR) $hasDir = true;
                    if ($uid === T_LNUMBER) $levels = (int)$u[1];
                    if ($uid === ')') { $k = $j; break; }
                }
                if (!$hasDir) return null;
                $up = $dir;
                for ($l = 0; $l < $levels; $l++) $up = dirname($up) === '.' ? '' : dirname($up);
                $s .= '@DIR@' . $up;
                continue;
            }
            return null;   // a variable or a function we cannot evaluate - leave it out rather than guess
        }
        if ($s === '') return null;
        if (str_starts_with($s, '@DIR@')) {
            $s = substr($s, 5);
            $path = $s;
        } else {
            $path = ($dir !== '' ? $dir . '/' : '') . $s;    // relative to the file's folder (pages run from their own folder)
        }
        return $this->normalise($path);
    }

    /**
     * Resolves '.' and '..' in a relative path; null when it climbs above the app root or ends up empty.
     *
     * @param string $path  a path from the app root; backslashes are read as slashes
     */
    private function normalise(string $path): ?string
    {
        $parts = [];
        foreach (explode('/', str_replace('\\', '/', $path)) as $p) {
            if ($p === '' || $p === '.') continue;
            if ($p === '..') { if (!$parts) return null; array_pop($parts); continue; }
            $parts[] = $p;
        }
        return $parts ? implode('/', $parts) : null;
    }

    /**
     * Resolves a script src or link found in a page to a path from the app root; null for external URLs and PHP-built paths.
     *
     * @param string $from  the path, from the app root, of the file the reference is written in
     * @param string $href  the src as written - relative, "/"-rooted, a URL, or starting with an inline PHP echo
     */
    private function resolveRel(string $from, string $href): ?string
    {
        if (preg_match('#^(https?:)?//#', $href)) return null;
        $href = preg_replace('#^<\?php.*?\?>#', '', $href);
        if ($href === '' || $href[0] === '$') return null;
        $dir = dirname($from) === '.' ? '' : dirname($from);
        return $href[0] === '/' ? $this->normalise($href) : $this->normalise(($dir !== '' ? $dir . '/' : '') . $href);
    }

    /**
     * Is this string SQL? Which tables does it read and which does it write (with the columns written)?
     *
     * @param string $s  a string literal (quotes may still be on), or the joined pieces of an interpolated string / heredoc
     */
    private function sqlOf(string $s): ?array
    {
        $s = trim($s, " \t\n\r'\"");
        if (!preg_match('/^\s*\(?\s*(SELECT|INSERT|UPDATE|DELETE|REPLACE|CREATE|ALTER|DROP|TRUNCATE|SHOW|WITH)\b/i', $s, $m)
            && !preg_match('/\b(FROM|JOIN)\s+`?[a-z_][a-z0-9_]*`?\s/i', $s)) return null;
        $op = strtoupper($m[1] ?? 'SELECT');
        if ($op === 'WITH') $op = 'SELECT';
        $reads = []; $writes = [];
        if (preg_match_all('/\b(?:FROM|JOIN)\s+`?([a-z_][a-z0-9_]*)`?/i', $s, $mm)) foreach ($mm[1] as $tb) $reads[strtolower($tb)] = true;
        if (preg_match('/^\s*(?:INSERT|REPLACE)\s+(?:IGNORE\s+)?INTO\s+`?([a-z_][a-z0-9_]*)`?\s*\(([^)]*)\)/i', $s, $w)) {
            $writes[strtolower($w[1])] = ['op' => 'insert', 'cols' => array_values(array_filter(array_map(fn($c) => trim($c, " `\n\r\t"), explode(',', $w[2]))))];
        } elseif (preg_match('/^\s*(?:INSERT|REPLACE)\s+(?:IGNORE\s+)?INTO\s+`?([a-z_][a-z0-9_]*)`?/i', $s, $w)) {
            $writes[strtolower($w[1])] = ['op' => 'insert', 'cols' => []];
        } elseif (preg_match('/^\s*UPDATE\s+`?([a-z_][a-z0-9_]*)`?.*?\bSET\b(.*?)(?:\bWHERE\b|$)/is', $s, $w)) {
            preg_match_all('/(?:^|,)\s*(?:\w+\.)?`?([a-z_][a-z0-9_]*)`?\s*=/i', $w[2], $cm);
            $writes[strtolower($w[1])] = ['op' => 'update', 'cols' => array_values(array_unique($cm[1]))];
        } elseif (preg_match('/^\s*DELETE\s+(?:\w+\s+)?FROM\s+`?([a-z_][a-z0-9_]*)`?/i', $s, $w)) {
            $writes[strtolower($w[1])] = ['op' => 'delete', 'cols' => []];
            unset($reads[strtolower($w[1])]);
        } elseif (preg_match('/^\s*(?:CREATE|ALTER|DROP|TRUNCATE)\s+TABLE\s+(?:IF\s+(?:NOT\s+)?EXISTS\s+)?`?([a-z_][a-z0-9_]*)`?/i', $s, $w)) {
            $writes[strtolower($w[1])] = ['op' => strtolower($op), 'cols' => []];
        }
        if (!$reads && !$writes) return null;
        return ['op' => $op, 'reads' => array_keys($reads), 'writes' => $writes];
    }

    /**
     * A /** doc comment * / split into summary, body and @tags.
     *
     * @param string $raw  the whole doc comment as written, opening and closing markers included
     */
    private function parseDoc(string $raw): array
    {
        $raw = preg_replace(['#^/\*\*+#', '#\*+/$#'], '', $raw);
        // A one-line comment can carry its tags on the same line ("/** Summary. @return int */").
        // Split them off - only in one-liners, so prose like "add @param lines" in a longer
        // comment is never mistaken for a tag.
        if (!str_contains(trim($raw), "\n")) {
            $raw = preg_replace('/(\S)\s+(@(?:param|return|reads|writes|see|wiki|input|output|throws)\b)/', "\$1\n\$2", $raw);
        }
        $lines = array_map(fn($l) => preg_replace('/^\s*\*( ?)/', '', rtrim($l)), explode("\n", $raw));
        $text = []; $tags = [];
        foreach ($lines as $l) {
            if (preg_match('/^\s*@(\w+)\s*(.*)$/', $l, $m)) { $tags[] = [strtolower($m[1]), trim($m[2])]; continue; }
            if ($tags && trim($l) !== '' && preg_match('/^\s{2,}/', $l)) { $tags[count($tags) - 1][1] .= ' ' . trim($l); continue; }   // a tag running on
            if ($tags && trim($l) === '') continue;
            $text[] = $l;
        }
        while ($text && trim($text[0]) === '') array_shift($text);
        while ($text && trim(end($text)) === '') array_pop($text);
        $paras = preg_split('/\n\s*\n/', implode("\n", $text));
        $summary = trim(preg_replace('/\s+/', ' ', array_shift($paras) ?? ''));
        return ['summary' => $summary, 'body' => trim(implode("\n\n", $paras)), 'tags' => $tags];
    }

    /**
     * A run of // lines, or a plain block comment, used as a description when there is
     * no doc comment. Decorative rules (// ----, // ====) and a lone TRAP are not descriptions.
     *
     * @param array $comments  the raw comment texts in order - // lines, # lines or one block comment
     */
    private function lineDoc(array $comments): ?array
    {
        $lines = [];
        foreach ($comments as $c) {
            foreach (explode("
", str_replace("", '', $c)) as $l) {
                $l = preg_replace(['#^\s*(//+|\#)\s?#', '#^\s*/\*+\s?#', '#\s*\*+/\s*$#', '#^\s*\*\s?#'], '', $l);
                // Decorative rules and banners, ASCII or box-drawing (// ----, ═══ KNOWLEDGE ═══).
                if (preg_match('/^\s*[-=~*#─━═]{3,}\s*$/u', $l) || preg_match('/^\s*[-=─━═]{3,}.*[-=─━═]{3,}\s*$/u', $l)) continue;
                $lines[] = rtrim($l);
            }
        }
        while ($lines && trim($lines[0]) === '') array_shift($lines);
        if (!$lines || preg_match('/^(🔴\s*)?TRAP:/u', trim($lines[0]))) return null;
        return $this->parseDoc("/**
 * " . implode("
 * ", $lines) . "
 */");
    }

    /**
     * Cleans a TRAP: comment down to its text - comment markers stripped, from "TRAP:" to the end of its paragraph, on one line.
     *
     * @param string $raw  the comment holding TRAP: - a run of // lines joined with "\n", or a whole block comment
     */
    private function trapText(string $raw): string
    {
        $lines = explode("\n", $raw);
        $out = [];
        foreach ($lines as $l) {
            $l = preg_replace(['#^\s*/\*+\s?#', '#\s*\*+/\s*$#', '#^\s*//+\s?#', '#^\s*\*\s?#'], '', $l);
            $out[] = rtrim($l);
        }
        $t = trim(implode("\n", $out));
        $p = stripos($t, 'TRAP:');
        if ($p !== false) $t = substr($t, $p);
        // a trap inside a long block comment ends at the paragraph
        $t = preg_split('/\n\s*\n/', $t)[0];
        $t = preg_replace('/^(?:🔴\s*)?TRAP:\s*/u', '', $t);
        return trim(preg_replace('/\s*\n\s*/', ' ', $t));
    }

    /**
     * JavaScript: its functions (with /** comments) and the API endpoints it calls.
     *
     * @param string $src  the file's contents
     * @param string $rel  the file's path from the app root (not used yet)
     */
    private function parseJs(string $src, string $rel): array
    {
        $d = ['kind' => 'js', 'doc' => null, 'functions' => [], 'endpoints' => [], 'traps' => [], 'lines' => substr_count($src, "\n") + 1];
        if (preg_match('#^\s*/\*\*?(.*?)\*/#s', $src, $m)) $d['doc'] = $this->parseDoc('/**' . $m[1] . '*/');
        // Functions, line by line, in every shape the codebase writes them:
        //   function name(a)  |  name = function (a)  |  window.name = function (a)
        //   const name = (a) =>  |  const name = a =>  |  name: function (a)  |  name(a) {   (a method)
        // Its description is the /** */ block or the run of // lines directly above it.
        $lines = explode("\n", str_replace("\r", '', $src));
        $buf = []; $inBlock = false; $block = [];
        $keywords = ['if', 'for', 'while', 'switch', 'catch', 'function', 'return', 'else', 'do', 'try', 'with', 'new', 'typeof', 'await'];
        foreach ($lines as $ln => $l) {
            $t = trim($l);
            if ($inBlock) {
                $block[] = $l;
                if (str_contains($t, '*/')) { $inBlock = false; $buf = ['block', implode("\n", $block)]; }
                continue;
            }
            if (str_starts_with($t, '/*')) {
                $block = [$l];
                if (strpos($t, '*/', 2) !== false) $buf = ['block', $l]; else $inBlock = true;
                continue;
            }
            if (str_starts_with($t, '//')) { if (($buf[0] ?? '') !== 'line') $buf = ['line', []]; $buf[1][] = $t; continue; }
            if ($t === '') { $buf = []; continue; }
            $name = null; $params = '';
            if (preg_match('/^(?:export\s+)?(?:async\s+)?function\s*\*?\s*([A-Za-z_$][\w$]*)\s*\(([^)]*)\)?/', $t, $m)) { $name = $m[1]; $params = $m[2]; }
            elseif (preg_match('/^(?:(?:const|let|var)\s+)?((?:window|this|self|[A-Za-z_$][\w$]*)\.)?([A-Za-z_$][\w$]*)\s*=\s*(?:async\s+)?function\b[^(]*\(([^)]*)\)?/', $t, $m)) { $name = $m[2]; $params = $m[3]; }
            elseif (preg_match('/^(?:(?:const|let|var)\s+)?((?:window|this|self)\.)?([A-Za-z_$][\w$]*)\s*=\s*(?:async\s+)?(?:\(([^)]*)\)|([A-Za-z_$][\w$]*))\s*=>/', $t, $m)) { $name = $m[2]; $params = ($m[3] ?? '') !== '' ? $m[3] : ($m[4] ?? ''); }
            elseif (preg_match('/^([A-Za-z_$][\w$]*)\s*:\s*(?:async\s+)?function\s*\(([^)]*)\)?/', $t, $m)) { $name = $m[1]; $params = $m[2]; }
            elseif (preg_match('/^(?:static\s+)?(?:async\s+)?([A-Za-z_$][\w$]*)\s*\(([^)]*)\)\s*\{\s*$/', $t, $m) && !in_array($m[1], $keywords, true)
                   && !preg_match('/\bfunction\b|=>/', $m[2])) { $name = $m[1]; $params = $m[2]; }   // not a call like requestAnimationFrame(function () {
            if ($name !== null) {
                $doc = null;
                if (($buf[0] ?? '') === 'block') $doc = $this->parseDoc('/**' . preg_replace(['#^\s*/\*+#', '#\*+/\s*$#'], '', $buf[1]) . '*/');
                elseif (($buf[0] ?? '') === 'line') $doc = $this->lineDoc($buf[1]);
                $d['functions'][] = ['name' => $name, 'line' => $ln + 1, 'params' => trim(preg_replace('/\s+/', ' ', $params)), 'doc' => $doc];
            }
            $buf = [];
        }
        // Endpoints: literal paths, and BASE + 'x.php' where BASE is an api/ folder set in this file.
        $bases = [];
        if (preg_match_all('#(?:const|let|var)\s+([A-Z_][A-Z0-9_]*)\s*=\s*[\'"]([^\'"]*api/[^\'"]*)[\'"]#', $src, $bm)) foreach ($bm[1] as $k => $b) $bases[$b] = $bm[2][$k];
        if (preg_match_all('#[\'"`]([^\'"`\s]*api/[\w/.-]+\.php)#', $src, $em)) foreach ($em[1] as $e) $d['endpoints'][] = preg_replace('#^.*?(api/)#', '$1', $e);
        foreach ($bases as $b => $path) {
            if (preg_match_all('#\b' . preg_quote($b, '#') . '\s*\+\s*[\'"`]([\w/.-]+\.php)#', $src, $pm)) {
                foreach ($pm[1] as $p) $d['endpoints'][] = preg_replace('#^.*?(api/)#', '$1', rtrim($path, '/') . '/' . $p);
            }
        }
        $d['endpoints'] = array_values(array_unique($d['endpoints']));
        if (preg_match_all('#//\s*(?:🔴\s*)?TRAP:(.*)#u', $src, $tm, PREG_OFFSET_CAPTURE)) {
            foreach ($tm[1] as $t) $d['traps'][] = ['line' => substr_count(substr($src, 0, $t[1]), "\n") + 1, 'fn' => null, 'text' => trim($t[0])];
        }
        return $d;
    }

    // ================================================================ phase 3: write the pages

    /**
     * Phase 3 - builds every page (files, functions, classes, tables, indexes, endpoints, traps) from the manifest, writes only those that changed and deletes pages no longer produced.
     */
    private function phaseWrite(): void
    {
        @set_time_limit(300);
        $files = [];
        foreach ($this->manifest['files'] as $rel => $f) $files[$rel] = $f['data'];
        ksort($files, SORT_NATURAL | SORT_FLAG_CASE);
        $this->files = $files;
        $schema = $this->loadSchema();
        $tableSet = array_fill_keys(array_keys($schema), true);

        // --- who defines what
        $fnIndex = []; $classIndex = [];
        foreach ($files as $rel => $d) {
            if ($d['kind'] !== 'php') continue;
            foreach ($d['functions'] as $fn) {
                if ($fn['class'] === null) $fnIndex[strtolower($fn['name'])][] = ['file' => $rel] + $fn;
                else $classIndex[$fn['class']]['methods'][] = ['file' => $rel] + $fn;
            }
            foreach ($d['classes'] as $c) $classIndex[$c['name']] = ($classIndex[$c['name']] ?? []) + ['file' => $rel, 'line' => $c['line'], 'doc' => $c['doc'], 'kind' => $c['kind']];
        }

        // --- who calls what, who includes what, which JS calls which endpoint
        $callers = []; $includedBy = []; $endpointCallers = []; $scriptUsers = [];
        foreach ($files as $rel => $d) {
            if ($d['kind'] === 'js') {
                foreach ($d['endpoints'] as $e) foreach ($this->matchEndpoint($e, $files) as $ep) $endpointCallers[$ep][$rel] = true;
                continue;
            }
            foreach ($d['calls'] as [$callee, $caller]) {
                $key = strtolower($callee);
                if (str_contains($callee, '::')) { [$cls, $m] = explode('::', $callee, 2); if (!isset($classIndex[$cls])) continue; $key = $cls . '::' . strtolower($m); }
                elseif (!isset($fnIndex[$key])) continue;
                $callers[$key][$rel . '|' . ($caller ?? '')] = ['file' => $rel, 'fn' => $caller];
            }
            foreach ($d['includes'] as $inc) if (isset($files[$inc])) $includedBy[$inc][$rel] = true;
            foreach ($d['scripts'] as $s) if (isset($files[$s])) $scriptUsers[$s][$rel] = true;
        }

        // --- tables: readers and writers
        $tableUse = [];
        foreach ($files as $rel => $d) {
            foreach ($d['sql'] ?? [] as $q) {
                foreach ($q['reads'] as $t) if (isset($tableSet[$t])) $tableUse[$t]['read'][] = ['file' => $rel, 'fn' => $q['fn'], 'line' => $q['line'], 'note' => $q['note']];
                // Schema upkeep (CREATE / ALTER / DROP - Database Verification) is not a use of the data.
                foreach ($q['writes'] as $t => $w) if (isset($tableSet[$t]) && in_array($w['op'], ['insert', 'update', 'delete'], true)) $tableUse[$t]['write'][] = ['file' => $rel, 'fn' => $q['fn'], 'line' => $q['line'], 'op' => $w['op'], 'cols' => $w['cols'], 'note' => $q['note']];
            }
        }

        $pages = [];
        $put = function (string $page, string $md) use (&$pages) { $pages[$page] = $md; };

        // --- file pages
        foreach ($files as $rel => $d) {
            $put('code/files/' . $rel, $this->filePage($rel, $d, $includedBy[$rel] ?? [], $endpointCallers[$rel] ?? [], $scriptUsers[$rel] ?? [], $tableSet, $fnIndex));
        }
        // --- function pages
        foreach ($fnIndex as $key => $defs) {
            $put('code/functions/' . $this->fnSlug($defs[0]['name']), $this->functionPage($defs, $callers[$key] ?? [], $files, $tableSet, $fnIndex));
        }
        // --- class pages
        foreach ($classIndex as $cls => $c) {
            if (empty($c['file'])) continue;
            $put('code/classes/' . $this->fnSlug($cls), $this->classPage($cls, $c, $callers, $files, $tableSet));
        }
        // --- table pages
        foreach ($schema as $t => $def) $put('code/tables/' . $t, $this->tablePage($t, $def, $tableUse[$t] ?? []));
        // --- indexes
        $put('code/index', $this->indexPage($files, $fnIndex, $classIndex, $schema));
        $put('code/functions/index', $this->functionsIndex($fnIndex));
        $put('code/classes/index', $this->classesIndex($classIndex));
        $put('code/tables/index', $this->tablesIndex($schema, $tableUse));
        $put('code/files/index', $this->filesIndex($files));
        $put('code/endpoints', $this->endpointsPage($files, $endpointCallers));
        $put('code/traps', $this->trapsPage($files));

        // --- write what changed, delete what is no longer produced
        $written = 0;
        foreach ($pages as $page => $md) {
            $path = ewRoot() . '/' . $page . '.md';
            if (is_file($path) && file_get_contents($path) === $md) continue;
            if (!is_dir(dirname($path))) @mkdir(dirname($path), 0775, true);
            file_put_contents($path, $md);
            $written++;
        }
        $previous = $this->readJson('pages.json', []);
        foreach ($previous as $page) {
            if (!isset($pages[$page])) { @unlink(ewRoot() . '/' . $page . '.md'); }
        }
        $this->writeJson('pages.json', array_keys($pages));
        $this->writeJson('last.json', ['finished' => time(), 'files' => count($files), 'pages' => count($pages), 'written' => $written, 'changed_files' => (int)($this->state['changed'] ?? 0)]);
        $this->state['pages'] = count($pages);
        $this->state['phase'] = 'done';
        $this->cleanEmptyDirs($this->out);
    }

    /**
     * Removes empty folders under the output, keeping _scan; returns true when the folder itself is empty.
     *
     * @param string $dir  the absolute folder to tidy - the output folder, then each folder below it
     */
    private function cleanEmptyDirs(string $dir): bool
    {
        $empty = true;
        foreach (scandir($dir) ?: [] as $f) {
            if ($f === '.' || $f === '..') continue;
            $p = $dir . '/' . $f;
            if (is_dir($p)) { if ($f === '_scan' || !$this->cleanEmptyDirs($p)) $empty = false; else @rmdir($p); }
            else $empty = false;
        }
        return $empty;
    }

    /**
     * "api/tickets/save.php" from a JS reference like "../api/tickets/save.php" or "api/tickets/save.php".
     *
     * @param string $e  an endpoint path as found in the JavaScript, e.g. "../api/tickets/save.php"
     * @param array $files  every scanned file, keyed by path from the app root (only the keys are used)
     */
    private function matchEndpoint(string $e, array $files): array
    {
        $e = ltrim(preg_replace('#^(\.\./)+#', '', $e), '/');
        return isset($files[$e]) ? [$e] : [];
    }

    // ================================================================ page builders

    /**
     * A Markdown link to a wiki page.
     *
     * @param string $page  the page path from the wiki root without ".md", e.g. "code/tables/leases"
     * @param string $label  the link text, plain
     */
    private function link(string $page, string $label): string { return '[' . $this->mdText($label) . '](/' . $page . '.md)'; }
    /**
     * A link to a file's page.
     *
     * @param string $rel  the file's path from the app root
     * @param ?string $label  the link text; null shows the path
     */
    private function fileLink(string $rel, ?string $label = null): string { return $this->link('code/files/' . $rel, $label ?? $rel); }
    /**
     * A link to a function's page, labelled name().
     *
     * @param string $name  the function's name as defined
     */
    private function fnLink(string $name): string { return $this->link('code/functions/' . $this->fnSlug($name), $name . '()'); }
    /**
     * A link to a table's page.
     *
     * @param string $t  the table name
     */
    private function tableLink(string $t): string { return $this->link('code/tables/' . $t, $t); }
    /**
     * A function or class name made safe for a page file name.
     *
     * @param string $name  a function or class name
     */
    private function fnSlug(string $name): string { return preg_replace('/[^A-Za-z0-9_.-]/', '_', $name); }
    /**
     * Text safe inside a link label or table cell.
     *
     * @param string $s  plain text
     */
    private function mdText(string $s): string { return str_replace(['|', '[', ']', "\n"], ['\|', '\[', '\]', ' '], $s); }
    /**
     * Text made safe for a Markdown table cell - pipes escaped, line breaks flattened.
     *
     * @param string $s  the cell's text, plain or Markdown
     */
    private function cell(string $s): string { return str_replace(['|', "\n", "\r"], ['\|', ' ', ''], trim($s)); }
    /**
     * Text as inline code (any backtick inside becomes a quote).
     *
     * @param string $s  the text to show as code
     */
    private function code(string $s): string { return '`' . str_replace('`', "'", $s) . '`'; }

    /**
     * A parsed doc comment as Markdown - the wiki link box, then the summary (optional) and the body.
     *
     * @param ?array $doc  a parsed comment from parseDoc() / lineDoc() - array{summary, body, tags}; null for none
     * @param bool $withSummary  false leaves the summary out (when the page shows it elsewhere)
     */
    private function docMd(?array $doc, bool $withSummary = true): string
    {
        if (!$doc) return '';
        $out = $this->wikiLinks($doc);
        if ($withSummary && $doc['summary'] !== '') $out .= $this->prose($doc['summary']) . "\n\n";
        if ($doc['body'] !== '') $out .= $this->prose($doc['body']) . "\n\n";
        return $out;
    }

    /**
     * The GitHub wiki pages a comment points at, as a box that stands out at the top of
     * the page - a LINK only; the wiki's text is never copied into FreeITSM.
     * From "@wiki Page-Name" (or a full URL) and from any github.com/edmozley/freeitsm/wiki/...
     * link already written in the comment.
     *
     * @param array $doc  a parsed comment from parseDoc() / lineDoc() - its @wiki tags, summary and body are read
     */
    private function wikiLinks(array $doc): string
    {
        $base = 'https://github.com/edmozley/freeitsm/wiki/';
        $pages = [];
        foreach ($doc['tags'] ?? [] as [$tag, $v]) {
            if ($tag !== 'wiki' || trim($v) === '') continue;
            $p = preg_replace('#^.*?/freeitsm/wiki/#', '', trim(preg_split('/\s+/', trim($v))[0]));
            if ($p === '') continue;
            $pages[$p] = true;
        }
        if (preg_match_all('#github\.com/edmozley/freeitsm/wiki/([A-Za-z0-9._%-]+(?:\#[\w-]+)?)#', $doc['summary'] . "\n" . $doc['body'], $m)) {
            foreach ($m[1] as $p) if (rtrim($p, '.,;:)') !== '') $pages[rtrim($p, '.,;:)')] = true;
        }
        if (!$pages) return '';
        $links = array_map(fn($p) => '[' . $this->mdText(str_replace(['-', '#'], [' ', ' - '], rawurldecode($p))) . '](' . $base . $p . ')', array_keys($pages));
        return "> [!TIP]\n> **Developer guide on the GitHub wiki:** " . implode(' · ', $links) . "\n\n";
    }

    /**
     * Comment prose into Markdown: keep paragraphs and lists, stop "*" and "_" in code-ish text turning into emphasis.
     *
     * @param string $t  comment text - a summary, body, tag value or note
     */
    private function prose(string $t): string
    {
        $t = preg_replace('/(?<![\w`])(\$[A-Za-z_]\w*)/', '`$1`', $t);
        $t = preg_replace('/(?<=\s|^)([A-Za-z_][\w]*\(\))/', '`$1`', $t);
        $t = preg_replace('/(?<!\S)(@[a-z]+)/', '`$1`', $t);
        return $t;
    }

    /**
     * A function's signature as written, wrapped one parameter per line when it is over 100 characters.
     *
     * @param array $fn  a parsed function from parsePhp() - name, params and return are used
     */
    private function signature(array $fn): string
    {
        $ps = array_map(function ($p) {
            return trim(($p['type'] !== '' ? $p['type'] . ' ' : '') . ($p['ref'] ? '&' : '') . ($p['variadic'] ? '...' : '') . $p['name'] . ($p['default'] !== '' ? ' = ' . $p['default'] : ''));
        }, $fn['params']);
        $one = 'function ' . $fn['name'] . '(' . implode(', ', $ps) . ')' . ($fn['return'] !== '' ? ': ' . $fn['return'] : '');
        if (strlen($one) <= 100) return $one;
        return 'function ' . $fn['name'] . "(\n    " . implode(",\n    ", $ps) . "\n)" . ($fn['return'] !== '' ? ': ' . $fn['return'] : '');
    }

    /**
     * The parameters table - name, type, default, and the meaning from its @param tag.
     *
     * @param array $fn  a parsed function from parsePhp() with its 'file' added - params, doc and file are used
     */
    private function paramsTable(array $fn): string
    {
        $descr = [];
        foreach ($fn['doc']['tags'] ?? [] as [$tag, $v]) {
            if ($tag === 'param' && preg_match('/(?:^|\s)(&?\.{0,3}\$\w+)\s*(.*)$/', $v, $m)) $descr[ltrim($m[1], '&.')] = $m[2];
        }
        if (!$fn['params']) return '';
        $out = "| Parameter | Type | Default | Meaning |\n|---|---|---|---|\n";
        foreach ($fn['params'] as $p) {
            $out .= '| ' . $this->code(($p['variadic'] ? '...' : '') . $p['name']) . ' | ' . ($p['type'] !== '' ? $this->code($p['type']) : '') . ' | '
                . ($p['default'] !== '' ? $this->code($p['default']) : '') . ' | ' . $this->cell($this->prose(trim($descr[$p['name']] ?? '') !== '' ? $descr[$p['name']] : (($p['note'] ?? '') !== '' ? $p['note'] : $this->standardParam($p, (string)($fn['file'] ?? ''))))) . " |\n";
        }
        return $out . "\n";
    }

    /**
     * The meaning of a parameter that means the same everywhere (handbook/standard-parameters.md),
     * used only when the function's own comment says nothing about it. Marked "standard" so a
     * reader knows where it came from.
     *
     * @param array $p  one parameter as tidyParams() leaves it - name and type are used
     * @param string $file  the defining file's path from the app root; under api/v1/ the REST parameters count too
     */
    private function standardParam(array $p, string $file): string
    {
        $type = ltrim($p['type'], '?\\');   // "?int" and "\PDO" read as int / PDO
        $api = str_starts_with($file, 'api/v1/');
        $m = null;
        switch ($p['name']) {
            case '$conn':      if ($type === 'PDO' || $type === '') $m = 'The database connection.'; break;
            case '$analystId': if ($type === 'int') $m = 'The analyst doing this - normally the one signed in.'; break;
            case '$ctx':       if ($type === 'ActorContext') $m = 'Who is acting, which companies they may reach, ui or api, and their language.'; break;
            case '$tenantId':  if ($type === 'int') $m = 'A company (tenant) id; null means the Default company.'; break;
            case '$projectId': case '$ticketId': case '$taskId': case '$assetId':
                if ($type === 'int') $m = 'The id of the ' . substr($p['name'], 1, -2) . '.'; break;
            case '$apiKey':    if ($api && $type === 'array') $m = 'The API key that authenticated the call - its scopes and company limit.'; break;
            case '$params':    if ($api && $type === 'array') $m = 'The ids captured from the URL path, in order, as integers.'; break;
            case '$body':      if ($api && $type === 'array') $m = 'The request\'s JSON body, decoded.'; break;
        }
        return $m === null ? '' : $m . ' *([standard](/handbook/standard-parameters.md))*';
    }

    /**
     * The doc comment's tags of the wanted kinds, as [tag, value] pairs.
     *
     * @param ?array $doc  a parsed comment from parseDoc() / lineDoc() - array{summary, body, tags}; null for none
     * @param array $want  the lower-case tag names to keep, e.g. ['return', 'reads']
     */
    private function tagRows(?array $doc, array $want): array
    {
        $rows = [];
        foreach ($doc['tags'] ?? [] as [$tag, $v]) if (in_array($tag, $want, true)) $rows[] = [$tag, $v];
        return $rows;
    }

    /**
     * Builds a file's page - its description, the endpoint box for an API file, its functions and classes, its SQL, what it includes and is included by, what it calls, and its traps.
     *
     * @param string $rel  the file's path from the app root, forward slashes, e.g. "api/tickets/save.php"
     * @param array $d  the file's parse, from parsePhp() or parseJs()
     * @param array $includedBy  the PHP files that include it: path => true
     * @param array $jsCallers  the JavaScript files that call it as an endpoint: path => true
     * @param array $usedBy  the pages that load it with a script tag: path => true
     * @param array $tableSet  table name => true for every table in the schema, so only real tables are linked
     * @param array $fnIndex  lower-case function name => list of its definitions (each a parsed function plus 'file')
     */
    private function filePage(string $rel, array $d, array $includedBy, array $jsCallers, array $usedBy, array $tableSet, array $fnIndex): string
    {
        $md = '# ' . $rel . "\n\n";
        $isApi = str_starts_with($rel, 'api/') && $d['kind'] === 'php';
        $md .= '> ' . ($d['kind'] === 'js' ? 'JavaScript' : ($isApi ? 'API endpoint' : 'PHP')) . ' - ' . $d['lines'] . " lines\n\n";
        $md .= $this->docMd($d['doc']);
        if ($d['doc']) foreach ($this->tagRows($d['doc'], ['see']) as [, $v]) $md .= '- See: ' . $this->seeLink($v) . "\n";

        if ($isApi) {
            $md .= "## The endpoint\n\n| | |\n|---|---|\n| URL | " . $this->code($rel) . " |\n| Protected by | " . $this->cell($this->guardText($rel, $d)) . " |\n";
            $inputs = array_map(fn($i) => $this->code(str_replace(':', ' ', $i)), $d['inputs']);
            if ($inputs) $md .= '| Reads | ' . implode(', ', $inputs) . " |\n";
            foreach ($this->tagRows($d['doc'], ['input']) as [, $v]) $md .= '| Input | ' . $this->cell($this->prose($v)) . " |\n";
            foreach ($this->tagRows($d['doc'], ['output']) as [, $v]) $md .= '| Output | ' . $this->cell($this->prose($v)) . " |\n";
            if ($jsCallers) $md .= '| Called from | ' . implode(', ', array_map(fn($f) => $this->fileLink($f), array_keys($jsCallers))) . " |\n";
            $md .= "\n";
        }

        if ($d['kind'] === 'js') {
            if ($d['functions']) {
                $md .= "## Functions\n\n| Function | Line | What it does |\n|---|--:|---|\n";
                foreach ($d['functions'] as $fn) $md .= '| ' . $this->code($fn['name'] . '(' . $fn['params'] . ')') . ' | ' . $fn['line'] . ' | ' . $this->cell($this->prose($fn['doc']['summary'] ?? '')) . " |\n";
                $md .= "\n";
            }
            if ($d['endpoints']) {
                $md .= "## API endpoints it calls\n\n";
                foreach ($d['endpoints'] as $e) $md .= '- ' . (is_file($this->app . '/' . $e) ? $this->fileLink($e) : $this->code($e)) . "\n";
                $md .= "\n";
            }
            if ($usedBy) $md .= "## Loaded by\n\n" . implode("\n", array_map(fn($f) => '- ' . $this->fileLink($f), array_keys($usedBy))) . "\n\n";
            return $md . $this->trapsMd($d['traps']);
        }

        $own = array_filter($d['functions'], fn($f) => $f['class'] === null);
        if ($own) {
            $md .= "## Functions\n\n| Function | Line | What it does |\n|---|--:|---|\n";
            foreach ($own as $fn) $md .= '| ' . $this->fnLink($fn['name']) . ' | ' . $fn['line'] . ' | ' . $this->cell($this->prose($fn['doc']['summary'] ?? '')) . " |\n";
            $md .= "\n";
        }
        if ($d['classes']) {
            $md .= "## Classes\n\n";
            foreach ($d['classes'] as $c) $md .= '- ' . $this->link('code/classes/' . $this->fnSlug($c['name']), $c['name']) . ' (' . $c['kind'] . ', line ' . $c['line'] . ')' . ($c['doc']['summary'] ?? '' ? ' - ' . $this->prose($c['doc']['summary']) : '') . "\n";
            $md .= "\n";
        }

        $md .= $this->sqlMd($d['sql'] ?? [], $tableSet);

        if ($d['includes'] || $includedBy || $d['scripts']) {
            $md .= "## How it hangs together\n\n";
            if ($d['includes']) $md .= "**Includes:** " . implode(', ', array_map(fn($f) => is_file($this->app . '/' . $f) ? $this->fileLink($f) : $this->code($f), $d['includes'])) . "\n\n";
            if ($includedBy) $md .= "**Included by:** " . implode(', ', array_map(fn($f) => $this->fileLink($f), array_keys($includedBy))) . "\n\n";
            if ($d['scripts']) $md .= "**Loads scripts:** " . implode(', ', array_map(fn($f) => is_file($this->app . '/' . $f) ? $this->fileLink($f) : $this->code($f), $d['scripts'])) . "\n\n";
        }

        $called = [];
        foreach ($d['calls'] as [$callee]) if (isset($fnIndex[strtolower($callee)]) && !in_array($callee, array_column($own, 'name'), true)) $called[strtolower($callee)] = $callee;
        if ($called) { ksort($called); $md .= "**Calls:** " . implode(', ', array_map(fn($c) => $this->fnLink($fnIndex[strtolower($c)][0]['name']), $called)) . "\n\n"; }

        return $md . $this->trapsMd($d['traps']);
    }

    /**
     * How an endpoint is protected: the checks it makes itself, else the ones made by a
     * file it includes (a shared bootstrap like includes/files/api_bootstrap.php), at the
     * top level of that file - a check inside a function only guards that function's callers.
     *
     * @param string $rel  the file's path from the app root, forward slashes, e.g. "api/tickets/save.php"
     * @param array $d  the file's parse, as parsePhp() returns it
     */
    private function guardText(string $rel, array $d): string
    {
        if (basename($rel)[0] === '_') return 'Helper - included by other endpoints, not called directly';
        if (str_starts_with($rel, 'api/v1/')) return 'REST API key';
        $g = $this->guardsOf($d, '');
        $seen = [$rel => true];
        $queue = $d['includes'];
        for ($hop = 0; !$g && $queue && $hop < 3; $hop++) {
            $next = [];
            foreach ($queue as $inc) {
                if (isset($seen[$inc]) || !isset($this->files[$inc]) || in_array($inc, ['config.php', 'includes/functions.php'], true)) continue;
                $seen[$inc] = true;
                $g = array_merge($g, $this->guardsOf($this->files[$inc], ' (via ' . $inc . ')'));
                $next = array_merge($next, $this->files[$inc]['includes'] ?? []);
            }
            $queue = $next;
        }
        if ($g) return implode('; ', array_unique($g));
        $sess = $d['session'] ?? [];
        if (in_array('analyst_id', $sess, true)) return 'Signed-in analyst (session check)';
        if (in_array('ss_user_id', $sess, true)) return 'Signed-in portal user (session check)';
        return 'Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code)';
    }

    /**
     * The sign-in and permission checks a file makes at its top level (admin guard, module access, capability), each suffixed with $via.
     *
     * @param array $d  the file's parse, as parsePhp() returns it
     * @param string $via  added after each check found, e.g. " (via includes/x.php)"; '' for the endpoint's own checks
     */
    private function guardsOf(array $d, string $via): array
    {
        $g = [];
        if (in_array('includes/admin_api_guard.php', $d['includes'] ?? [], true)) $g[] = 'Administrators only (admin_api_guard.php)' . $via;
        foreach ($d['guards'] ?? [] as $row) {
            [$fn, $arg] = $row; $inFn = $row[2] ?? null;
            if ($inFn !== null) continue;
            if (stripos($fn, 'admin') !== false) $g[] = 'Administrators only (' . $fn . ')' . $via;
            elseif (stripos($fn, 'moduleaccess') !== false) $g[] = 'Module access: ' . ($arg ?? '?') . ' (' . $fn . ')' . $via;
            elseif (stripos($fn, 'cap') !== false) $g[] = 'Permission: ' . ($arg ?? '?') . ' (' . $fn . ')' . $via;
            else $g[] = $fn . ($arg ? '(' . $arg . ')' : '') . $via;
        }
        if (!$g && $via !== '' && in_array('analyst_id', $d['session'] ?? [], true)) $g[] = 'Signed-in analyst (session check)' . $via;
        return $g;
    }

    /**
     * The Database table for a list of queries - line, function, tables read or written (with columns), and the SQL: note.
     *
     * @param array $sql  query entries from sqlOf() with line, fn and note added, as parsePhp() stores them
     * @param array $tableSet  table name => true for every table in the schema, so only real tables are linked
     */
    private function sqlMd(array $sql, array $tableSet): string
    {
        $rows = [];
        foreach ($sql as $q) {
            $tables = [];
            foreach ($q['writes'] as $t => $w) if (isset($tableSet[$t])) $tables[] = '**' . $w['op'] . '** ' . $this->tableLink($t) . ($w['cols'] ? ' (' . implode(', ', array_map(fn($c) => $this->code($c), array_slice($w['cols'], 0, 12))) . ')' : '');
            foreach ($q['reads'] as $t) if (isset($tableSet[$t]) && !isset($q['writes'][$t])) $tables[] = 'read ' . $this->tableLink($t);
            if (!$tables) continue;
            $rows[] = '| ' . $q['line'] . ' | ' . ($q['fn'] ? $this->code($q['fn']) : '') . ' | ' . implode(', ', $tables) . ' | ' . $this->cell($this->prose((string)$q['note'])) . " |\n";
        }
        if (!$rows) return '';
        return "## Database\n\n| Line | In | Tables | Purpose |\n|--:|---|---|---|\n" . implode('', array_slice($rows, 0, 200)) . (count($rows) > 200 ? "\n*(" . (count($rows) - 200) . " more not shown)*\n" : '') . "\n";
    }

    /**
     * The Traps section - each trap as a warning box with its line and function.
     *
     * @param array $traps  the traps to show, each array{line:int, fn:?string, text:string}
     */
    private function trapsMd(array $traps): string
    {
        if (!$traps) return '';
        $md = "## Traps\n\n";
        foreach ($traps as $t) $md .= "> [!WARNING]\n> **Line " . $t['line'] . ($t['fn'] ? ', in ' . $this->code($t['fn']) : '') . ':** ' . $this->prose($t['text']) . "\n\n";
        return $md;
    }

    /**
     * An @see value as a file link when it names a file in the app, otherwise as code.
     *
     * @param string $v  an @see value as written; only its first word is used
     */
    private function seeLink(string $v): string
    {
        $v = trim(preg_split('/\s+/', $v)[0]);
        if (is_file($this->app . '/' . $v)) return $this->fileLink($v);
        return $this->code($v);
    }

    /**
     * Builds a function's page - description, signature, parameters, return, the tables it touches (warning when the comment names one its SQL does not), what it calls, its callers and its traps.
     *
     * @param array $defs  every definition of this function name (a parsed function plus 'file'); the first is shown
     * @param array $callers  who calls it: "file|caller" => array{file, fn}, fn null for top-level code
     * @param array $files  every scanned file's parse (parsePhp() / parseJs() result), keyed by path from the app root
     * @param array $tableSet  table name => true for every table in the schema, so only real tables are linked
     * @param array $fnIndex  lower-case function name => list of its definitions (each a parsed function plus 'file')
     */
    private function functionPage(array $defs, array $callers, array $files, array $tableSet, array $fnIndex): string
    {
        $fn = $defs[0];
        $md = '# ' . $fn['name'] . "()\n\n";
        if (count($defs) > 1) $md .= "> [!NOTE]\n> Defined in " . count($defs) . " places (each behind a function_exists() check, or in files never loaded together): " . implode(', ', array_map(fn($x) => $this->fileLink($x['file']), $defs)) . "\n\n";
        $md .= $this->docMd($fn['doc']);
        $md .= "```php\n" . $this->signature($fn) . "\n```\n\n";
        $md .= 'Defined in ' . $this->fileLink($fn['file']) . ', line ' . $fn['line'] . ".\n\n";
        $md .= $this->paramsTable($fn);
        foreach ($this->tagRows($fn['doc'], ['return']) as [, $v]) $md .= '**Returns:** ' . $this->prose($v) . "\n\n";
        if ($fn['return'] !== '' && !$this->tagRows($fn['doc'], ['return'])) $md .= '**Returns:** ' . $this->code($fn['return']) . "\n\n";

        // tables it touches: from its SQL, and what its comment says
        $sqlHere = array_filter($files[$fn['file']]['sql'] ?? [], fn($q) => $q['fn'] === $fn['name']);
        $detected = [];
        foreach ($sqlHere as $q) { foreach ($q['reads'] as $t) if (isset($tableSet[$t])) $detected[$t] = true; foreach ($q['writes'] as $t => $_) if (isset($tableSet[$t])) $detected[$t] = true; }
        $said = [];
        foreach ($this->tagRows($fn['doc'], ['reads', 'writes']) as [$tag, $v]) {
            $md .= '**' . ucfirst($tag) . ':** ' . $this->prose($v) . "\n\n";
            if (preg_match_all('/\b([a-z_][a-z0-9_]*)\b/', preg_replace('/\s-\s.*$/', '', $v), $tm)) foreach ($tm[1] as $t) if (isset($tableSet[$t])) $said[$t] = true;
        }
        if ($sqlHere) $md .= $this->sqlMd(array_values($sqlHere), $tableSet);
        if ($said) {
            $missing = array_diff(array_keys($said), array_keys($detected));
            if ($missing && $sqlHere) $md .= "> [!CAUTION]\n> The comment names " . implode(', ', array_map(fn($t) => $this->code($t), $missing)) . " but no SQL in this function touches it - the comment may be out of date (or the SQL is built elsewhere).\n\n";
        }

        $calls = [];
        foreach ($files[$fn['file']]['calls'] ?? [] as [$callee, $in]) if ($in === $fn['name'] && isset($fnIndex[strtolower($callee)]) && strtolower($callee) !== strtolower($fn['name'])) $calls[strtolower($callee)] = $fnIndex[strtolower($callee)][0]['name'];
        if ($calls) { ksort($calls); $md .= "## Calls\n\n" . implode(', ', array_map(fn($c) => $this->fnLink($c), $calls)) . "\n\n"; }

        if ($callers) {
            $md .= "## Called from\n\n";
            $byFile = [];
            foreach ($callers as $c) $byFile[$c['file']][] = $c['fn'];
            ksort($byFile, SORT_NATURAL);
            foreach ($byFile as $f => $fns) {
                $fns = array_values(array_unique(array_filter($fns)));
                $md .= '- ' . $this->fileLink($f) . ($fns ? ' - in ' . implode(', ', array_map(fn($x) => !str_contains($x, '::') && isset($fnIndex[strtolower($x)]) ? $this->fnLink($x) : $this->code($x), $fns)) : '') . "\n";
            }
            $md .= "\n";
        } else {
            $md .= "*Nothing calls this function by name.*\n\n";
        }
        $traps = array_filter($files[$fn['file']]['traps'] ?? [], fn($t) => $t['fn'] === $fn['name']);
        return $md . $this->trapsMd(array_values($traps));
    }

    /**
     * Builds a class's page - its description, then each method with its signature, parameters, tags, SQL and callers.
     *
     * @param string $cls  the class name
     * @param array $c  the class's $classIndex entry - file, line, doc, kind and methods
     * @param array $callers  every caller list, keyed by function name or "Class::method" (method lower-case)
     * @param array $files  every scanned file's parse (parsePhp() / parseJs() result), keyed by path from the app root
     * @param array $tableSet  table name => true for every table in the schema, so only real tables are linked
     */
    private function classPage(string $cls, array $c, array $callers, array $files, array $tableSet): string
    {
        $md = '# ' . $cls . "\n\n> " . ucfirst($c['kind'] ?? 'class') . ' in ' . $this->fileLink($c['file']) . ', line ' . $c['line'] . "\n\n";
        $md .= $this->docMd($c['doc']);
        $methods = $c['methods'] ?? [];
        usort($methods, fn($a, $b) => $a['line'] <=> $b['line']);
        if ($methods) {
            $md .= "## Methods\n\n| Method | What it does |\n|---|---|\n";
            foreach ($methods as $m) $md .= '| [' . $this->mdText($m['name']) . '()](#' . strtolower($this->fnSlug($m['name'])) . ') | ' . $this->cell($this->prose($m['doc']['summary'] ?? '')) . " |\n";
            $md .= "\n";
            foreach ($methods as $m) {
                $md .= '### ' . $m['name'] . "\n\n";
                $md .= trim(($m['visibility'] ? $m['visibility'] . ' ' : '') . ($m['static'] ? 'static' : '')) !== '' ? '*' . trim($m['visibility'] . ' ' . ($m['static'] ? 'static' : '')) . "* - line " . $m['line'] . "\n\n" : 'Line ' . $m['line'] . "\n\n";
                $md .= $this->docMd($m['doc']);
                $md .= "```php\n" . $this->signature($m) . "\n```\n\n";
                $md .= $this->paramsTable($m);
                foreach ($this->tagRows($m['doc'], ['return', 'reads', 'writes']) as [$tag, $v]) $md .= '**' . ucfirst($tag === 'return' ? 'returns' : $tag) . ':** ' . $this->prose($v) . "\n\n";
                $sqlHere = array_values(array_filter($files[$c['file']]['sql'] ?? [], fn($q) => $q['fn'] === $cls . '::' . $m['name']));
                if ($sqlHere) $md .= str_replace('## Database', '**Database**', $this->sqlMd($sqlHere, $tableSet));
                $who = $callers[$cls . '::' . strtolower($m['name'])] ?? [];
                if ($who) $md .= '**Called from:** ' . implode(', ', array_map(fn($f) => $this->fileLink($f), array_unique(array_column($who, 'file')))) . "\n\n";
            }
        }
        return $md;
    }

    /**
     * Builds a table's page - its columns from the schema with their comments, then who writes it and who reads it.
     *
     * @param string $t  the table name
     * @param array $def  the table's loadSchema() entry - array{note, columns}
     * @param array $use  array{read?: list, write?: list} from phaseWrite(); empty when no SQL names the table
     */
    private function tablePage(string $t, array $def, array $use): string
    {
        $md = '# ' . $t . "\n\n";
        if ($def['note'] !== '') $md .= $this->prose($def['note']) . "\n\n";
        $md .= "## Columns\n\n| Column | Definition | Notes |\n|---|---|---|\n";
        foreach ($def['columns'] as $col => $c) $md .= '| ' . $this->code($col) . ' | ' . $this->code($c['def']) . ' | ' . $this->cell($this->prose($c['note'])) . " |\n";
        $md .= "\n";
        if (!empty($use['write'])) {
            $md .= "## Written by\n\n| File | In | Does | Columns | Purpose |\n|---|---|---|---|---|\n";
            foreach ($use['write'] as $w) $md .= '| ' . $this->fileLink($w['file']) . ':' . $w['line'] . ' | ' . ($w['fn'] ? $this->code($w['fn']) : '') . ' | ' . $w['op'] . ' | ' . implode(', ', array_map(fn($x) => $this->code($x), array_slice($w['cols'], 0, 10))) . ' | ' . $this->cell($this->prose((string)$w['note'])) . " |\n";
            $md .= "\n";
        }
        if (!empty($use['read'])) {
            $byFile = [];
            foreach ($use['read'] as $r) $byFile[$r['file']][] = $r;
            ksort($byFile, SORT_NATURAL);
            $md .= "## Read by\n\n| File | Where | Purpose |\n|---|---|---|\n";
            foreach ($byFile as $f => $rs) {
                $where = implode(', ', array_unique(array_map(fn($r) => ($r['fn'] ? $r['fn'] . ' ' : '') . 'L' . $r['line'], array_slice($rs, 0, 6)))) . (count($rs) > 6 ? ' ...' : '');
                $notes = implode('; ', array_unique(array_filter(array_column($rs, 'note'))));
                $md .= '| ' . $this->fileLink($f) . ' | ' . $this->cell($where) . ' | ' . $this->cell($this->prose($notes)) . " |\n";
            }
            $md .= "\n";
        }
        if (empty($use)) $md .= "*No SQL in the scanned code names this table directly.*\n";
        return $md;
    }

    /**
     * Builds the Code map front page - counts of files, endpoints, functions, classes, tables and traps, with links.
     *
     * @param array $files  every scanned file's parse (parsePhp() / parseJs() result), keyed by path from the app root
     * @param array $fnIndex  lower-case function name => list of its definitions (each a parsed function plus 'file')
     * @param array $classIndex  class name => array{file, line, doc, kind, methods} (methods are parsed functions plus 'file')
     * @param array $schema  loadSchema()'s result: table name => array{note, columns}
     */
    private function indexPage(array $files, array $fnIndex, array $classIndex, array $schema): string
    {
        $php = count(array_filter($files, fn($d) => $d['kind'] === 'php'));
        $js = count($files) - $php;
        $api = count(array_filter(array_keys($files), fn($f) => str_starts_with($f, 'api/') && substr($f, -4) === '.php'));
        $traps = array_sum(array_map(fn($d) => count($d['traps']), $files));
        return "# Code map\n\nWritten by the scanner from FreeITSM's own code - do not edit these pages, they are rebuilt on every scan. The *why* lives in the [Handbook](/handbook/start.md).\n\n"
            . "| | |\n|---|--:|\n"
            . '| ' . $this->link('code/files/index', 'Files') . " | $php PHP, $js JavaScript |\n"
            . '| ' . $this->link('code/endpoints', 'API endpoints') . " | $api |\n"
            . '| ' . $this->link('code/functions/index', 'Functions') . ' | ' . count($fnIndex) . " |\n"
            . '| ' . $this->link('code/classes/index', 'Classes') . ' | ' . count(array_filter($classIndex, fn($c) => !empty($c['file']))) . " |\n"
            . '| ' . $this->link('code/tables/index', 'Database tables') . ' | ' . count($schema) . " |\n"
            . '| ' . $this->link('code/traps', 'Traps') . " | $traps |\n\n"
            . "How to make these pages say more: [[handbook/comments|Writing comments for the wiki]].\n";
    }

    /**
     * Builds the A-Z list of functions outside classes, with file and summary.
     *
     * @param array $fnIndex  lower-case function name => list of its definitions (each a parsed function plus 'file')
     */
    private function functionsIndex(array $fnIndex): string
    {
        $md = "# Functions\n\nEvery function defined outside a class, A to Z. Class methods are on their [class pages](/code/classes/index.md).\n\n";
        $byLetter = [];
        foreach ($fnIndex as $defs) $byLetter[strtoupper($defs[0]['name'][0])][] = $defs[0];
        ksort($byLetter);
        foreach ($byLetter as $l => $fns) {
            usort($fns, fn($a, $b) => strnatcasecmp($a['name'], $b['name']));
            $md .= "## $l\n\n| Function | File | What it does |\n|---|---|---|\n";
            foreach ($fns as $f) $md .= '| ' . $this->fnLink($f['name']) . ' | ' . $this->fileLink($f['file']) . ' | ' . $this->cell($this->prose($f['doc']['summary'] ?? '')) . " |\n";
            $md .= "\n";
        }
        return $md;
    }

    /**
     * Builds the list of classes, with file, method count and summary.
     *
     * @param array $classIndex  class name => array{file, line, doc, kind, methods} (methods are parsed functions plus 'file')
     */
    private function classesIndex(array $classIndex): string
    {
        ksort($classIndex, SORT_NATURAL | SORT_FLAG_CASE);
        $md = "# Classes\n\n| Class | File | Methods | What it is |\n|---|---|--:|---|\n";
        foreach ($classIndex as $cls => $c) {
            if (empty($c['file'])) continue;
            $md .= '| ' . $this->link('code/classes/' . $this->fnSlug($cls), $cls) . ' | ' . $this->fileLink($c['file']) . ' | ' . count($c['methods'] ?? []) . ' | ' . $this->cell($this->prose($c['doc']['summary'] ?? '')) . " |\n";
        }
        return $md;
    }

    /**
     * Builds the list of tables, with column count, how many files read and write each, and the first sentence of its note.
     *
     * @param array $schema  loadSchema()'s result: table name => array{note, columns}
     * @param array $use  table name => array{read?: list, write?: list}, each entry carrying the file
     */
    private function tablesIndex(array $schema, array $use): string
    {
        $md = "# Database tables\n\nFrom " . $this->fileLink('includes/db_verify_schema.php') . " - the definition Database Verification builds every install from.\n\n| Table | Columns | Read by | Written by | About |\n|---|--:|--:|--:|---|\n";
        foreach ($schema as $t => $def) {
            $r = count(array_unique(array_column($use[$t]['read'] ?? [], 'file')));
            $w = count(array_unique(array_column($use[$t]['write'] ?? [], 'file')));
            $about = $def['note'] !== '' ? preg_split('/(?<=\.)\s/', $def['note'])[0] : '';
            $md .= '| ' . $this->tableLink($t) . ' | ' . count($def['columns']) . " | $r | $w | " . $this->cell($this->prose($about)) . " |\n";
        }
        return $md;
    }

    /**
     * Builds the files index - a count per top folder, then the top-level files with their summaries.
     *
     * @param array $files  every scanned file's parse (parsePhp() / parseJs() result), keyed by path from the app root
     */
    private function filesIndex(array $files): string
    {
        $md = "# Files\n\nEvery PHP and JavaScript file the scanner read, by folder. Open a folder in the page list on the left to browse, or search.\n\n";
        $byDir = [];
        foreach ($files as $rel => $d) $byDir[dirname($rel) === '.' ? '(top)' : explode('/', $rel)[0]][] = $rel;
        ksort($byDir);
        $md .= "| Folder | Files |\n|---|--:|\n";
        foreach ($byDir as $dir => $fs) $md .= '| ' . ($dir === '(top)' ? 'top level' : $this->code($dir . '/')) . ' | ' . count($fs) . " |\n";
        $md .= "\n## Top-level files\n\n";
        foreach ($byDir['(top)'] ?? [] as $f) $md .= '- ' . $this->fileLink($f) . ($files[$f]['doc']['summary'] ?? '' ? ' - ' . $this->prose($files[$f]['doc']['summary']) : '') . "\n";
        return $md;
    }

    /**
     * Builds the API endpoints page - every api/ PHP file by folder, with its protection, summary and the scripts that call it.
     *
     * @param array $files  every scanned file's parse (parsePhp() / parseJs() result), keyed by path from the app root
     * @param array $jsCallers  endpoint path => [path of each JavaScript file that calls it => true]
     */
    private function endpointsPage(array $files, array $jsCallers): string
    {
        $md = "# API endpoints\n\nEvery PHP file under `api/`, with how it is protected and what it is for. *Public* means the scanner found no sign-in check: right for sign-in pages, webhooks and token links, worth a look for anything else.\n\n";
        $byDir = [];
        foreach ($files as $rel => $d) if (str_starts_with($rel, 'api/') && $d['kind'] === 'php') $byDir[dirname($rel)][$rel] = $d;
        ksort($byDir, SORT_NATURAL);
        foreach ($byDir as $dir => $eps) {
            $md .= '## ' . $dir . "\n\n| Endpoint | Protected by | What it does | Called from |\n|---|---|---|---|\n";
            foreach ($eps as $rel => $d) {
                $md .= '| ' . $this->fileLink($rel, basename($rel)) . ' | ' . $this->cell($this->guardText($rel, $d)) . ' | ' . $this->cell($this->prose($d['doc']['summary'] ?? '')) . ' | '
                    . implode(', ', array_map(fn($f) => $this->fileLink($f, basename($f)), array_keys($jsCallers[$rel] ?? []))) . " |\n";
            }
            $md .= "\n";
        }
        return $md;
    }

    /**
     * Builds the page listing every TRAP: comment, file by file.
     *
     * @param array $files  every scanned file's parse (parsePhp() / parseJs() result), keyed by path from the app root
     */
    private function trapsPage(array $files): string
    {
        $md = "# Traps\n\nEvery `TRAP:` comment in the code - the things that will bite the next person, and why. Written by the people who were bitten.\n\n";
        foreach ($files as $rel => $d) {
            if (!$d['traps']) continue;
            $md .= '## ' . $rel . "\n\n";
            foreach ($d['traps'] as $t) $md .= '- **Line ' . $t['line'] . ($t['fn'] ? ', ' . $this->code($t['fn']) : '') . ':** ' . $this->prose($t['text']) . "\n";
            $md .= "\n" . $this->fileLink($rel, 'Open ' . basename($rel)) . "\n\n";
        }
        return $md;
    }

    // ================================================================ schema, with its comments

    /**
     * Loads the table definitions from includes/db_verify_schema.php, with the
     * comments written above each table and above or beside each column as notes.
     *
     * @return array<string, array{note:string, columns:array<string,array{def:string,note:string}>}>
     */
    private function loadSchema(): array
    {
        $file = $this->app . '/includes/db_verify_schema.php';
        if (!is_file($file)) return [];
        $defs = require $file;
        if (!is_array($defs)) return [];
        // The comments: above a table, above a column, or after a column on its line.
        $tokens = token_get_all((string)file_get_contents($file));
        $notes = []; $colNotes = [];
        $depth = 0; $pending = []; $table = null; $lastCol = null; $lastColLine = 0;
        foreach ($tokens as $k => $t) {
            $tid = is_array($t) ? $t[0] : $t;
            if ($tid === '[') { $depth++; continue; }
            if ($tid === ']') { $depth--; if ($depth === 1) { $table = null; $lastCol = null; } continue; }
            if ($tid === T_COMMENT) {
                $c = trim(preg_replace(['#^//+\s?#', '#^/\*+|\*+/$#'], '', trim($t[1])));
                if ($depth === 2 && $lastCol !== null && $t[2] === $lastColLine) { $colNotes[$table][$lastCol] = trim(($colNotes[$table][$lastCol] ?? '') . ' ' . $c); continue; }
                if (preg_match('/^-{3,}|^-+\s.*-{3,}$/', $c)) { $pending = []; $c = trim($c, ' -'); if ($c !== '') $pending[] = $c; continue; }
                $pending[] = $c;
                continue;
            }
            if ($tid === T_CONSTANT_ENCAPSED_STRING) {
                $key = trim($t[1], '\'"');
                $next = $tokens[$k + 1] ?? null; $j = $k + 1;
                while ($next !== null && is_array($next) && $next[0] === T_WHITESPACE) $next = $tokens[++$j] ?? null;
                if (!(is_array($next) && $next[0] === T_DOUBLE_ARROW)) continue;
                if ($depth === 1) { $table = $key; $notes[$key] = implode(' ', $pending); $pending = []; }
                elseif ($depth === 2 && $table !== null) {
                    $lastCol = $key; $lastColLine = $t[2];
                    if ($pending) { $colNotes[$table][$key] = implode(' ', $pending); $pending = []; }
                }
            }
        }
        $out = [];
        ksort($defs, SORT_NATURAL);
        foreach ($defs as $t => $cols) {
            if (!is_array($cols)) continue;
            $c = [];
            foreach ($cols as $col => $def) if (is_string($def)) $c[$col] = ['def' => $def, 'note' => $colNotes[$t][$col] ?? ''];
            $out[$t] = ['note' => $notes[$t] ?? '', 'columns' => $c];
        }
        return $out;
    }

    // ================================================================ state files

    /**
     * Loads the saved manifest, unless it was written by a different VERSION of the scanner (which forces a full re-read).
     */
    private function loadManifest(): void
    {
        $m = $this->readJson('manifest.json', null);
        if (is_array($m) && ($m['version'] ?? 0) === self::VERSION) $this->manifest = $m;
    }
    /** Saves the manifest. */
    private function saveManifest(): void { $this->writeJson('manifest.json', $this->manifest); }
    /** Saves the scan's progress; a finished scan saves an empty state. */
    private function saveState(): void { $this->writeJson('state.json', $this->state['phase'] === 'done' ? [] : $this->state); }
    /**
     * Reads a JSON file from the state folder, or $default when it is missing or unreadable.
     *
     * @param string $name  the file name inside _scan, e.g. 'state.json'
     * @param mixed $default  returned when the file is missing, unreadable or holds null
     */
    private function readJson(string $name, $default)
    {
        $f = $this->stateDir . '/' . $name;
        if (!is_file($f)) return $default;
        $d = json_decode((string)file_get_contents($f), true);
        return $d === null ? $default : $d;
    }
    /**
     * Writes a JSON file to the state folder through a temporary file and a rename, so a reader never sees half a file.
     *
     * @param string $name  the file name inside _scan, e.g. 'manifest.json'
     * @param mixed $data  any value json_encode() can write
     */
    private function writeJson(string $name, $data): void
    {
        $f = $this->stateDir . '/' . $name;
        file_put_contents($f . '.tmp', json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        @rename($f . '.tmp', $f) || (@unlink($f) && @rename($f . '.tmp', $f));
    }

    /** When the last scan finished, and what it did - for the reader's Rescan button. */
    public static function lastScan(): ?array
    {
        $f = ewRoot() . '/code/_scan/last.json';
        return is_file($f) ? json_decode((string)file_get_contents($f), true) : null;
    }
}
