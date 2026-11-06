<?php
/**
 * Engineering wiki - a small Markdown renderer written for the wiki, so the
 * pages look and read like GitHub without pulling in a library.
 *
 * What it understands (the GitHub-flavoured subset the wiki uses):
 *   # headings (with GitHub-style anchors), paragraphs, **bold**, *italic*,
 *   ~~strike~~, `code`, [links](other-page.md#anchor), [[wiki links]],
 *   <https://autolinks>, ![images](https://...), > quotes and GitHub alerts
 *   (> [!NOTE] / TIP / IMPORTANT / WARNING / CAUTION), - and 1. lists (nested,
 *   - [ ] task lists), | tables | with :alignment:, ``` fenced code ``` with a
 *   little highlighting for php / sql / js / json / bash, and --- rules.
 *
 * TRAP: raw HTML in a page is NEVER passed through - every character of text is
 * escaped. The pages are files a developer edits, and a generated page quotes
 * code comments, so treating any of it as HTML would let a comment run script in
 * an administrator's browser. Anything that needs markup gets a Markdown form.
 *
 * Links: the caller passes a resolver. An internal link (a relative .md path or a
 * [[wiki link]]) becomes <a href="?page=..." data-ew-page="..."> so the viewer can
 * open it in a tab; anything with a scheme opens in a new browser window.
 */

final class EwMarkdown
{
    /** @var callable(string):?string  relative link -> wiki page path, or null if it is not a wiki page */
    private $resolve;
    private array $toc = [];
    private array $slugs = [];
    private ?string $title = null;
    private array $holds = [];

    /**
     * Render a page.
     *
     * @param string   $md       the Markdown source
     * @param callable $resolve  fn(string $href): ?string - maps a relative link to a wiki page path
     * @return array{html:string, title:?string, toc:array<int,array{level:int,text:string,id:string}>}
     */
    public static function render(string $md, callable $resolve): array
    {
        $r = new self();
        $r->resolve = $resolve;
        $md = str_replace(["\r\n", "\r", "\t"], ["\n", "\n", '    '], $md);
        $html = $r->blocks(explode("\n", $md));
        return ['html' => $html, 'title' => $r->title, 'toc' => $r->toc];
    }

    // ------------------------------------------------------------------ blocks

    /**
     * Renders a run of lines as block HTML - fenced code, headings, rules, quotes and alerts, tables, lists and paragraphs.
     *
     * @param array $lines  the page (or a quote / list item body) as lines, line endings and tabs already normalised
     */
    private function blocks(array $lines): string
    {
        $out = '';
        $n = count($lines);
        $i = 0;
        while ($i < $n) {
            $line = $lines[$i];
            if (trim($line) === '') { $i++; continue; }

            // ``` fenced code
            if (preg_match('/^ {0,3}(`{3,}|~{3,})\s*([\w+#.-]*)/', $line, $m)) {
                $fence = $m[1]; $lang = strtolower($m[2]); $code = [];
                $i++;
                while ($i < $n && !preg_match('/^ {0,3}' . preg_quote($fence[0], '/') . '{' . strlen($fence) . ',}\s*$/', $lines[$i])) {
                    $code[] = $lines[$i]; $i++;
                }
                $i++;   // the closing fence (or the end of the file)
                $out .= $this->codeBlock(implode("\n", $code), $lang);
                continue;
            }

            // # heading
            if (preg_match('/^ {0,3}(#{1,6})\s+(.*?)\s*#*\s*$/', $line, $m)) {
                $out .= $this->heading(strlen($m[1]), $m[2]);
                $i++;
                continue;
            }

            // --- rule
            if (preg_match('/^ {0,3}([-*_])( *\1){2,} *$/', $line)) {
                $out .= "<hr>\n";
                $i++;
                continue;
            }

            // > quote / alert
            if (preg_match('/^ {0,3}>/', $line)) {
                $q = [];
                while ($i < $n && trim($lines[$i]) !== '' && (preg_match('/^ {0,3}>/', $lines[$i]) || !$this->startsBlock($lines[$i]))) {
                    $q[] = preg_replace('/^ {0,3}> ?/', '', $lines[$i]);
                    $i++;
                }
                $out .= $this->quote($q);
                continue;
            }

            // | table |
            if (str_contains($line, '|') && $i + 1 < $n && $this->isTableRule($lines[$i + 1])) {
                $rows = [$line, $lines[$i + 1]];
                $i += 2;
                while ($i < $n && trim($lines[$i]) !== '' && str_contains($lines[$i], '|')) { $rows[] = $lines[$i]; $i++; }
                $out .= $this->table($rows);
                continue;
            }

            // - list / 1. list
            if ($this->listMarker($line) !== null) {
                [$html, $i] = $this->listBlock($lines, $i);
                $out .= $html;
                continue;
            }

            // paragraph: up to a blank line or the start of another block
            $p = [];
            while ($i < $n && trim($lines[$i]) !== '' && ($p === [] || !$this->startsBlock($lines[$i]))) {
                $p[] = $lines[$i];
                $i++;
            }
            $out .= '<p>' . $this->inlineLines($p) . "</p>\n";
        }
        return $out;
    }

    /**
     * Would this line start a new block (and so end a paragraph)?
     *
     * @param string $line  one source line
     */
    private function startsBlock(string $line): bool
    {
        return (bool)preg_match('/^ {0,3}(#{1,6}\s|`{3}|~{3}|>|([-*_])( *\2){2,} *$)/', $line)
            || $this->listMarker($line) !== null;
    }

    /**
     * Renders a heading with its GitHub-style anchor; the first h1 becomes the page title and h2/h3 go into the table of contents.
     *
     * @param int $level  1 to 6, the number of # marks
     * @param string $text  the heading text as written, without the # marks - inline Markdown
     */
    private function heading(int $level, string $text): string
    {
        $inner = $this->inline($text);
        $plain = html_entity_decode(strip_tags($inner), ENT_QUOTES, 'UTF-8');
        $id = $this->slug($plain);
        if ($level === 1 && $this->title === null) $this->title = $plain;
        if ($level >= 2 && $level <= 3) $this->toc[] = ['level' => $level, 'text' => $plain, 'id' => $id];
        return "<h$level id=\"" . $this->e($id) . "\"><a class=\"ew-anchor\" href=\"#" . $this->e($id) . "\" aria-hidden=\"true\">#</a>$inner</h$level>\n";
    }

    /**
     * GitHub's anchor rule: lower case, punctuation dropped, spaces to hyphens, repeats numbered.
     *
     * @param string $text  the heading as plain text (tags stripped, entities decoded); repeats are tracked per page
     */
    private function slug(string $text): string
    {
        $s = mb_strtolower(trim($text), 'UTF-8');
        $s = preg_replace('/[^\p{L}\p{N}\s_-]+/u', '', $s);
        $s = preg_replace('/\s/u', '-', $s);
        if ($s === '') $s = 'section';
        $base = $s; $k = 1;
        while (isset($this->slugs[$s])) $s = $base . '-' . $k++;
        $this->slugs[$s] = true;
        return $s;
    }

    /**
     * Renders a block quote, or a GitHub alert box when the first line is [!NOTE], [!TIP], [!IMPORTANT], [!WARNING] or [!CAUTION].
     *
     * @param array $lines  the quote's lines with the leading "> " already removed
     */
    private function quote(array $lines): string
    {
        // GitHub alerts: > [!NOTE] on the first line.
        if ($lines && preg_match('/^\s*\[!(NOTE|TIP|IMPORTANT|WARNING|CAUTION)\]\s*$/i', $lines[0], $m)) {
            $kind = strtolower($m[1]);
            array_shift($lines);
            $label = ucfirst($kind);
            return "<div class=\"ew-alert ew-alert-$kind\"><p class=\"ew-alert-title\">" . $this->e($label) . "</p>\n"
                . $this->blocks($lines) . "</div>\n";
        }
        return "<blockquote>\n" . $this->blocks($lines) . "</blockquote>\n";
    }

    /**
     * True when a line is a table's header separator (| --- | :---: |).
     *
     * @param string $line  the line straight after a candidate header row
     */
    private function isTableRule(string $line): bool
    {
        return (bool)preg_match('/^\s*\|?\s*:?-{1,}:?\s*(\|\s*:?-{1,}:?\s*)+\|?\s*$/', $line)
            || (bool)preg_match('/^\s*\|\s*:?-{1,}:?\s*\|\s*$/', $line);
    }

    /**
     * Splits a table row into trimmed cells, ignoring the outer pipes and any | inside `code` or escaped as \|.
     *
     * @param string $row  one raw table line
     */
    private function cells(string $row): array
    {
        $row = trim($row);
        if ($row !== '' && $row[0] === '|') $row = substr($row, 1);
        if ($row !== '' && substr($row, -1) === '|' && substr($row, -2) !== '\\|') $row = substr($row, 0, -1);
        // A | inside `code` or escaped as \| does not split the row.
        $cells = []; $cur = ''; $inCode = false; $len = strlen($row);
        for ($k = 0; $k < $len; $k++) {
            $ch = $row[$k];
            if ($ch === '\\' && $k + 1 < $len && $row[$k + 1] === '|') { $cur .= '|'; $k++; continue; }
            if ($ch === '`') $inCode = !$inCode;
            if ($ch === '|' && !$inCode) { $cells[] = trim($cur); $cur = ''; continue; }
            $cur .= $ch;
        }
        $cells[] = trim($cur);
        return $cells;
    }

    /**
     * Renders a table from its header, separator and body rows, applying the column alignment.
     *
     * @param array $rows  raw table lines: [0] the header, [1] the | --- | separator, then the body rows
     */
    private function table(array $rows): string
    {
        $head = $this->cells($rows[0]);
        $align = [];
        foreach ($this->cells($rows[1]) as $k => $c) {
            $l = $c !== '' && $c[0] === ':'; $r = $c !== '' && substr($c, -1) === ':';
            $align[$k] = $l && $r ? 'center' : ($r ? 'right' : ($l ? 'left' : ''));
        }
        $a = fn($k) => !empty($align[$k]) ? ' style="text-align:' . $align[$k] . '"' : '';
        $h = "<div class=\"ew-table-wrap\"><table>\n<thead><tr>";
        foreach ($head as $k => $c) $h .= "<th{$a($k)}>" . $this->inline($c) . '</th>';
        $h .= "</tr></thead>\n<tbody>\n";
        for ($r = 2; $r < count($rows); $r++) {
            $cells = $this->cells($rows[$r]);
            $h .= '<tr>';
            for ($k = 0; $k < count($head); $k++) $h .= "<td{$a($k)}>" . $this->inline($cells[$k] ?? '') . '</td>';
            $h .= "</tr>\n";
        }
        return $h . "</tbody></table></div>\n";
    }

    /**
     * Reads a list item marker (- * + or 1. 1)) at the start of a line; null when
     * the line is not a list item, including a --- rule.
     *
     * @param string $line  one source line
     * @return ?array{indent:int, ordered:bool, start:int, width:int}
     */
    private function listMarker(string $line): ?array
    {
        if (!preg_match('/^( *)([-*+]|(\d{1,9})[.)])( +|$)/', $line, $m)) return null;
        // "---" / "***" is a rule, not a list.
        if (preg_match('/^ {0,3}([-*_])( *\1){2,} *$/', $line)) return null;
        return ['indent' => strlen($m[1]), 'ordered' => $m[3] !== '', 'start' => $m[3] !== '' ? (int)$m[3] : 1, 'width' => strlen($m[0])];
    }

    /**
     * One list (and its nested lists).
     *
     * @param array $lines  every line of the block being rendered
     * @param int $i  the index in $lines of the list's first item
     * @return array{0:string,1:int} [html, next line]
     */
    private function listBlock(array $lines, int $i): array
    {
        $first = $this->listMarker($lines[$i]);
        $ordered = $first['ordered'];
        $base = $first['indent'];
        $items = [];
        $n = count($lines);
        $loose = false;
        while ($i < $n) {
            $mk = $this->listMarker($lines[$i]);
            if ($mk === null || $mk['indent'] !== $base || $mk['ordered'] !== $ordered) break;
            $content = [substr($lines[$i], $mk['width'])];
            $contIndent = $mk['width'];
            $i++;
            $sawBlank = false;
            while ($i < $n) {
                $l = $lines[$i];
                if (trim($l) === '') { $sawBlank = true; $content[] = ''; $i++; continue; }
                $ind = strlen($l) - strlen(ltrim($l, ' '));
                $next = $this->listMarker($l);
                if ($next !== null && $next['indent'] <= $base) break;          // a sibling (or an outer item)
                if ($ind >= $contIndent || ($next !== null && $next['indent'] > $base)) {
                    $content[] = substr($l, min($ind, $contIndent));
                    if ($sawBlank) $loose = true;
                    $sawBlank = false;
                    $i++;
                    continue;
                }
                if ($sawBlank || $this->startsBlock($l)) break;                    // the list is over
                $content[] = ltrim($l);                                           // lazy continuation
                $i++;
            }
            while ($content && trim(end($content)) === '') array_pop($content);
            $items[] = $content;
            // A blank line between items makes the list loose.
            // (Only for another item of THIS list - a different list after a gap is not a gap inside this one.)
            if ($i < $n && $i > 0 && trim($lines[$i - 1]) === '' && ($nx = $this->listMarker($lines[$i])) && $nx['indent'] === $base && $nx['ordered'] === $ordered) $loose = true;
        }

        $tag = $ordered ? 'ol' : 'ul';
        $start = $ordered && $first['start'] !== 1 ? ' start="' . $first['start'] . '"' : '';
        $hasTasks = false;
        $html = '';
        foreach ($items as $content) {
            $task = '';
            if (preg_match('/^\[([ xX])\]\s+/', $content[0], $tm)) {
                $hasTasks = true;
                $task = '<input type="checkbox" disabled' . (strtolower($tm[1]) === 'x' ? ' checked' : '') . '> ';
                $content[0] = substr($content[0], strlen($tm[0]));
            }
            $body = $this->blocks($content);
            if (!$loose) $body = preg_replace('#^<p>(.*?)</p>\n#s', '$1' . "\n", $body, 1);   // tight: no <p> round the text
            $html .= '<li' . ($task !== '' ? ' class="ew-task"' : '') . '>' . $task . rtrim($body) . "</li>\n";
        }
        $cls = $hasTasks ? ' class="ew-tasks"' : '';
        return ["<$tag$start$cls>\n$html</$tag>\n", $i];
    }

    // ------------------------------------------------------------------ code

    /**
     * Renders a fenced code block with its language label, highlighted when the language is one it knows.
     *
     * @param string $code  the code between the fences, unescaped
     * @param string $lang  the lower-cased language after the opening fence ("php", "sql"...), '' for none
     */
    private function codeBlock(string $code, string $lang): string
    {
        $alias = ['php' => 'php', 'sql' => 'sql', 'mysql' => 'sql', 'js' => 'js', 'javascript' => 'js', 'json' => 'js',
                  'bash' => 'sh', 'sh' => 'sh', 'shell' => 'sh', 'powershell' => 'sh', 'ps1' => 'sh', 'css' => 'css'];
        $kind = $alias[$lang] ?? '';
        $body = $kind !== '' ? $this->highlight($code, $kind) : $this->e($code);
        $label = $lang !== '' ? '<span class="ew-code-lang">' . $this->e($lang) . '</span>' : '';
        return "<div class=\"ew-code\">$label<pre><code" . ($lang !== '' ? ' class="language-' . $this->e($lang) . '"' : '') . ">$body</code></pre></div>\n";
    }

    /**
     * Just enough colour to read by: comments, strings, numbers, keywords, variables.
     *
     * @param string $code  the code to colour, unescaped (every piece is escaped here)
     * @param string $kind  'php', 'sql', 'js', 'sh' or 'css' - the alias codeBlock() mapped the language to
     */
    private function highlight(string $code, string $kind): string
    {
        $kw = [
            'php' => 'abstract|and|array|as|break|case|catch|class|const|continue|declare|default|do|echo|else|elseif|empty|enum|extends|false|final|finally|fn|for|foreach|function|global|if|implements|include|include_once|instanceof|interface|isset|list|match|namespace|new|null|or|private|protected|public|readonly|require|require_once|return|static|switch|throw|trait|true|try|unset|use|var|while|yield|self|parent|int|string|bool|float|void|mixed|iterable|callable|object|never',
            'js'  => 'async|await|break|case|catch|class|const|continue|default|delete|do|else|export|extends|false|finally|for|function|if|import|in|instanceof|let|new|null|of|return|static|super|switch|this|throw|true|try|typeof|undefined|var|void|while|yield',
            'sql' => 'select|from|where|and|or|not|in|is|null|as|join|left|right|inner|outer|on|group|by|order|having|limit|offset|insert|into|values|update|set|delete|create|table|alter|add|drop|index|primary|key|foreign|references|unique|default|distinct|count|sum|min|max|avg|case|when|then|else|end|exists|between|like|union|all|asc|desc|int|varchar|text|datetime|date|decimal|tinyint|bigint|auto_increment|coalesce|if|interval|duplicate',
            'sh'  => 'if|then|else|fi|for|in|do|done|while|case|esac|function|return|export|echo|cd|sudo',
            'css' => '',
        ][$kind];
        $ci = $kind === 'sql' ? 'i' : '';
        $patterns = [
            'c' => $kind === 'sql' ? '--[^\n]*|\/\*[\s\S]*?\*\/' : ($kind === 'sh' ? '#[^\n]*' : '\/\/[^\n]*|\/\*[\s\S]*?\*\/|#(?!\[)[^\n]*'),
            's' => '"(?:\\\\.|[^"\\\\])*"|\'(?:\\\\.|[^\'\\\\])*\'|`(?:\\\\.|[^`\\\\])*`',
            'v' => $kind === 'php' ? '\$[A-Za-z_]\w*' : '(?!)',
            'n' => '\b\d+(?:\.\d+)?\b',
            'k' => $kw !== '' ? '\b(?:' . $kw . ')\b' : '(?!)',
        ];
        if ($kind === 'js') $patterns['c'] = '\/\/[^\n]*|\/\*[\s\S]*?\*\/';
        $re = '/(' . implode(')|(', $patterns) . ')/' . $ci;
        $out = ''; $pos = 0;
        $classes = ['c' => 'ew-c', 's' => 'ew-s', 'v' => 'ew-v', 'n' => 'ew-n', 'k' => 'ew-k'];
        $keys = array_keys($patterns);
        if (!preg_match_all($re, $code, $all, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) return $this->e($code);
        foreach ($all as $m) {
            [$text, $at] = $m[0];
            if ($at < $pos) continue;
            $out .= $this->e(substr($code, $pos, $at - $pos));
            $cls = 'ew-k';
            for ($g = 1; $g <= count($keys); $g++) if (isset($m[$g]) && $m[$g][1] !== -1 && $m[$g][0] !== '') { $cls = $classes[$keys[$g - 1]]; break; }
            $out .= '<span class="' . $cls . '">' . $this->e($text) . '</span>';
            $pos = $at + strlen($text);
        }
        return $out . $this->e(substr($code, $pos));
    }

    // ------------------------------------------------------------------ inline

    /**
     * Renders a paragraph's lines as inline HTML, turning two trailing spaces or a trailing backslash into a line break.
     *
     * @param array $lines  the paragraph's source lines, in order
     */
    private function inlineLines(array $lines): string
    {
        // Two trailing spaces or a trailing backslash is a hard line break.
        $parts = [];
        foreach ($lines as $k => $l) {
            $hard = $k < count($lines) - 1 && (preg_match('/ {2,}$/', $l) || substr(rtrim($l, ' '), -1) === '\\');
            $l = trim($l);
            if ($hard && substr($l, -1) === '\\') $l = substr($l, 0, -1);
            $parts[] = $this->inline($l) . ($hard ? '<br>' : '');
        }
        return implode("\n", $parts);
    }

    /**
     * Parks finished HTML (code, links, escapes) behind a placeholder so later inline passes cannot touch it.
     *
     * @param string $html  finished, already-escaped HTML
     */
    private function hold(string $html): string
    {
        $this->holds[] = $html;
        return "\x1A" . (count($this->holds) - 1) . "\x1A";
    }

    /**
     * Renders inline Markdown - escapes, code, images, links, wiki links and autolinks, then bold, italic and strike - with all other text escaped.
     *
     * @param string $t  one piece of Markdown text (a line, a heading, a cell or a link label) - raw, escaped here
     */
    private function inline(string $t): string
    {
        $base = count($this->holds);
        // 1. backslash escapes and `code` first - nothing inside them is formatting.
        $t = preg_replace_callback('/\\\\([\\\\`*_{}\[\]()#+\-.!|~<>])/', fn($m) => $this->hold($this->e($m[1])), $t);
        $t = preg_replace_callback('/(`+)(.+?)(?<!`)\1(?!`)/s', fn($m) => $this->hold('<code>' . $this->e(trim($m[2]) !== '' ? (preg_match('/^ .* $/s', $m[2]) ? substr($m[2], 1, -1) : $m[2]) : $m[2]) . '</code>'), $t);
        // 2. images, links, wiki links and autolinks, before escaping so the URLs stay whole.
        $t = preg_replace_callback('/!\[([^\]]*)\]\(\s*<?([^)\s>]+)>?(?:\s+"([^"]*)")?\s*\)/', function ($m) {
            if (!preg_match('#^https?://#i', $m[2])) return $this->hold($this->e($m[0]));   // no local files - nothing to serve them from
            return $this->hold('<img src="' . $this->e($m[2]) . '" alt="' . $this->e($m[1]) . '"' . (!empty($m[3]) ? ' title="' . $this->e($m[3]) . '"' : '') . ' loading="lazy">');
        }, $t);
        $t = preg_replace_callback('/\[\[([^\]|]+)(?:\|([^\]]+))?\]\]/', fn($m) => $this->hold($this->link(trim($m[1]), $this->inline(trim($m[2] ?? $m[1])), true)), $t);
        $t = preg_replace_callback('/\[((?:[^\[\]]|\[[^\[\]]*\])+)\]\(\s*<?([^)\s>]*)>?(?:\s+"([^"]*)")?\s*\)/', fn($m) => $this->hold($this->link($m[2], $this->inline($m[1]))), $t);
        $t = preg_replace_callback('/<(https?:\/\/[^>\s]+)>/i', fn($m) => $this->hold($this->link($m[1], $this->e($m[1]))), $t);
        $t = preg_replace_callback('/(?<![\w\/"\'=])(https?:\/\/[^\s<>()]+[^\s<>().,;:!?\'"])/i', fn($m) => $this->hold($this->link($m[1], $this->e($m[1]))), $t);
        // 3. everything else is text.
        $t = $this->e($t);
        // 4. emphasis.
        $t = preg_replace('/(\*\*|__)(?=\S)(.+?)(?<=\S)\1/s', '<strong>$2</strong>', $t);
        $t = preg_replace('/(?<![\w*])\*(?=[^\s*])(.+?)(?<=[^\s*])\*(?![\w*])/s', '<em>$1</em>', $t);
        $t = preg_replace('/(?<![\w_])_(?=[^\s_])(.+?)(?<=[^\s_])_(?![\w_])/s', '<em>$1</em>', $t);
        $t = preg_replace('/~~(?=\S)(.+?)(?<=\S)~~/s', '<del>$1</del>', $t);
        // 5. put the held pieces back (they may nest, so repeat until none are left).
        for ($guard = 0; $guard < 5 && str_contains($t, "\x1A"); $guard++) {
            $t = preg_replace_callback('/\x1A(\d+)\x1A/', fn($m) => $this->holds[(int)$m[1]] ?? '', $t);
        }
        if (count($this->holds) > $base + 500) $this->holds = array_slice($this->holds, 0, $base);
        return $t;
    }

    /**
     * Builds a link - external ones open in a new window, #anchors stay on the page, and wiki paths go through the resolver (a page it cannot find is shown as a broken link).
     *
     * @param string $href  the link target as written: a URL, a #anchor, a relative .md path, or a wiki path when $wiki
     * @param string $label  the link text already rendered to HTML - inserted as is, so it must be escaped first
     * @param bool $wiki  true for a [[wiki link]], whose path is from the wiki root and may omit ".md"
     */
    private function link(string $href, string $label, bool $wiki = false): string
    {
        if (preg_match('#^(https?:|mailto:)#i', $href)) {
            return '<a href="' . $this->e($href) . '" target="_blank" rel="noopener noreferrer" class="ew-external">' . $label . '</a>';
        }
        if ($href !== '' && $href[0] === '#') {
            return '<a href="' . $this->e($href) . '">' . $label . '</a>';
        }
        // [[wiki links]] name a page from the wiki root ("tables/leases"); ordinary
        // links are relative to the page they are on, as on GitHub.
        if ($wiki) {
            $frag = '';
            if (($h = strpos($href, '#')) !== false) { $frag = substr($href, $h); $href = substr($href, 0, $h); }
            $href = '/' . ltrim($href, '/') . (preg_match('/\.md$/', $href) ? '' : '.md') . $frag;
        }
        $page = ($this->resolve)($href);
        if ($page === null) {
            return '<span class="ew-badlink" title="' . $this->e($href) . '">' . $label . '</span>';
        }
        return '<a href="?page=' . $this->e($page) . '" data-ew-page="' . $this->e($page) . '">' . $label . '</a>';
    }

    /**
     * Escapes text for HTML.
     *
     * @param string $s  plain text
     */
    private function e(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
