<?php
/**
 * IssueDoc — one description/comment, written once, rendered per provider.
 *
 * The problem this solves: every issue tracker wants a different body format.
 * Jira Cloud's v3 API wants ADF (Atlassian Document Format — a nested JSON
 * document). Jira Data Center's v2 API wants wiki markup. GitHub and GitLab want
 * Markdown. Azure DevOps wants HTML. If core built a string, every one of those
 * would need its own string-building code and they would drift.
 *
 * So core never builds a string. It builds an IssueDoc — an ordered list of
 * blocks — and the connector asks for the shape it needs:
 *
 *     $doc = (new IssueDoc)
 *         ->heading('Raised in FreeITSM')
 *         ->para('Ticket ', IssueDoc::link($url, 'SD-1042'), ' · Jane Doe (Acme)')
 *         ->rule()
 *         ->para($ticket['description']);
 *
 *     $doc->toAdf();        // Jira Cloud   (array, JSON-encode it)
 *     $doc->toWikiMarkup(); // Jira DC
 *     $doc->toMarkdown();   // GitHub / GitLab
 *     $doc->toHtml();       // Azure DevOps
 *
 * The renderers live HERE rather than in each connector on purpose: a new
 * provider almost always reuses one of these four, so adding GitHub is
 * "renderDoc() returns $doc->toMarkdown()" and not a new renderer. That is what
 * makes the connector-per-provider cost small.
 *
 * Deliberately small — heading, paragraph, link, bullet list, code block, rule.
 * This is not a rich-text format. It is enough to make a readable issue and no
 * more; anything bigger becomes a second sanitiser to keep safe.
 *
 * ⚠️ Text is always treated as PLAIN TEXT and escaped for the target format.
 * Never pass HTML in and expect it to survive — pass the text, use the blocks.
 */

class IssueDoc
{
    /** @var array ordered blocks; see the const TYPE_* below */
    private $blocks = [];

    const T_HEADING = 'heading';
    const T_PARA    = 'para';
    const T_BULLETS = 'bullets';
    const T_CODE    = 'code';
    const T_RULE    = 'rule';

    // ---------------------------------------------------------------- building

    /**
     * A link, for use inside para(). Returns the inline marker para() understands.
     *
     * @param string $href  the link's URL
     * @param string $text  the link text, plain
     */
    public static function link(string $href, string $text): array
    {
        return ['_link' => true, 'href' => $href, 'text' => $text];
    }

    /**
     * Adds a heading block; blank text is ignored.
     *
     * @param string $text  the heading, plain text
     */
    public function heading(string $text): self
    {
        if (trim($text) !== '') {
            $this->blocks[] = [self::T_HEADING, $text];
        }
        return $this;
    }

    /**
     * A paragraph of inline parts. Each part is either a plain string or the
     * array returned by self::link(). Empty parts are dropped; a paragraph that
     * ends up with nothing in it is not added at all (an empty paragraph is
     * invalid in ADF, and pointless everywhere else).
      *
      * @param mixed ...$parts  plain strings and link() arrays, in reading order
     */
    public function para(...$parts): self
    {
        $inlines = [];
        foreach ($parts as $p) {
            if (is_array($p) && !empty($p['_link'])) {
                if ($p['text'] !== '' && $p['href'] !== '') {
                    $inlines[] = $p;
                }
                continue;
            }
            $s = (string)$p;
            if ($s !== '') {
                $inlines[] = $s;
            }
        }
        if ($inlines) {
            $this->blocks[] = [self::T_PARA, $inlines];
        }
        return $this;
    }

    /**
     * Adds a bullet list, dropping blank items; nothing is added if every item is blank.
     *
     * @param string[] $items  the list items, plain text
     */
    public function bullets(array $items): self
    {
        $clean = [];
        foreach ($items as $i) {
            $s = trim((string)$i);
            if ($s !== '') $clean[] = $s;
        }
        if ($clean) {
            $this->blocks[] = [self::T_BULLETS, $clean];
        }
        return $this;
    }

    /**
     * Adds a code block; an empty string is ignored.
     *
     * @param string $text  the code, kept verbatim
     */
    public function code(string $text): self
    {
        if ($text !== '') {
            $this->blocks[] = [self::T_CODE, $text];
        }
        return $this;
    }

    /** Adds a horizontal rule, unless the document is empty or already ends with one. */
    public function rule(): self
    {
        // A leading rule, or two in a row, is always a mistake in generated
        // output — swallow it rather than making every caller check.
        if ($this->blocks && end($this->blocks)[0] !== self::T_RULE) {
            $this->blocks[] = [self::T_RULE, null];
        }
        return $this;
    }

    /** True when no block has been added. */
    public function isEmpty(): bool
    {
        return $this->blocks === [];
    }

    /** The raw block list. For tests and for a connector that needs its own renderer. */
    public function blocks(): array
    {
        return $this->blocks;
    }

    // --------------------------------------------------------------- rendering

    /**
     * Atlassian Document Format (Jira Cloud v3). Returns the PHP array — the
     * caller JSON-encodes it, because it is usually one field inside a bigger
     * request body.
     *
     * ⚠️ ADF rejects empty text nodes and empty paragraphs, which is why the
     * builders above refuse to create them.
     */
    public function toAdf(): array
    {
        $content = [];
        foreach ($this->blocks as $b) {
            list($type, $val) = $b;
            switch ($type) {
                case self::T_HEADING:
                    $content[] = [
                        'type'    => 'heading',
                        'attrs'   => ['level' => 3],
                        'content' => [['type' => 'text', 'text' => $val]],
                    ];
                    break;
                case self::T_PARA:
                    $content[] = ['type' => 'paragraph', 'content' => $this->adfInlines($val)];
                    break;
                case self::T_BULLETS:
                    $items = [];
                    foreach ($val as $i) {
                        $items[] = ['type' => 'listItem', 'content' => [
                            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => $i]]],
                        ]];
                    }
                    $content[] = ['type' => 'bulletList', 'content' => $items];
                    break;
                case self::T_CODE:
                    $content[] = ['type' => 'codeBlock', 'content' => [['type' => 'text', 'text' => $val]]];
                    break;
                case self::T_RULE:
                    $content[] = ['type' => 'rule'];
                    break;
            }
        }
        // A doc with no content is invalid; one empty paragraph is the valid
        // way to say "nothing here".
        if (!$content) {
            $content = [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => ' ']]]];
        }
        return ['type' => 'doc', 'version' => 1, 'content' => $content];
    }

    /**
     * Converts a paragraph's parts to ADF text nodes, giving links a link mark.
     *
     * @param array $inlines  a paragraph's parts as para() stored them - strings and link() arrays
     */
    private function adfInlines(array $inlines): array
    {
        $out = [];
        foreach ($inlines as $p) {
            if (is_array($p)) {
                $out[] = [
                    'type'  => 'text',
                    'text'  => $p['text'],
                    'marks' => [['type' => 'link', 'attrs' => ['href' => $p['href']]]],
                ];
            } else {
                $out[] = ['type' => 'text', 'text' => $p];
            }
        }
        return $out;
    }

    /** GitHub, GitLab. */
    public function toMarkdown(): string
    {
        $out = [];
        foreach ($this->blocks as $b) {
            list($type, $val) = $b;
            switch ($type) {
                case self::T_HEADING:
                    $out[] = '### ' . $this->mdEscape($val);
                    break;
                case self::T_PARA:
                    $line = '';
                    foreach ($val as $p) {
                        $line .= is_array($p)
                            ? '[' . $this->mdEscape($p['text']) . '](' . $p['href'] . ')'
                            : $this->mdEscape($p);
                    }
                    $out[] = $line;
                    break;
                case self::T_BULLETS:
                    $lines = [];
                    foreach ($val as $i) $lines[] = '- ' . $this->mdEscape($i);
                    $out[] = implode("\n", $lines);
                    break;
                case self::T_CODE:
                    $out[] = "```\n" . $val . "\n```";
                    break;
                case self::T_RULE:
                    $out[] = '---';
                    break;
            }
        }
        return implode("\n\n", $out);
    }

    /** Jira Data Center / Server (v2 API). */
    public function toWikiMarkup(): string
    {
        $out = [];
        foreach ($this->blocks as $b) {
            list($type, $val) = $b;
            switch ($type) {
                case self::T_HEADING:
                    $out[] = 'h3. ' . $val;
                    break;
                case self::T_PARA:
                    $line = '';
                    foreach ($val as $p) {
                        $line .= is_array($p)
                            ? '[' . $this->wikiEscape($p['text']) . '|' . $p['href'] . ']'
                            : $this->wikiEscape($p);
                    }
                    $out[] = $line;
                    break;
                case self::T_BULLETS:
                    $lines = [];
                    foreach ($val as $i) $lines[] = '* ' . $this->wikiEscape($i);
                    $out[] = implode("\n", $lines);
                    break;
                case self::T_CODE:
                    $out[] = "{code}\n" . $val . "\n{code}";
                    break;
                case self::T_RULE:
                    $out[] = '----';
                    break;
            }
        }
        return implode("\n\n", $out);
    }

    /** Azure DevOps (System.Description is HTML). */
    public function toHtml(): string
    {
        $out = [];
        foreach ($this->blocks as $b) {
            list($type, $val) = $b;
            switch ($type) {
                case self::T_HEADING:
                    $out[] = '<h3>' . $this->h($val) . '</h3>';
                    break;
                case self::T_PARA:
                    $line = '';
                    foreach ($val as $p) {
                        $line .= is_array($p)
                            ? '<a href="' . $this->h($p['href']) . '">' . $this->h($p['text']) . '</a>'
                            : $this->h($p);
                    }
                    $out[] = '<p>' . $line . '</p>';
                    break;
                case self::T_BULLETS:
                    $lis = '';
                    foreach ($val as $i) $lis .= '<li>' . $this->h($i) . '</li>';
                    $out[] = '<ul>' . $lis . '</ul>';
                    break;
                case self::T_CODE:
                    $out[] = '<pre>' . $this->h($val) . '</pre>';
                    break;
                case self::T_RULE:
                    $out[] = '<hr>';
                    break;
            }
        }
        return implode("\n", $out);
    }

    /** Plain text — for the escalate preview, and for logs. */
    public function toPlainText(): string
    {
        $out = [];
        foreach ($this->blocks as $b) {
            list($type, $val) = $b;
            switch ($type) {
                case self::T_HEADING:
                    $out[] = $val;
                    break;
                case self::T_PARA:
                    $line = '';
                    foreach ($val as $p) {
                        $line .= is_array($p) ? $p['text'] . ' (' . $p['href'] . ')' : $p;
                    }
                    $out[] = $line;
                    break;
                case self::T_BULLETS:
                    $lines = [];
                    foreach ($val as $i) $lines[] = '- ' . $i;
                    $out[] = implode("\n", $lines);
                    break;
                case self::T_CODE:
                    $out[] = $val;
                    break;
                case self::T_RULE:
                    $out[] = '---';
                    break;
            }
        }
        return implode("\n\n", $out);
    }

    // ---------------------------------------------------------------- escaping

    /**
     * HTML-escapes text for toHtml().
     *
     * @param string $s  plain text to escape
     */
    private function h(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Markdown: neutralise the characters that would otherwise turn a ticket's
     * text into formatting. A requester writing "use the * wildcard" must not
     * produce italics, and "[see notes]" must not look like a broken link.
      *
      * @param string $s  plain text to escape
     */
    private function mdEscape(string $s): string
    {
        return preg_replace('/([\\\\`*_\[\]()#+\-!>|~])/', '\\\\$1', $s);
    }

    /**
     * Jira wiki markup: the same idea, different character set.
     *
     * @param string $s  plain text to escape
     */
    private function wikiEscape(string $s): string
    {
        return preg_replace('/([\[\]{}*_+\-|!^~?])/', '\\\\$1', $s);
    }
}
