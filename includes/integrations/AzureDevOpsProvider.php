<?php
/**
 * AzureDevOpsProvider — Azure DevOps Services (cloud) and Server (on-prem).
 *
 * ── Why this connector is shaped differently from Jira ───────────────────────
 *
 * Jira and Azure DevOps look alike from the outside and are genuinely different
 * underneath. The four differences that drive this file:
 *
 *   1. WRITES ARE JSON PATCH. Everything — create and update — is a
 *      `[{"op":"add","path":"/fields/System.Title","value":…}]` document sent as
 *      `application/json-patch+json`. There is no "post a fields object" call.
 *   2. TYPES AND STATES ARE PER-PROCESS, not per-project or global. Two projects
 *      in the SAME organisation can disagree about which work item types exist
 *      and what their states are called.
 *   3. BODIES ARE HTML, and which FIELD holds the body depends on the work item
 *      type — see bodyFieldFor().
 *   4. QUERYING IS TWO-STEP. WIQL returns ids only; the fields need a second
 *      batch read.
 *
 * ── ⚠️ Five state categories, not four ──────────────────────────────────────
 *
 * Azure DevOps has Proposed / InProgress / **Resolved** / Completed / Removed.
 * We have four. `Resolved` — "the developer says it is fixed, but nobody has
 * verified it" — is the one that does not map cleanly, so it is a per-connection
 * setting rather than a decision baked in here. See mapStateCategory().
 *
 * ⚠️ Never branch on a state NAME. Azure DevOps proves why better than Jira did:
 * in the stock Agile process the state called **"Resolved" is category Resolved
 * on a Bug and category InProgress on a User Story** — the same word meaning
 * different things on two types in the same project.
 *
 * ── Testability ─────────────────────────────────────────────────────────────
 *
 * As with JiraProvider, payload building and response parsing are separate
 * protected methods so the interesting logic is provable without a live
 * organisation. tests/integrations stubs httpRequest().
 */

require_once __DIR__ . '/IssueTrackerProvider.php';
require_once __DIR__ . '/IssueDoc.php';

class AzureDevOpsProvider extends IssueTrackerProvider
{
    /**
     * ⚠️ The api-version is NOT uniform across this API, and getting it wrong
     * returns a clear error rather than wrong data — which is the only reason
     * these are constants and not a single value.
     */
    const API_VERSION         = '7.0';
    const API_VERSION_COMMENT = '7.0-preview.3';   // comments are still preview
    const API_VERSION_CONNDATA = '7.0-preview';    // connectionData rejects a bare 7.0

    /** What `Resolved` should mean to us, when a connection has not said. */
    const RESOLVED_DEFAULT = self::STATUS_IN_PROGRESS;

    /** WIQL cannot express an unbounded IN list; chunk the watch list. */
    const WIQL_CHUNK = 100;

    /** Overlap and cap for the poll window, mirroring JiraProvider's reasoning. */
    const COMMENT_OVERLAP_MINUTES    = 5;
    const COMMENT_LOOKBACK_CAP_HOURS = 24;

    /** The optional features Azure DevOps supports - everything except a priority list. */
    protected function capabilities(): array
    {
        return [
            self::CAP_ISSUE_TYPES,
            self::CAP_ATTACHMENTS,
            self::CAP_WEBHOOKS,
            self::CAP_POLLING,
            self::CAP_CUSTOM_FIELDS,
            // ⚠️ NOT CAP_PRIORITIES. Azure DevOps priority is an integer 1-4 on
            // Microsoft.VSTS.Common.Priority, not a named list you can enumerate,
            // so there is nothing to offer in a "map our priority to theirs"
            // dropdown. Declaring it would give the mapping screen an empty
            // select and no explanation.
        ];
    }

    // --------------------------------------------------------------- urls

    /** The organisation (or on-prem collection) root, no trailing slash. */
    protected function baseUrl(): string
    {
        return rtrim((string)($this->connection['base_url'] ?? ''), '/');
    }

    /**
     * An organisation-scoped API URL: `{base}/_apis/{path}`.
     *
     * Work item reads are deliberately org-scoped rather than project-scoped —
     * a work item id is unique across the organisation, and scoping the read to
     * a project would mean knowing which project it lives in before we can look
     * it up, which the link row does not always carry.
      *
      * @param string $path  the route under `_apis/`, e.g. 'wit/workitems/42'; a leading slash is dropped
      * @param array $query  query-string parameters; api-version is added and wins over any given
      * @param string $version  the api-version to send - one of the API_VERSION* constants
     */
    protected function apiUrl(string $path, array $query = [], string $version = self::API_VERSION): string
    {
        $query['api-version'] = $version;
        return $this->baseUrl() . '/_apis/' . ltrim($path, '/') . '?' . http_build_query($query);
    }

    /**
     * A project-scoped API URL: `{base}/{project}/_apis/{path}`.
     *
     * @param string $project  the project name (or GUID); URL-encoded here
     * @param string $path  the route under the project's `_apis/`; a leading slash is dropped
     * @param array $query  query-string parameters; api-version is added and wins over any given
     * @param string $version  the api-version to send - one of the API_VERSION* constants
     */
    protected function projectUrl(string $project, string $path, array $query = [], string $version = self::API_VERSION): string
    {
        $query['api-version'] = $version;
        return $this->baseUrl() . '/' . rawurlencode($project) . '/_apis/' . ltrim($path, '/')
             . '?' . http_build_query($query);
    }

    /**
     * The human-facing link stored on the link row and shown in the panel.
     *
     * @param string $project  the name of the project the work item lives in
     * @param string $id  the numeric work item id
     */
    public function browseUrl(string $project, string $id): string
    {
        return $this->baseUrl() . '/' . rawurlencode($project) . '/_workitems/edit/' . rawurlencode($id);
    }

    // ------------------------------------------------------------ requests

    /**
     * Auth is a Personal Access Token as HTTP Basic with an EMPTY username —
     * `Authorization: Basic base64(":" + PAT)`. Azure DevOps ignores the
     * username entirely; sending the user's email in it also works, which is
     * why a wrong implementation can appear to succeed on a developer's own
     * account and fail on a service account.
      *
      * @param string $method  the HTTP method - 'GET', 'POST' or 'PATCH'
      * @param string $url  the full URL, from apiUrl() or projectUrl()
      * @param ?array $json  the body to JSON-encode; null sends no body
      * @param ?string $contentType  the body's Content-Type; null means application/json
     */
    protected function devopsRequest(string $method, string $url, ?array $json = null, ?string $contentType = null): array
    {
        $creds   = $this->connection['credentials'] ?? [];
        $headers = ['Accept: application/json'];
        $opts    = [
            'method' => $method,
            'auth'   => ':' . ($creds['api_token'] ?? ''),
        ];

        if ($json !== null) {
            $headers[]    = 'Content-Type: ' . ($contentType ?: 'application/json');
            $opts['body'] = json_encode($json);
        }
        $opts['headers'] = $headers;

        list($code, $body) = $this->httpRequest($url, $opts);

        if ($code < 200 || $code >= 300) {
            throw new Exception($this->extractError($code, $body));
        }
        $decoded = json_decode($body, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * A JSON Patch write. Separated because the content type is the whole trick.
     *
     * @param string $method  'POST' to create a work item, 'PATCH' to change one
     * @param string $url  the full URL, from apiUrl() or projectUrl()
     * @param array $ops  the JSON Patch operations, e.g. built by addOp()
     */
    protected function patchRequest(string $method, string $url, array $ops): array
    {
        return $this->devopsRequest($method, $url, $ops, 'application/json-patch+json');
    }

    /**
     * An Azure DevOps error into something an analyst can act on.
     *
     * ⚠️ A 203 is NOT a success here. When a PAT is invalid or expired, Azure
     * DevOps often answers 203 with an HTML sign-in page rather than 401 with
     * JSON — so "the connection test passed but nothing works" is the symptom of
     * treating any 2xx as fine. This is why devopsRequest() accepts 200-299 but
     * testConnection() insists on a parsed identity.
      *
      * @param int $code  the HTTP status code
      * @param string $rawBody  the response body as received - JSON, or an HTML sign-in page
     */
    protected function extractError(int $code, string $rawBody): string
    {
        $d = json_decode($rawBody, true);

        if (is_array($d) && isset($d['message']) && is_string($d['message'])) {
            return 'Azure DevOps: ' . $d['message'];
        }
        if ($code === 203 || stripos($rawBody, '<html') !== false) {
            return 'Azure DevOps rejected the token (it returned a sign-in page). '
                 . 'Check the personal access token has not expired and has Work Items → Read, write & manage.';
        }
        return 'Azure DevOps returned HTTP ' . $code . '.';
    }

    // ---------------------------------------------------------- rendering

    /**
     * Azure DevOps fields are HTML — both the description and comment bodies.
     * `System.Description` is rendered as rich text on every work item form.
      *
      * @param IssueDoc $doc  the description or comment to render as HTML
     */
    public function renderDoc(IssueDoc $doc)
    {
        return $doc->toHtml();
    }

    // ------------------------------------------------------------- create

    /**
     * Which field holds the body for this work item type.
     *
     * ⚠️ This is not a detail. In the stock **Agile** process a Bug's form shows
     * *Repro Steps* (`Microsoft.VSTS.TCM.ReproSteps`) and does NOT show
     * `System.Description`. Writing the description to System.Description on a
     * Bug therefore succeeds, returns 200, and produces a work item that looks
     * EMPTY to the developer who opens it. Nothing errors; the text is simply
     * somewhere the form does not display.
     *
     * So the type's own field list decides, and the answer is cached per
     * (project, type) because it cannot change without an admin editing the
     * process.
      *
      * @param string $project  the project name
      * @param string $type  the work item type name, e.g. 'Bug'
     */
    protected function bodyFieldFor(string $project, string $type): string
    {
        static $cache = [];
        $key = $project . '|' . $type;
        if (isset($cache[$key])) return $cache[$key];

        $fallback = 'System.Description';
        try {
            $t = $this->devopsRequest('GET', $this->projectUrl($project, 'wit/workitemtypes/' . rawurlencode($type)));
        } catch (Exception) {
            return $cache[$key] = $fallback;   // a create with a plausible field beats no create
        }

        $has = [];
        foreach ((array)($t['fields'] ?? []) as $f) {
            $has[(string)($f['referenceName'] ?? '')] = true;
        }
        // Repro Steps wins when present: on a Bug it is the field a developer reads.
        $cache[$key] = isset($has['Microsoft.VSTS.TCM.ReproSteps'])
            ? 'Microsoft.VSTS.TCM.ReproSteps'
            : $fallback;

        return $cache[$key];
    }

    /**
     * Build the JSON Patch document for a create.
     *
     * Separate and protected so the patch shape is provable without a live org —
     * this is the single most provider-specific thing in the file.
      *
      * @param array $target  array{project:string, work_item_type?:string, issue_type?:string, area_path?:string,
      *                        iteration_path?:string} - the escalation target
      * @param string $summary  the title; trimTitle() collapses whitespace and caps it at 255 characters
      * @param IssueDoc $body  the description, written to the type's body field (see bodyFieldFor())
      * @param array $fields  mapped fields, field reference name => value; keys that are not strings are skipped
     */
    protected function buildCreatePatch(array $target, string $summary, IssueDoc $body, array $fields = []): array
    {
        $project = (string)($target['project'] ?? '');
        $type    = $this->targetType($target);

        $ops = [
            $this->addOp('System.Title', $this->trimTitle($summary)),
            $this->addOp($this->bodyFieldFor($project, $type), $this->renderDoc($body)),
        ];

        // Area and iteration path are Azure DevOps' own routing. They are part of
        // the TARGET rather than mapped fields, because they decide which team
        // sees the item at all — the same job the project key does on Jira.
        foreach (['area_path' => 'System.AreaPath', 'iteration_path' => 'System.IterationPath'] as $k => $field) {
            $v = trim((string)($target[$k] ?? ''));
            if ($v !== '') $ops[] = $this->addOp($field, $v);
        }

        // Mapped fields (V3) arrive already keyed by their reference name, because
        // only an admin who chose the mapping knows what "Severity" is called here.
        foreach ($fields as $refName => $value) {
            if (!is_string($refName) || $refName === '') continue;
            $ops[] = $this->addOp($refName, $value);
        }

        return $ops;
    }

    /**
     * Which work item type to create.
     *
     * ⚠️ TWO KEYS, and this is not belt-and-braces. The core is tracker-neutral
     * and speaks of an `issue_type` — that is what escalate_ticket.php and the
     * workflow action both send, because "issue type" is the word the mapping
     * screen uses for every provider. `work_item_type` is Azure DevOps' own name
     * for the same thing, and is what the contract's docblock shows for this
     * provider.
     *
     * Reading only one of them is a bug that CANNOT be seen: an escalation
     * requesting a Bug quietly produced a Task, with no error anywhere, because
     * the default silently applied. That is exactly what happened on the first
     * live run. Accept both, and default only when neither was given.
      *
      * @param array $target  the escalation target; its work_item_type, else issue_type, is read
     */
    protected function targetType(array $target): string
    {
        foreach (['work_item_type', 'issue_type'] as $key) {
            $v = trim((string)($target[$key] ?? ''));
            if ($v !== '') return $v;
        }
        // Task exists in every stock process — Basic, Agile, Scrum and CMMI —
        // which Bug does not (Basic has no Bug at all).
        return 'Task';
    }

    /**
     * One JSON Patch add operation against a field.
     *
     * @param string $refName  the field reference name, e.g. 'System.Title'
     * @param mixed $value  the value to set
     */
    protected function addOp(string $refName, $value): array
    {
        return ['op' => 'add', 'path' => '/fields/' . $refName, 'value' => $value];
    }

    /**
     * ⚠️ System.Title is capped at 255 characters and Azure DevOps rejects the
     * whole create if it is longer — so a ticket with a very long subject would
     * fail to escalate at all rather than arrive with a shortened title.
      *
      * @param string $summary  the ticket subject
     */
    protected function trimTitle(string $summary): string
    {
        $s = trim(preg_replace('/\s+/u', ' ', $summary));
        if ($s === '') $s = 'Untitled';
        return mb_strlen($s) > 255 ? (mb_substr($s, 0, 252) . '...') : $s;
    }

    /**
     * Creates a work item of the target's type in the target project and returns it parsed.
     *
     * Throws when no project is given or Azure DevOps returns no id.
      *
      * @param array $target  the escalation target, shaped as for buildCreatePatch() - project is required
      * @param string $summary  the ticket subject, used as the title
      * @param IssueDoc $body  the description
      * @param array $fields  mapped fields, field reference name => value
     */
    public function createIssue(array $target, string $summary, IssueDoc $body, array $fields = []): array
    {
        $project = (string)($target['project'] ?? '');
        $type    = $this->targetType($target);
        if ($project === '') {
            throw new Exception('No Azure DevOps project was given for this escalation.');
        }

        // ⚠️ The `$` before the type is part of the route, not a typo:
        // POST /{project}/_apis/wit/workitems/$Bug
        $url = $this->projectUrl($project, 'wit/workitems/$' . rawurlencode($type));

        $created = $this->patchRequest('POST', $url, $this->buildCreatePatch($target, $summary, $body, $fields));

        $id = (string)($created['id'] ?? '');
        if ($id === '') {
            throw new Exception('Azure DevOps accepted the work item but returned no id.');
        }
        // Unlike Jira, create DOES return the fields, so the panel is correct
        // immediately without a second read.
        return $this->parseWorkItem($created);
    }

    // --------------------------------------------------------------- read

    /**
     * Azure DevOps' work item JSON → our normalised shape.
     *
     * ⚠️ `external_key` is the plain numeric id. Azure DevOps has no
     * human-readable key like Jira's OPS-123 — the id IS what people quote.
      *
      * @param array $wi  one work item as Azure DevOps returns it - id plus fields keyed by reference name
     */
    protected function parseWorkItem(array $wi): array
    {
        $f       = (array)($wi['fields'] ?? []);
        $id      = (string)($wi['id'] ?? '');
        $project = (string)($f['System.TeamProject'] ?? '');
        $type    = (string)($f['System.WorkItemType'] ?? '');
        $state   = (string)($f['System.State'] ?? '');

        return [
            'external_id'     => $id,
            'external_key'    => $id,
            'external_url'    => ($project !== '' && $id !== '') ? $this->browseUrl($project, $id) : null,
            'status_name'     => $state !== '' ? $state : null,
            'status_category' => $state !== '' ? $this->stateCategory($project, $type, $state) : null,
            'assignee_name'   => isset($f['System.AssignedTo']['displayName'])
                                    ? (string)$f['System.AssignedTo']['displayName'] : null,
            'summary'         => isset($f['System.Title']) ? (string)$f['System.Title'] : null,
        ];
    }

    /**
     * A state NAME → its category, for this project's process and this type.
     *
     * ⚠️ Both the project and the type are required, and neither is padding.
     * States are defined per work item type within a process, and two types in
     * one project genuinely disagree: in the stock Agile process "Resolved" is
     * category `Resolved` on a Bug and `InProgress` on a User Story.
     *
     * Cached per (project, type) — a process only changes when an admin edits it.
      *
      * @param string $project  the project name
      * @param string $type  the work item type name, e.g. 'Bug'
      * @param string $state  the state name, e.g. 'Resolved'; matched without regard to case
     */
    protected function stateCategory(string $project, string $type, string $state): ?string
    {
        static $cache = [];
        $key = $project . '|' . $type;

        if (!isset($cache[$key])) {
            $map = [];
            try {
                $t = $this->devopsRequest('GET',
                    $this->projectUrl($project, 'wit/workitemtypes/' . rawurlencode($type)));
                foreach ((array)($t['states'] ?? []) as $s) {
                    $name = (string)($s['name'] ?? '');
                    if ($name !== '') $map[mb_strtolower($name)] = (string)($s['category'] ?? '');
                }
            } catch (Exception) {
                // Leave the map empty: an unknown category is null, which the
                // caller renders as "no status yet" rather than a wrong one.
            }
            $cache[$key] = $map;
        }

        $category = $cache[$key][mb_strtolower($state)] ?? '';
        return $category !== '' ? $this->mapStateCategory($category) : null;
    }

    /**
     * Azure DevOps' five state categories → our four.
     *
     * | Azure DevOps | ours |
     * |--------------|------|
     * | Proposed     | todo |
     * | InProgress   | in_progress |
     * | Resolved     | **the connection decides** |
     * | Completed    | done |
     * | Removed      | cancelled |
     *
     * 🔑 `Resolved` means "a developer says it is fixed, nobody has verified it".
     * Whether that is *done* from a service desk's point of view is a genuine
     * judgement, not a technical fact:
     *
     *   - treat it as **in progress** and the requester is told nothing until
     *     someone verifies — cautious, and the default, because telling somebody
     *     their problem is fixed when it is not is the worse failure;
     *   - treat it as **done** and the requester hears as soon as the developer
     *     marks it resolved — faster, right for teams who close on resolve.
     *
     * Jira has no equivalent: its "Won't Do" lands in `done` and there is no
     * fifth category, which is why this setting exists only here.
      *
      * @param string $category  the Azure DevOps state category - Proposed, InProgress, Resolved, Completed or Removed
     */
    protected function mapStateCategory(string $category): ?string
    {
        switch ($category) {
            case 'Proposed':   return self::STATUS_TODO;
            case 'InProgress': return self::STATUS_IN_PROGRESS;
            case 'Completed':  return self::STATUS_DONE;
            case 'Removed':    return self::STATUS_CANCELLED;
            case 'Resolved':   return $this->resolvedMeans();
            default:           return null;   // unknown → unset rather than guess
        }
    }

    /** The connection's answer for `Resolved`, validated against our closed set. */
    public function resolvedMeans(): string
    {
        $v = (string)($this->connection['credentials']['resolved_means'] ?? '');
        return in_array($v, [self::STATUS_IN_PROGRESS, self::STATUS_DONE], true)
            ? $v
            : self::RESOLVED_DEFAULT;
    }

    /** The fields worth asking for. Narrower than the default, which returns everything. */
    protected function readFields(): string
    {
        return 'System.Title,System.State,System.WorkItemType,System.AssignedTo,System.TeamProject';
    }

    /**
     * Reads one work item by its numeric id and returns it parsed for the escalation panel.
     *
     * @param string $externalId  the numeric work item id; anything else throws
     */
    public function fetchIssue(string $externalId): array
    {
        if (!ctype_digit((string)$externalId)) {
            throw new Exception('Azure DevOps work item ids are numeric.');
        }
        $url = $this->apiUrl('wit/workitems/' . rawurlencode($externalId), ['fields' => $this->readFields()]);
        return $this->parseWorkItem($this->devopsRequest('GET', $url));
    }

    /**
     * Batch read — one call for the whole watch list, because this is the poll
     * cron's hot path.
     *
     * ⚠️ Capped at 200 ids per call by the API. Asking for 201 is a 400, not a
     * truncated list, so the chunking is load-bearing rather than politeness.
      *
      * @param array $externalIds  work item ids to read; ones that are not numeric are dropped
     */
    public function fetchIssues(array $externalIds): array
    {
        $ids = array_values(array_unique(array_filter(array_map('strval', $externalIds), 'ctype_digit')));
        if (!$ids) return [];

        $out = [];
        foreach (array_chunk($ids, 200) as $chunk) {
            try {
                $res = $this->devopsRequest('GET', $this->apiUrl('wit/workitems', [
                    'ids'    => implode(',', $chunk),
                    'fields' => $this->readFields(),
                    // Without this, ONE deleted work item 404s the whole batch and
                    // every other linked issue silently stops updating.
                    'errorPolicy' => 'omit',
                ]));
            } catch (Exception) {
                continue;   // a bad chunk must not lose the others
            }
            foreach ((array)($res['value'] ?? []) as $wi) {
                if (!is_array($wi) || !isset($wi['id'])) continue;   // omitted entries come back null
                $parsed = $this->parseWorkItem($wi);
                if ($parsed['external_id'] !== '') $out[$parsed['external_id']] = $parsed;
            }
        }
        return $out;
    }

    // ----------------------------------------------------------- comments

    /**
     * Comments are project-scoped even though work item ids are not, so we have
     * to know which project the item lives in. Resolved from the item itself and
     * cached, rather than requiring every caller to carry it.
      *
      * @param string $externalId  the numeric work item id
     */
    protected function projectOf(string $externalId): string
    {
        static $cache = [];
        if (isset($cache[$externalId])) return $cache[$externalId];

        $wi = $this->devopsRequest('GET',
            $this->apiUrl('wit/workitems/' . rawurlencode($externalId), ['fields' => 'System.TeamProject']));
        $project = (string)($wi['fields']['System.TeamProject'] ?? '');
        if ($project === '') {
            throw new Exception('Could not work out which Azure DevOps project work item ' . $externalId . ' belongs to.');
        }
        return $cache[$externalId] = $project;
    }

    /**
     * Posts a comment, rendered to HTML, on a work item and returns the new comment's id.
     *
     * @param string $externalId  the numeric work item id
     * @param IssueDoc $body  the comment, rendered to HTML
     */
    public function addComment(string $externalId, IssueDoc $body): string
    {
        $url = $this->projectUrl($this->projectOf($externalId),
            'wit/workItems/' . rawurlencode($externalId) . '/comments', [], self::API_VERSION_COMMENT);

        $res = $this->devopsRequest('POST', $url, ['text' => $this->renderDoc($body)]);
        return (string)($res['id'] ?? '');
    }

    /**
     * Comments on one work item, normalised.
     *
      * @param string $externalId  the numeric work item id
     * @return array [['comment_id'=>…, 'comment_body'=>plain text,
     *                 'author_identity'=>…, 'author_name'=>…, 'created_ts'=>?int], …]
     */
    public function fetchComments(string $externalId): array
    {
        $url = $this->projectUrl($this->projectOf($externalId),
            'wit/workItems/' . rawurlencode($externalId) . '/comments',
            ['$top' => 200, 'order' => 'desc'], self::API_VERSION_COMMENT);

        $out = [];
        foreach ((array)($this->devopsRequest('GET', $url)['comments'] ?? []) as $c) {
            $parsed = $this->parseComment((array) $c);
            if ($parsed['comment_id'] !== '') $out[] = $parsed;
        }
        return $out;
    }

    /**
     * One Azure DevOps comment → the fields the canonical comment_added event needs.
     *
     * ⚠️ author_identity is `createdBy.id`, NOT `createdBy.descriptor`. Echo
     * suppression compares this against what testConnection() returned, and the
     * two endpoints report descriptors in DIFFERENT formats for the same person
     * — `connectionData` gives a ClaimsIdentity string while a comment gives
     * `aad.<base64>`. Comparing descriptors would never match, so every one of
     * our own comments would be re-imported as if a developer had written it.
     * The GUID in `id` is identical across both.
      *
      * @param array $c  one entry of the comments response - id, text (HTML), createdBy, createdDate
     */
    protected function parseComment(array $c): array
    {
        $author  = (array)($c['createdBy'] ?? []);
        $created = (string)($c['createdDate'] ?? '');

        return [
            'comment_id'      => (string)($c['id'] ?? ''),
            'comment_body'    => $this->htmlToText((string)($c['text'] ?? '')),
            'author_identity' => (string)($author['id'] ?? ''),
            'author_name'     => (string)($author['displayName'] ?? ''),
            'created_ts'      => $created !== '' ? (strtotime($created) ?: null) : null,
        ];
    }

    /**
     * A comment's HTML → plain text for the internal note.
     *
     * ⚠️ Uses the shared reducer rather than strip_tags(), for the reason
     * documented on integrationsHtmlToIssueText(): strip_tags removes the TAGS
     * but keeps the CONTENT of <style>, so a pasted-in styled block arrives as
     * a wall of CSS.
      *
      * @param string $html  a comment's text as HTML
     */
    protected function htmlToText(string $html): string
    {
        if (function_exists('integrationsHtmlToIssueText')) {
            return integrationsHtmlToIssueText($html);
        }
        return trim(preg_replace('/\s+/u', ' ', strip_tags($html)));
    }

    // -------------------------------------------------------- attachments

    /**
     * Two steps, and the second is the one that is easy to miss.
     *
     * Uploading returns a URL but attaches the file to NOTHING. The work item
     * only gains it when a second JSON Patch adds an `AttachedFile` relation —
     * so an implementation that stops after the upload reports success, and the
     * file exists in the org, invisible to everyone.
     *
      * @param string $externalId  the numeric id of the work item to attach the file to
     * @param array $file ['data'=>binary, 'filename'=>string, 'content_type'=>string]
     */
    public function addAttachment(string $externalId, array $file): void
    {
        $project  = $this->projectOf($externalId);
        $filename = $this->safeFilename((string)($file['filename'] ?? 'attachment'));

        // 1. Upload the bytes. Sent raw — this endpoint takes an octet-stream
        //    body, NOT multipart/form-data (which is what Jira wants; sending
        //    the wrong one here produces a corrupt attachment rather than an error).
        $creds = $this->connection['credentials'] ?? [];
        list($code, $raw) = $this->httpRequest(
            $this->projectUrl($project, 'wit/attachments', ['fileName' => $filename]),
            [
                'method'  => 'POST',
                'auth'    => ':' . ($creds['api_token'] ?? ''),
                'headers' => ['Accept: application/json', 'Content-Type: application/octet-stream'],
                'body'    => (string)($file['data'] ?? ''),
                'timeout' => 120,
            ]
        );
        if ($code < 200 || $code >= 300) {
            throw new Exception($this->extractError($code, $raw));
        }
        $uploaded = json_decode($raw, true);
        $url      = (string)($uploaded['url'] ?? '');
        if ($url === '') {
            throw new Exception('Azure DevOps accepted the upload but returned no attachment URL.');
        }

        // 2. Link it to the work item. Note the `/relations/-` path: the dash
        //    means "append", and it is the only way to add without clobbering
        //    the relations already there.
        $this->patchRequest('PATCH',
            $this->apiUrl('wit/workitems/' . rawurlencode($externalId)),
            [[
                'op'    => 'add',
                'path'  => '/relations/-',
                'value' => [
                    'rel'        => 'AttachedFile',
                    'url'        => $url,
                    'attributes' => ['comment' => 'Attached from FreeITSM'],
                ],
            ]]
        );
    }

    /**
     * Azure DevOps takes the filename verbatim; strip anything path- or header-breaking.
     *
     * @param string $filename  the file's original name
     */
    protected function safeFilename(string $filename): string
    {
        $safe = str_replace(['"', "\r", "\n", '\\', '/'], '', basename($filename));
        return $safe !== '' ? $safe : 'attachment';
    }

    // ------------------------------------------------------------ updates

    /**
     * ⚠️ A flat map of reference name => value, translated into `add` ops.
     *
     * `add` is correct rather than `replace` for Azure DevOps: on a field that
     * has no value yet, `replace` fails, whereas `add` both sets and overwrites.
     * The contract cannot express `remove`, so clearing a field is not supported
     * here — and that is a genuine limit rather than an oversight.
      *
      * @param string $externalId  the numeric work item id
      * @param array $fields  field reference name => value; each becomes an `add` op
     */
    public function updateFields(string $externalId, array $fields): void
    {
        $ops = [];
        foreach ($fields as $refName => $value) {
            if (!is_string($refName) || $refName === '') continue;
            $ops[] = $this->addOp($refName, $value);
        }
        if (!$ops) return;

        $this->patchRequest('PATCH', $this->apiUrl('wit/workitems/' . rawurlencode($externalId)), $ops);
    }

    // ------------------------------------------------------------- polling

    /**
     * Which watched work items changed, and what was said on them.
     *
     * ⚠️ Two things here are easy to get wrong and both fail quietly:
     *
     *  1. **`timePrecision=true` is a QUERY parameter, not a body field.**
     *     Without it Azure DevOps refuses any WIQL carrying a time-of-day and
     *     the whole poll errors. The tempting "fix" is to drop to a date-only
     *     boundary — which silently re-reads a WHOLE DAY of comments on every
     *     run, because our watermark moves in minutes.
     *  2. **The timestamp must carry an explicit `Z`.** With one, Azure DevOps
     *     honours real UTC — verified against a live organisation, including a
     *     future-dated window correctly matching nothing. This is the OPPOSITE
     *     of Jira, where an absolute date is read in the Jira user's own
     *     timezone and forced us onto relative minutes.
      *
      * @param ?string $since  UTC 'Y-m-d H:i:s' watermark (last_poll_watermark); null on the first run returns nothing
      * @param array $externalIds  ids of the work items this connection holds links to; non-numeric ones are dropped
     */
    public function pollChanges(?string $since, array $externalIds = []): array
    {
        if ($since === null) return [];

        $ids = array_values(array_unique(array_filter(array_map('strval', $externalIds), 'ctype_digit')));
        if (!$ids) return [];

        $sinceTs = strtotime($since . ' UTC');
        if (!$sinceTs) return [];

        $cutoff = $this->pollCutoff($sinceTs);

        $events = [];
        foreach ($this->searchChangedSince($ids, $cutoff) as $externalId) {
            try {
                $comments = $this->fetchComments($externalId);
            } catch (Exception) {
                continue;   // one unreadable item must not lose the others' comments
            }
            foreach ($comments as $c) {
                if ($c['created_ts'] !== null && $c['created_ts'] <= $cutoff) continue;
                if ($c['comment_body'] === '') continue;
                $events[] = [
                    'event_id'        => 'devops-comment-' . $c['comment_id'],
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
     * The window boundary: the watermark, less a small overlap, and never more
     * than the cap. Pure, so the capping rule is provable without a clock.
     *
     * The overlap exists because a comment written in the same second the poll
     * ran would otherwise fall between two windows and never be seen; the same
     * comment arriving twice is harmless, as the comment map dedupes by id.
      *
      * @param int $sinceTs  the watermark as a Unix timestamp
      * @param ?int $nowTs  the current time as a Unix timestamp; null means time()
     */
    protected function pollCutoff(int $sinceTs, ?int $nowTs = null): int
    {
        $now     = $nowTs ?? time();
        $overlap = $sinceTs - (self::COMMENT_OVERLAP_MINUTES * 60);
        $floor   = $now - (self::COMMENT_LOOKBACK_CAP_HOURS * 3600);
        return (int) max($overlap, $floor);
    }

    /**
     * WIQL is UTC when the literal says so. Format it that way, always.
     *
     * @param int $ts  a Unix timestamp
     */
    protected function wiqlTimestamp(int $ts): string
    {
        return gmdate('Y-m-d\TH:i:s', $ts) . '.0000000Z';
    }

    /**
     * Asks WIQL which of the watched work items changed since $cutoff, in chunks.
     *
     * A chunk that fails is skipped rather than failing the whole poll.
     *
      * @param array $ids  numeric work item ids to check
      * @param int $cutoff  a Unix timestamp; items changed at or after it are returned
     * @return string[] ids of watched work items changed since $cutoff
     */
    protected function searchChangedSince(array $ids, int $cutoff): array
    {
        $out   = [];
        $stamp = $this->wiqlTimestamp($cutoff);

        foreach (array_chunk($ids, self::WIQL_CHUNK) as $chunk) {
            $wiql = 'SELECT [System.Id] FROM WorkItems'
                  . ' WHERE [System.Id] IN (' . implode(',', array_map('intval', $chunk)) . ')'
                  . " AND [System.ChangedDate] >= '" . $stamp . "'";
            try {
                $res = $this->devopsRequest('POST',
                    $this->apiUrl('wit/wiql', ['timePrecision' => 'true']), ['query' => $wiql]);
            } catch (Exception) {
                continue;
            }
            foreach ((array)($res['workItems'] ?? []) as $w) {
                if (isset($w['id'])) $out[] = (string)$w['id'];
            }
        }
        return $out;
    }

    // ----------------------------------------------------------- discovery

    /**
     * Confirm the credentials and capture who we are.
     *
     * ⚠️ `connectionData` rejects a bare `api-version=7.0` and demands the
     * `-preview` suffix, unlike every other endpoint this file calls.
     *
     * account_identity is half of echo suppression — see parseComment() for why
     * this must be the GUID and never the descriptor.
     */
    public function testConnection(): array
    {
        $data = $this->devopsRequest('GET', $this->apiUrl('connectionData', [], self::API_VERSION_CONNDATA));

        $user     = (array)($data['authenticatedUser'] ?? []);
        $identity = (string)($user['id'] ?? '');
        $display  = (string)($user['providerDisplayName'] ?? $identity);

        // An anonymous identity is what a rejected PAT looks like when Azure
        // DevOps answers with a sign-in page instead of a 401 — see extractError().
        if ($identity === '' || $identity === '00000000-0000-0000-0000-000000000000') {
            throw new Exception('Azure DevOps did not identify the account this token belongs to. '
                              . 'Check the personal access token is valid and not expired.');
        }

        return [
            'detail'           => 'Connected to Azure DevOps as ' . $display,
            'account_identity' => $identity,
        ];
    }

    /**
     * Lists the organisation's well-formed projects for the mapping dropdowns, a page of
     * 100 at a time up to 1,000.
     *
     * @return array [['key'=>'Support','name'=>'Support'], …]
     */
    public function listProjects(): array
    {
        $out  = [];
        $skip = 0;
        do {
            $res = $this->devopsRequest('GET', $this->apiUrl('projects', [
                '$top' => 100, '$skip' => $skip, 'stateFilter' => 'wellFormed',
            ]));
            $batch = (array)($res['value'] ?? []);
            foreach ($batch as $p) {
                $name = (string)($p['name'] ?? '');
                // ⚠️ The NAME is the key here, not the id. Every other work item
                // route takes the project name (or its GUID) in the path, and the
                // name is what an admin recognises in a dropdown.
                if ($name !== '') $out[] = ['key' => $name, 'name' => $name];
            }
            $skip += 100;
        } while (count($batch) === 100 && $skip < 1000);

        return $out;
    }

    /**
     * The work item types this project's process defines.
     *
     * ⚠️ Per-project, genuinely — two projects in one organisation on different
     * processes return different lists. Basic gives Issue/Epic/Task; Agile adds
     * Bug, User Story and Feature. Caching this across projects would be wrong.
      *
      * @param string $project  the project name, as listProjects() returns it in `key`
     */
    public function listIssueTypes(string $project): array
    {
        $out = [];
        foreach ((array)($this->devopsRequest('GET',
                    $this->projectUrl($project, 'wit/workitemtypes'))['value'] ?? []) as $t) {
            if (!empty($t['isDisabled'])) continue;
            $name = (string)($t['name'] ?? '');
            if ($name === '') continue;
            // Test and code-review types are machinery, not somewhere a service
            // desk escalates to. Offering them makes the dropdown useless.
            if ($this->isInternalType($name)) continue;
            $out[] = ['id' => $name, 'name' => $name];
        }
        return $out;
    }

    /**
     * Types that exist for tooling rather than for people to be assigned work.
     *
     * @param string $name  the work item type name
     */
    protected function isInternalType(string $name): bool
    {
        return in_array($name, [
            'Test Case', 'Test Plan', 'Test Suite', 'Shared Steps', 'Shared Parameter',
            'Code Review Request', 'Code Review Response',
            'Feedback Request', 'Feedback Response',
        ], true);
    }
}
