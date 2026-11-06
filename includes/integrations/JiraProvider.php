<?php
/**
 * JiraProvider — Jira Cloud and Jira Data Center / Server behind one contract.
 *
 * ── Cloud vs Data Center ────────────────────────────────────────────────────
 *
 * They are the same product with two genuinely different APIs, and the
 * differences are not cosmetic:
 *
 *                        Cloud                     Data Center / Server
 *   REST base            /rest/api/3               /rest/api/2
 *   Description format   ADF (nested JSON)         wiki markup (a string)
 *   Auth                 email + API token (Basic) Personal Access Token (Bearer)
 *   User identity        accountId                 username ("name")
 *   Project list         /project/search (paged)   /project (a plain array)
 *
 * Rather than two providers with 80% duplication, this is ONE provider with a
 * `flavour`. It is decided once at testConnection() and cached in the
 * connection's credentials blob, falling back to a base-URL heuristic
 * (*.atlassian.net is always Cloud; Data Center never is) so a connection saved
 * before the sniff still behaves.
 *
 * ── Testability ─────────────────────────────────────────────────────────────
 *
 * Payload building and response parsing are separate, protected methods rather
 * than being inlined into the HTTP calls. That is deliberate: it means the
 * interesting logic — the ADF-vs-wiki choice, the status mapping, the error
 * extraction — is provable without a live Jira site. tests/integrations/run.php
 * subclasses this and stubs httpRequest().
 *
 * ⚠️ Status categories: Jira's own statusCategory is the ONLY stable thing here.
 * Status *names* are per-project, per-workflow and renamed at will, so nothing
 * branches on them. See the note on tickets.merged_into_id for the last time
 * keying on a status name cost us.
 */

require_once __DIR__ . '/IssueTrackerProvider.php';
require_once __DIR__ . '/IssueDoc.php';

class JiraProvider extends IssueTrackerProvider
{
    const FLAVOUR_CLOUD  = 'cloud';
    const FLAVOUR_SERVER = 'server';

    /** The optional features the Jira connector supports, priorities included. */
    protected function capabilities(): array
    {
        return [
            self::CAP_ISSUE_TYPES,
            self::CAP_PRIORITIES,
            self::CAP_ATTACHMENTS,
            self::CAP_WEBHOOKS,
            self::CAP_POLLING,
            self::CAP_CUSTOM_FIELDS,
        ];
    }

    // ------------------------------------------------------------- flavour

    /** 'cloud' | 'server'. Sniffed at testConnection, cached in credentials. */
    public function flavour(): string
    {
        $stored = $this->connection['credentials']['flavour'] ?? '';
        if ($stored === self::FLAVOUR_CLOUD || $stored === self::FLAVOUR_SERVER) {
            return $stored;
        }
        // Heuristic fallback. Jira Cloud is always on atlassian.net; Data Center
        // never is. Only used until the first successful connection test.
        $host = strtolower((string) parse_url($this->baseUrl(), PHP_URL_HOST));
        return (substr($host, -14) === '.atlassian.net' || $host === 'atlassian.net')
            ? self::FLAVOUR_CLOUD
            : self::FLAVOUR_SERVER;
    }

    /** True when the connection is Jira Cloud rather than Server / Data Center. */
    protected function isCloud(): bool
    {
        return $this->flavour() === self::FLAVOUR_CLOUD;
    }

    /** REST API version this flavour speaks. */
    protected function apiVersion(): int
    {
        return $this->isCloud() ? 3 : 2;
    }

    /** The connection's Jira address without a trailing slash. */
    protected function baseUrl(): string
    {
        return rtrim((string)($this->connection['base_url'] ?? ''), '/');
    }

    /**
     * A REST API address for $path, on API version 3 (Cloud) or 2 (Server / Data Center).
     *
     * @param string $path  the route under the REST base, e.g. 'issue/10042'; a leading slash is dropped
     */
    protected function apiUrl(string $path): string
    {
        return $this->baseUrl() . '/rest/api/' . $this->apiVersion() . '/' . ltrim($path, '/');
    }

    /**
     * The human-facing link we store on the link row and show in the panel.
     *
     * @param string $key  the issue key, e.g. 'OPS-123'
     */
    public function browseUrl(string $key): string
    {
        return $this->baseUrl() . '/browse/' . rawurlencode($key);
    }

    // ------------------------------------------------------------ requests

    /**
     * Auth differs by flavour: Cloud uses HTTP Basic with the user's email and
     * an API token; Data Center uses a Bearer Personal Access Token.
      *
      * @param string $method  the HTTP method - 'GET' or 'POST'
      * @param string $url  the full URL from apiUrl(), query string included
      * @param ?array $json  the body to JSON-encode; null sends no body
     */
    protected function jiraRequest(string $method, string $url, ?array $json = null): array
    {
        $creds   = $this->connection['credentials'] ?? [];
        $headers = ['Accept: application/json'];
        $opts    = ['method' => $method, 'headers' => &$headers];

        if ($this->isCloud()) {
            $opts['auth'] = ($creds['email'] ?? '') . ':' . ($creds['api_token'] ?? '');
        } else {
            $headers[] = 'Authorization: Bearer ' . ($creds['api_token'] ?? '');
        }
        if ($json !== null) {
            $headers[]     = 'Content-Type: application/json';
            $opts['body']  = json_encode($json);
        }
        $opts['headers'] = $headers;

        list($code, $body) = $this->httpRequest($url, $opts);
        $decoded = json_decode($body, true);

        if ($code < 200 || $code >= 300) {
            throw new Exception($this->extractError($code, $body));
        }
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Turn a Jira error response into something an analyst can act on.
     *
     * Jira reports field-level problems in `errors` (e.g. {"customfield_10010":
     * "Epic Link is required"}) and general ones in `errorMessages`. Surfacing
     * only "HTTP 400" would make the most common failure — a project whose screen
     * scheme demands a field we did not send — completely undiagnosable.
      *
      * @param int $code  the HTTP status code
      * @param string $rawBody  the response body as received
     */
    protected function extractError(int $code, string $rawBody): string
    {
        $d     = json_decode($rawBody, true);
        $parts = [];

        if (is_array($d)) {
            foreach ((array)($d['errorMessages'] ?? []) as $m) {
                if (is_string($m) && $m !== '') $parts[] = $m;
            }
            foreach ((array)($d['errors'] ?? []) as $field => $m) {
                if (is_string($m) && $m !== '') $parts[] = "$field: $m";
            }
        }
        if (!$parts) {
            if ($code === 401 || $code === 403) {
                $parts[] = 'Jira rejected the credentials (HTTP ' . $code . ').';
            } elseif ($code === 404) {
                $parts[] = 'Jira returned 404 — check the site URL and that the issue or project exists.';
            } else {
                $snippet = trim(strip_tags($rawBody));
                $parts[] = 'Jira returned HTTP ' . $code
                         . ($snippet !== '' ? ': ' . mb_substr($snippet, 0, 200) : '.');
            }
        }
        return implode(' ', $parts);
    }

    // ----------------------------------------------------------- rendering

    /**
     * ADF (an array) on Cloud; wiki markup (a string) on Data Center.
     *
     * @param IssueDoc $doc  the description or comment to render
     */
    public function renderDoc(IssueDoc $doc)
    {
        return $this->isCloud() ? $doc->toAdf() : $doc->toWikiMarkup();
    }

    // -------------------------------------------------------------- create

    /**
     * Builds the JSON body for creating an issue - project, issue type, a one-line summary
     * of up to 255 characters, the rendered description and any mapped fields.
     *
     * Throws when no project or issue type was given or mapped.
     *
     * @param array $target ['project' => 'OPS', 'issue_type' => 'Bug']
      * @param string $summary  the ticket subject; whitespace collapsed, blank becomes '(no subject)'
      * @param IssueDoc $body  the description
      * @param array $fields  mapped Jira fields keyed by field id (e.g. 'priority' => ['name' => 'High']), merged last
     */
    protected function buildCreatePayload(array $target, string $summary, IssueDoc $body, array $fields = []): array
    {
        $project = trim((string)($target['project'] ?? ''));
        $type    = trim((string)($target['issue_type'] ?? ''));
        // Since V3 these can be left blank on a rule and filled in by the
        // connection's mapping, so "you did not choose one" is no longer the
        // whole story — the mapping may simply not cover this ticket.
        if ($project === '') throw new Exception('No Jira project: none was given and no mapping matched this ticket. Set one on the escalation, or add a project mapping under System → Integrations.');
        if ($type === '')    throw new Exception('No Jira issue type: none was given and no mapping matched this ticket. Set one on the escalation, or add an issue-type mapping under System → Integrations.');

        // Summary is a single line in Jira; a pasted subject with a newline in it
        // is rejected with an unhelpful error, so normalise rather than fail.
        $summary = trim(preg_replace('/\s+/u', ' ', $summary));
        if ($summary === '') $summary = '(no subject)';
        if (mb_strlen($summary) > 255) $summary = mb_substr($summary, 0, 252) . '…';

        $payload = ['fields' => array_merge([
            'project'     => ['key' => $project],
            'issuetype'   => ['name' => $type],
            'summary'     => $summary,
            'description' => $this->renderDoc($body),
        ], $fields)];

        // NOTE: priority is deliberately NOT set from the ticket's priority.
        // Jira priorities are per-project with arbitrary names, so sending one
        // 400s on any project that renamed them. Priority travels as text in the
        // description instead. Mapped priorities arrive in V3 via $fields, where
        // an admin has explicitly said which name means which.

        return $payload;
    }

    /**
     * Creates an issue and returns it as the escalation panel needs it.
     *
     * Reads the new issue back to get its status; if that read fails the creation still
     * stands and the status is left empty.
      *
      * @param array $target  ['project' => 'OPS', 'issue_type' => 'Bug'] - both required
      * @param string $summary  the ticket subject
      * @param IssueDoc $body  the description
      * @param array $fields  mapped Jira fields, as for buildCreatePayload()
     */
    public function createIssue(array $target, string $summary, IssueDoc $body, array $fields = []): array
    {
        $created = $this->jiraRequest('POST', $this->apiUrl('issue'), $this->buildCreatePayload($target, $summary, $body, $fields));

        $id  = (string)($created['id'] ?? '');
        $key = (string)($created['key'] ?? '');
        if ($id === '') {
            throw new Exception('Jira accepted the issue but returned no id.');
        }

        // Create does not return status, and the panel would otherwise show a
        // blank until the first poll. One extra read is worth a correct panel —
        // but if it fails the issue still EXISTS, so never let this throw away a
        // successful creation.
        try {
            return $this->fetchIssue($id);
        } catch (Exception) {
            return [
                'external_id'     => $id,
                'external_key'    => $key,
                'external_url'    => $this->browseUrl($key),
                'status_name'     => null,
                'status_category' => null,
                'assignee_name'   => null,
            ];
        }
    }

    // --------------------------------------------------------------- read

    /**
     * Jira's issue JSON → our normalised shape.
     *
     * @param array $issue  one issue as the Jira REST API returns it - id, key and fields
     */
    protected function parseIssue(array $issue): array
    {
        $key    = (string)($issue['key'] ?? '');
        $fields = (array)($issue['fields'] ?? []);
        $status = (array)($fields['status'] ?? []);

        return [
            'external_id'     => (string)($issue['id'] ?? ''),
            'external_key'    => $key,
            'external_url'    => $key !== '' ? $this->browseUrl($key) : null,
            'status_name'     => isset($status['name']) ? (string)$status['name'] : null,
            'status_category' => $this->mapStatusCategory((string)($status['statusCategory']['key'] ?? '')),
            'assignee_name'   => isset($fields['assignee']['displayName'])
                                    ? (string)$fields['assignee']['displayName'] : null,
            'summary'         => isset($fields['summary']) ? (string)$fields['summary'] : null,
        ];
    }

    /**
     * Jira's statusCategory key → our closed set.
     *
     * These three keys are fixed by Jira and are the same on every project on
     * every site, which is exactly why we key off them and not the status name.
     * Jira has no "cancelled" category — a Won't Do lands in `done` — so
     * STATUS_CANCELLED is simply unreachable here, and that is correct rather
     * than a gap.
      *
      * @param string $categoryKey  Jira's statusCategory key - 'new', 'indeterminate' or 'done'
     */
    protected function mapStatusCategory(string $categoryKey): ?string
    {
        switch ($categoryKey) {
            case 'new':           return self::STATUS_TODO;
            case 'indeterminate': return self::STATUS_IN_PROGRESS;
            case 'done':          return self::STATUS_DONE;
            default:              return null;   // unknown → leave it unset rather than guess
        }
    }

    /**
     * Reads one issue's summary, status and assignee and returns it parsed.
     *
     * @param string $externalId  the numeric Jira issue id (a key such as OPS-123 also works)
     */
    public function fetchIssue(string $externalId): array
    {
        $url = $this->apiUrl('issue/' . rawurlencode($externalId))
             . '?fields=summary,status,assignee';
        return $this->parseIssue($this->jiraRequest('GET', $url));
    }

    /**
     * Batch read via JQL — one call for the whole watch list instead of one per
     * issue, which matters because this is the poll cron's hot path.
     *
     * Chunked at 100: JQL has a practical length limit, and a site with hundreds
     * of linked issues would otherwise build a URL nothing will accept.
      *
      * @param array $externalIds  numeric Jira issue ids; anything else is dropped
     */
    public function fetchIssues(array $externalIds): array
    {
        $ids = array_values(array_filter(array_map('strval', $externalIds), function ($i) {
            return ctype_digit($i);   // Jira ids are numeric; anything else cannot be interpolated safely
        }));
        if (!$ids) return [];

        $out = [];
        $chunks = array_chunk($ids, 100);
        $failures = 0;
        $lastError = null;

        foreach ($chunks as $chunk) {
            $jql = 'id in (' . implode(',', $chunk) . ')';
            // ⚠️ Cloud REMOVED /rest/api/3/search — it is /search/jql now, with
            // token paging instead of startAt (Atlassian CHANGE-2046). Data
            // Center's v2 /search is unaffected, so the endpoint is flavour-aware
            // like everything else here. Found the hard way: the old path
            // returned "The requested API has been removed" against live Jira.
            $endpoint = $this->isCloud() ? 'search/jql' : 'search';
            $url = $this->apiUrl($endpoint) . '?' . http_build_query([
                'jql'        => $jql,
                'fields'     => 'summary,status,assignee',
                'maxResults' => count($chunk),
            ]);
            try {
                $res = $this->jiraRequest('GET', $url);
            } catch (Exception $e) {
                // One bad chunk must not lose the others — but see below.
                $failures++;
                $lastError = $e;
                continue;
            }
            foreach ((array)($res['issues'] ?? []) as $issue) {
                $parsed = $this->parseIssue((array)$issue);
                if ($parsed['external_id'] !== '') {
                    $out[$parsed['external_id']] = $parsed;
                }
            }
        }

        // ⚠️ If EVERY chunk failed this is not "one flaky page", it is a broken
        // connection — and returning [] would be indistinguishable from "none of
        // those issues exist". The poll would then cheerfully report "checked 12,
        // changed 0" while the tracker had been unreachable for a week. Surface it.
        if ($failures > 0 && $failures === count($chunks)) {
            throw ($lastError ?: new Exception('Could not read any issues from Jira.'));
        }
        return $out;
    }

    // ------------------------------------------------------------ comments

    /** How many comments to read per issue in one poll. */
    const COMMENT_PAGE_SIZE = 50;

    /**
     * Overlap added to every lookback window. The watermark is written after a
     * successful pull, but a comment posted DURING the pull would otherwise fall
     * in the gap. Re-asking for a few extra minutes is free because the UNIQUE
     * key on integration_comment_map makes a repeat import impossible — the
     * overlap is the belt, that key is the braces.
     */
    const COMMENT_OVERLAP_MINUTES = 5;

    /**
     * The furthest back a poll will ever look, however stale the watermark is.
     *
     * ⚠️ This is a product rule, not a performance one. A connection switched off
     * for a month must not, on being switched back on, tip a month of dev
     * conversation onto tickets that have long since been closed.
     */
    const COMMENT_LOOKBACK_CAP_MINUTES = 1440;   // 24 hours

    /**
     * Posts a comment on an issue, rendered for this Jira flavour, and returns the new comment's id.
     *
     * @param string $externalId  the numeric Jira issue id
     * @param IssueDoc $body  the comment, rendered for this flavour
     */
    public function addComment(string $externalId, IssueDoc $body): string
    {
        $res = $this->jiraRequest(
            'POST',
            $this->apiUrl('issue/' . rawurlencode($externalId) . '/comment'),
            ['body' => $this->renderDoc($body)]
        );
        return (string)($res['id'] ?? '');
    }

    /**
     * Comments on one issue, newest first, normalised.
     *
      * @param string $externalId  the numeric Jira issue id
     * @return array [['comment_id'=>…, 'comment_body'=>plain text,
     *                 'author_identity'=>…, 'author_name'=>…, 'created_ts'=>?int], …]
     */
    public function fetchComments(string $externalId): array
    {
        $url = $this->apiUrl('issue/' . rawurlencode($externalId) . '/comment')
             . '?' . http_build_query(['orderBy' => '-created', 'maxResults' => self::COMMENT_PAGE_SIZE]);

        $out = [];
        foreach ((array)($this->jiraRequest('GET', $url)['comments'] ?? []) as $c) {
            $parsed = $this->parseComment((array) $c);
            if ($parsed['comment_id'] !== '') $out[] = $parsed;
        }
        return $out;
    }

    /**
     * One Jira comment → the fields the canonical comment_added event needs.
     *
     * ⚠️ author_identity must be the SAME kind of value testConnection() returns,
     * because echo suppression compares the two. Cloud identifies a user by
     * accountId and Data Center by username, so this is flavour-aware for exactly
     * the reason testConnection() is.
      *
      * @param array $c  one comment as the Jira REST API returns it - id, body, author, created
     */
    protected function parseComment(array $c): array
    {
        $author  = (array)($c['author'] ?? []);
        $created = (string)($c['created'] ?? '');

        return [
            'comment_id'      => (string)($c['id'] ?? ''),
            'comment_body'    => $this->commentBodyToText($c['body'] ?? null),
            'author_identity' => $this->isCloud()
                                    ? (string)($author['accountId'] ?? '')
                                    : (string)($author['name'] ?? $author['key'] ?? ''),
            'author_name'     => (string)($author['displayName'] ?? ''),
            'created_ts'      => $created !== '' ? (strtotime($created) ?: null) : null,
        ];
    }

    /**
     * A comment body in whichever shape this flavour sends → plain text.
     *
     * Cloud sends ADF (nested JSON); Data Center sends wiki markup (a string).
     * Same split as renderDoc(), in the other direction.
      *
      * @param mixed $body  the comment body - an ADF array on Cloud, a wiki markup string on Data Center
     */
    protected function commentBodyToText($body): string
    {
        if (is_array($body))  return $this->tidyText($this->adfToText($body));
        if (is_string($body)) return $this->tidyText($body);
        return '';
    }

    /**
     * ADF → plain text.
     *
     * This lives in the connector rather than in IssueDoc on purpose: ADF is
     * Atlassian's format and nobody else's, whereas IssueDoc is the shared
     * document every provider renders FROM. Putting an ADF reader in IssueDoc
     * would make core carry one tracker's vocabulary.
     *
     * Deliberately lossy. The job is "an analyst can read what the dev said",
     * not a faithful round-trip — a note is plain text, and the issue itself is
     * one click away on the pill.
      *
      * @param mixed $node  an ADF node (array); anything else gives ''
     */
    protected function adfToText($node): string
    {
        if (!is_array($node)) return '';

        $type     = (string)($node['type'] ?? '');
        $children = function (string $glue = '') use ($node) {
            $parts = [];
            foreach ((array)($node['content'] ?? []) as $child) {
                $parts[] = $this->adfToText($child);
            }
            return implode($glue, $parts);
        };

        switch ($type) {
            case 'text':
                $text = (string)($node['text'] ?? '');
                // A link's href is the useful half — "click here" alone is no use
                // in a plain-text note.
                foreach ((array)($node['marks'] ?? []) as $mark) {
                    if (($mark['type'] ?? '') === 'link') {
                        $href = (string)($mark['attrs']['href'] ?? '');
                        if ($href !== '' && $href !== $text) $text .= ' (' . $href . ')';
                    }
                }
                return $text;

            case 'hardBreak':  return "\n";
            case 'rule':       return "\n---\n";
            // Both carry their label in attrs rather than in a text child, so
            // recursing would silently drop them.
            case 'mention':    return (string)($node['attrs']['text'] ?? '');
            case 'emoji':      return (string)($node['attrs']['text'] ?? $node['attrs']['shortName'] ?? '');
            case 'inlineCard': return (string)($node['attrs']['url'] ?? '');

            case 'paragraph':
            case 'heading':
            case 'codeBlock':
            case 'blockquote':
                return $children() . "\n\n";

            case 'listItem':
                return '- ' . trim($children()) . "\n";

            default:
                return $children();
        }
    }

    /**
     * Shared tidy-up for text pulled out of a tracker.
     *
     * @param string $s  text pulled out of a comment
     */
    protected function tidyText(string $s): string
    {
        $s = str_replace(["\r\n", "\r", "\xC2\xA0"], ["\n", "\n", ' '], $s);
        $s = preg_replace('/[ \t]+/u', ' ', $s);
        $s = preg_replace('/ *\n */u', "\n", $s);
        $s = preg_replace('/\n{3,}/u', "\n\n", $s);
        $s = trim($s);
        if (mb_strlen($s) > 8000) {
            $s = mb_substr($s, 0, 8000) . "\n\n… (truncated — see the issue for the rest)";
        }
        return $s;
    }

    // ---------------------------------------------------------------- polling

    /**
     * The inbound path until webhooks land: ask Jira what has been commented on
     * since $since and return canonical `comment_added` events.
     *
     * $since is a UTC 'Y-m-d H:i:s' watermark written by the service after a
     * successful pull. **null means this connection has never been polled**, and
     * that case deliberately returns NOTHING: turning comment sync on must not
     * tip a tracker's entire comment history onto tickets, some of which closed
     * months ago. The first run only establishes the watermark; the second run
     * onwards is when comments start arriving.
     *
     * Two calls, not one per issue: a JQL search narrows the watch list to the
     * issues that actually changed, and only those are read for comments. On a
     * quiet day that is one search and no comment reads at all.
      *
      * @param ?string $since  UTC 'Y-m-d H:i:s' watermark (last_poll_watermark); null on the first run returns nothing
      * @param array $externalIds  numeric ids of the issues this connection holds links to; anything else is dropped
     */
    public function pollChanges(?string $since, array $externalIds = []): array
    {
        if ($since === null) return [];

        $ids = array_values(array_filter(array_map('strval', $externalIds), 'ctype_digit'));
        if (!$ids) return [];

        $sinceTs = strtotime($since . ' UTC');
        if (!$sinceTs) return [];

        $now     = time();
        $minutes = $this->lookbackMinutes($sinceTs, $now);
        // Keep the client-side filter and the server-side window in step. When
        // the cap above bites, the watermark is older than what we asked Jira
        // for, and filtering on it would import comments the search never
        // reliably covered.
        $cutoff  = max($sinceTs, $now - ($minutes * 60));

        $events = [];
        foreach ($this->searchUpdatedSince($ids, $minutes) as $externalId) {
            try {
                $comments = $this->fetchComments($externalId);
            } catch (Exception) {
                // One issue we cannot read must not lose the others' comments.
                continue;
            }
            foreach ($comments as $c) {
                if ($c['created_ts'] !== null && $c['created_ts'] <= $cutoff) continue;
                if ($c['comment_body'] === '') continue;
                $events[] = [
                    'event_id'        => 'jira-comment-' . $c['comment_id'],
                    'type'            => 'comment_added',
                    'external_id'     => $externalId,
                    'comment_id'      => $c['comment_id'],
                    'comment_body'    => $c['comment_body'],
                    'author_identity' => $c['author_identity'],
                    'author_name'     => $c['author_name'],
                    'occurred_at'     => $c['created_ts'],
                ];
            }
        }
        return $events;
    }

    /**
     * How many minutes back to ask Jira about. Pure, so the capping rule is
     * provable without a clock or a network.
      *
      * @param int $sinceTs  the watermark as a Unix timestamp
      * @param ?int $nowTs  the current time as a Unix timestamp; null means time()
     */
    protected function lookbackMinutes(int $sinceTs, ?int $nowTs = null): int
    {
        $now  = $nowTs ?? time();
        $mins = (int) ceil(max(0, $now - $sinceTs) / 60) + self::COMMENT_OVERLAP_MINUTES;
        return max(self::COMMENT_OVERLAP_MINUTES, min($mins, self::COMMENT_LOOKBACK_CAP_MINUTES));
    }

    /**
     * Which of our watched issues changed in the last $minutes.
     *
     * ⚠️ Uses JQL's RELATIVE date syntax (`updated >= -90m`) rather than an
     * absolute timestamp. An absolute JQL date is interpreted in the *Jira
     * user's* timezone, not UTC, so a server in one timezone and a Jira account
     * in another would silently miss or re-read a window's worth of comments.
     * Relative minutes have no timezone at all.
      *
      * @param array $ids  numeric Jira issue ids to check
      * @param int $minutes  how far back to look, from lookbackMinutes()
     */
    protected function searchUpdatedSince(array $ids, int $minutes): array
    {
        $out = [];
        foreach (array_chunk($ids, 100) as $chunk) {
            $jql = 'id in (' . implode(',', $chunk) . ') AND updated >= -' . (int) $minutes . 'm';
            // Flavour-aware for the same reason fetchIssues() is: Cloud removed
            // /rest/api/3/search in favour of /search/jql.
            $url = $this->apiUrl($this->isCloud() ? 'search/jql' : 'search') . '?' . http_build_query([
                'jql'        => $jql,
                'fields'     => 'id',
                'maxResults' => count($chunk),
            ]);
            try {
                $res = $this->jiraRequest('GET', $url);
            } catch (Exception) {
                continue;   // a bad chunk loses its own issues, never the others'
            }
            foreach ((array)($res['issues'] ?? []) as $issue) {
                $id = (string)($issue['id'] ?? '');
                if ($id !== '') $out[] = $id;
            }
        }
        return $out;
    }

    // ---------------------------------------------------------- attachments

    /**
     * Upload one file to an issue.
     *
     * Jira needs three things that a normal JSON call does not:
     *
     *  1. **`X-Atlassian-Token: no-check`** — without it Jira rejects the upload
     *     as a suspected cross-site request, with a 403 and no useful body. It
     *     is the single most common reason a Jira upload "just fails".
     *  2. **`multipart/form-data` with the field named `file`** — not `files`,
     *     not the filename.
     *  3. **No `Accept: application/json` weirdness** — it still returns JSON,
     *     but the request itself must carry the multipart content type with its
     *     boundary, which is why the body is built here rather than handed to
     *     cURL as an array.
     *
      * @param string $externalId  the numeric id of the issue to attach the file to
     * @param array $file ['data'=>binary, 'filename'=>string, 'content_type'=>string]
     */
    public function addAttachment(string $externalId, array $file): void
    {
        $body = $this->buildMultipart(
            (string)($file['filename'] ?? 'attachment'),
            (string)($file['content_type'] ?? 'application/octet-stream'),
            (string)($file['data'] ?? ''),
            $boundary
        );

        $creds   = $this->connection['credentials'] ?? [];
        $headers = [
            'Accept: application/json',
            'X-Atlassian-Token: no-check',
            'Content-Type: multipart/form-data; boundary=' . $boundary,
        ];
        $opts = [
            'method'  => 'POST',
            'body'    => $body,
            'timeout' => 120,   // a few MB over a slow link outlasts the default
        ];
        if ($this->isCloud()) {
            $opts['auth'] = ($creds['email'] ?? '') . ':' . ($creds['api_token'] ?? '');
        } else {
            $headers[] = 'Authorization: Bearer ' . ($creds['api_token'] ?? '');
        }
        $opts['headers'] = $headers;

        list($code, $raw) = $this->httpRequest(
            $this->apiUrl('issue/' . rawurlencode($externalId) . '/attachments'), $opts
        );
        if ($code < 200 || $code >= 300) {
            throw new Exception($this->extractError($code, $raw));
        }
    }

    /**
     * A single-file multipart/form-data body, and the boundary that goes with it.
     *
     * ⚠️ The boundary must not occur inside the file's bytes or the body is
     * truncated at that point — hence a random one, not a fixed string.
      *
      * @param string $filename  the file's original name; path parts, quotes and line breaks are stripped
      * @param string $contentType  the file's MIME type
      * @param string $data  the file's bytes
      * @param ?string &$boundary  set to the random boundary used - the caller puts it in the Content-Type header
     */
    protected function buildMultipart(string $filename, string $contentType, string $data, ?string &$boundary): string
    {
        $boundary = '----FreeITSM' . bin2hex(random_bytes(16));

        // Strip anything path-like: Jira takes the filename verbatim, and a
        // name containing quotes or CRLF would break the header it sits in.
        $safe = str_replace(['"', "\r", "\n"], '', basename($filename));
        if ($safe === '') $safe = 'attachment';

        return "--{$boundary}\r\n"
             . "Content-Disposition: form-data; name=\"file\"; filename=\"{$safe}\"\r\n"
             . "Content-Type: {$contentType}\r\n\r\n"
             . $data . "\r\n"
             . "--{$boundary}--\r\n";
    }

    // ------------------------------------------------------------- discovery

    /**
     * Confirm the credentials and — just as importantly — capture who we are.
     *
     * account_identity is half of echo suppression: an inbound event authored by
     * this identity is our own write coming back and must be dropped, not
     * re-imported. Cloud identifies users by accountId, Data Center by username,
     * and those are the values that appear as the author in each flavour's
     * webhook payloads.
     */
    public function testConnection(): array
    {
        // Sniff the flavour: v3 /myself exists only on Cloud. Try the heuristic's
        // choice first, then the other, so a vanity domain in front of Cloud (or a
        // wrong guess either way) still resolves rather than reporting bad creds.
        $order = $this->isCloud()
            ? [self::FLAVOUR_CLOUD, self::FLAVOUR_SERVER]
            : [self::FLAVOUR_SERVER, self::FLAVOUR_CLOUD];

        $lastError = null;
        foreach ($order as $flavour) {
            $this->connection['credentials']['flavour'] = $flavour;
            try {
                $me = $this->jiraRequest('GET', $this->apiUrl('myself'));
            } catch (Exception $e) {
                $lastError = $e;
                continue;
            }

            $identity = $flavour === self::FLAVOUR_CLOUD
                ? (string)($me['accountId'] ?? '')
                : (string)($me['name'] ?? $me['key'] ?? '');
            $display  = (string)($me['displayName'] ?? $me['emailAddress'] ?? $identity);

            if ($identity === '') {
                $lastError = new Exception('Jira did not identify the account this token belongs to.');
                continue;
            }

            return [
                'detail'           => 'Connected to Jira ' . ($flavour === self::FLAVOUR_CLOUD ? 'Cloud' : 'Data Center')
                                    . ' as ' . $display,
                'account_identity' => $identity,
                'flavour'          => $flavour,
            ];
        }
        throw ($lastError ?: new Exception('Could not reach Jira.'));
    }

    /**
     * Lists the Jira projects for the mapping dropdowns - paged on Cloud (up to 1,000), one
     * call on Server / Data Center.
     *
     * @return array [['key'=>'OPS','name'=>'Operations'], …]
     */
    public function listProjects(): array
    {
        $out = [];
        if ($this->isCloud()) {
            // Paged. Walk it rather than taking the first 50 — an MSP's Jira can
            // easily have more, and a silently truncated list looks like a bug
            // ("my project isn't in the dropdown").
            $start = 0;
            do {
                $res = $this->jiraRequest('GET', $this->apiUrl('project/search')
                    . '?' . http_build_query(['startAt' => $start, 'maxResults' => 50]));
                foreach ((array)($res['values'] ?? []) as $p) {
                    $out[] = ['key' => (string)($p['key'] ?? ''), 'name' => (string)($p['name'] ?? '')];
                }
                $start += 50;
                $isLast = !empty($res['isLast']) || empty($res['values']);
            } while (!$isLast && $start < 1000);
        } else {
            foreach ((array)$this->jiraRequest('GET', $this->apiUrl('project')) as $p) {
                $out[] = ['key' => (string)($p['key'] ?? ''), 'name' => (string)($p['name'] ?? '')];
            }
        }
        return $out;
    }

    /**
     * Every priority defined on the site, for the mapping screen's dropdown.
     *
     * ⚠️ Site-wide, but Jira applies priorities **per project** through priority
     * schemes — so a name listed here can still be rejected by a particular
     * project. That is exactly why a rejected priority falls back to creating the
     * issue without one rather than failing the escalation
     * (`integrationsLooksLikePriorityRejection()`).
     *
     * Cloud paginates this (`/priority/search`); Data Center returns a plain
     * array from `/priority`. Same split as listProjects(), and pinned by tests
     * because Atlassian has form for retiring the unpaged version — see §6.
     *
     * @return array [['id'=>'3','name'=>'Medium'], …]
     */
    public function listPriorities(): array
    {
        $out = [];
        if ($this->isCloud()) {
            $start = 0;
            do {
                $res = $this->jiraRequest('GET', $this->apiUrl('priority/search')
                    . '?' . http_build_query(['startAt' => $start, 'maxResults' => 50]));
                foreach ((array)($res['values'] ?? []) as $p) {
                    $out[] = ['id' => (string)($p['id'] ?? ''), 'name' => (string)($p['name'] ?? '')];
                }
                $start += 50;
                $isLast = !empty($res['isLast']) || empty($res['values']);
            } while (!$isLast && $start < 500);
        } else {
            foreach ((array)$this->jiraRequest('GET', $this->apiUrl('priority')) as $p) {
                $out[] = ['id' => (string)($p['id'] ?? ''), 'name' => (string)($p['name'] ?? '')];
            }
        }
        return $out;
    }

    /**
     * Issue types available on a project.
     *
     * ⚠️ Which types a project offers depends on its issue-type scheme, so this
     * cannot be a hardcoded Bug/Task/Story list — that is exactly how you end up
     * 400ing on the one project that renamed everything.
      *
      * @param string $project  the project key, e.g. 'OPS'
     */
    public function listIssueTypes(string $project): array
    {
        $out = [];
        if ($this->isCloud()) {
            $res = $this->jiraRequest('GET',
                $this->apiUrl('issue/createmeta/' . rawurlencode($project) . '/issuetypes'));
            foreach ((array)($res['values'] ?? $res['issueTypes'] ?? []) as $t) {
                if (!empty($t['subtask'])) continue;   // subtasks need a parent; not a valid escalation target
                $out[] = ['id' => (string)($t['id'] ?? ''), 'name' => (string)($t['name'] ?? '')];
            }
        } else {
            $res = $this->jiraRequest('GET', $this->apiUrl('issue/createmeta')
                . '?' . http_build_query(['projectKeys' => $project, 'expand' => 'projects.issuetypes']));
            foreach ((array)($res['projects'] ?? []) as $p) {
                foreach ((array)($p['issuetypes'] ?? []) as $t) {
                    if (!empty($t['subtask'])) continue;
                    $out[] = ['id' => (string)($t['id'] ?? ''), 'name' => (string)($t['name'] ?? '')];
                }
            }
        }
        return $out;
    }
}
