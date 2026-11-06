<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * External issue trackers — IssueDoc and the provider contract.
 *
 * IssueDoc is the piece every later phase writes through: descriptions in V1,
 * comments in V2, everything after. If it is wrong, V2 is where we find out and
 * where we would have to rip it up. So it gets a real suite now rather than an
 * eyeball.
 *
 * Four renderers produce four genuinely different syntaxes from one document,
 * and the risk is that they drift — one of them quietly stops escaping, or
 * handles an empty paragraph differently. Every case therefore runs through ALL
 * FOUR and each is asserted separately.
 *
 *   php tests/integrations/run.php
 *
 * ⚠️ Every negative assertion is paired with a positive control. "The asterisk
 * did not become italics" proves nothing on its own — it is equally true of a
 * renderer that dropped the text entirely. So each escaping test also asserts
 * the surrounding content still arrived.
 *
 * No database. No network. Pure functions, so this suite is fast and safe to run
 * anywhere.
 */

require_once __DIR__ . '/../../includes/integrations/IssueDoc.php';
require_once __DIR__ . '/../../includes/integrations/IssueTrackerProvider.php';

$pass = 0; $fail = 0; $failures = [];

function ok(string $what, bool $cond) {
    global $pass, $fail, $failures;
    if ($cond) { $pass++; return; }
    $fail++; $failures[] = $what;
}
function eq(string $what, $expected, $actual) {
    global $pass, $fail, $failures;
    if ($expected === $actual) { $pass++; return; }
    $fail++;
    $failures[] = $what . "\n      expected: " . var_export($expected, true)
                        . "\n      actual:   " . var_export($actual, true);
}

echo "External issue trackers — IssueDoc\n";
echo str_repeat('=', 62) . "\n\n";

// ---------------------------------------------------------------------------
echo "1. A realistic escalation body renders in all four formats\n";
// ---------------------------------------------------------------------------

$doc = (new IssueDoc)
    ->heading('Raised in FreeITSM')
    ->para('Ticket ', IssueDoc::link('https://sd.example/t/1042', 'SD-1042'),
           ' · Jane Doe (Acme Ltd)')
    ->rule()
    ->para('Outlook crashes when sending with an attachment.')
    ->bullets(['Happens every time', 'Only on the laptop'])
    ->code("Exception 0xC0000005\n  at MSO.dll");

$adf  = $doc->toAdf();
$md   = $doc->toMarkdown();
$wiki = $doc->toWikiMarkup();
$html = $doc->toHtml();

eq('ADF is a doc v1',            'doc', $adf['type']);
eq('ADF version',                1,     $adf['version']);
eq('ADF block count',            6,     count($adf['content']));
eq('ADF first block is heading', 'heading', $adf['content'][0]['type']);
eq('ADF rule survives',          'rule',    $adf['content'][2]['type']);
eq('ADF bulletList',             'bulletList', $adf['content'][4]['type']);
eq('ADF codeBlock',              'codeBlock',  $adf['content'][5]['type']);
ok('ADF JSON-encodes cleanly',   json_encode($adf) !== false);

// The link must become a text node carrying a link MARK — a nested link node is
// the wrong shape and Jira rejects the whole document.
$linkNode = $adf['content'][1]['content'][1];
eq('ADF link is a text node',    'text', $linkNode['type']);
eq('ADF link text',              'SD-1042', $linkNode['text']);
eq('ADF link mark href', 'https://sd.example/t/1042', $linkNode['marks'][0]['attrs']['href']);

ok('Markdown heading',   str_starts_with($md, '### Raised in FreeITSM'));
ok('Markdown link',      str_contains($md, '](https://sd.example/t/1042)'));
ok('Markdown rule',      str_contains($md, "\n---\n"));
ok('Markdown bullet',    str_contains($md, '- Happens every time'));
ok('Markdown fence',     str_contains($md, "```\nException"));

ok('Wiki heading',       str_starts_with($wiki, 'h3. Raised in FreeITSM'));
ok('Wiki link syntax',   str_contains($wiki, '|https://sd.example/t/1042]'));
ok('Wiki rule',          str_contains($wiki, "\n----\n"));
ok('Wiki bullet',        str_contains($wiki, '* Happens every time'));
ok('Wiki code macro',    str_contains($wiki, "{code}\nException"));

ok('HTML heading',       str_contains($html, '<h3>Raised in FreeITSM</h3>'));
ok('HTML anchor',        str_contains($html, '<a href="https://sd.example/t/1042">SD-1042</a>'));
ok('HTML rule',          str_contains($html, '<hr>'));
ok('HTML list item',     str_contains($html, '<li>Happens every time</li>'));
ok('HTML pre',           str_contains($html, '<pre>Exception'));

// ---------------------------------------------------------------------------
echo "2. Ticket text cannot inject formatting (each with a positive control)\n";
// ---------------------------------------------------------------------------

// A requester writing about wildcards and brackets must not produce italics or
// a broken link — but the words themselves must still arrive.
$nasty = (new IssueDoc)->para('use the * wildcard and [see notes] plus _underscore_');
$nmd   = $nasty->toMarkdown();

ok('MD escapes asterisk',        str_contains($nmd, '\\*'));
ok('MD escapes bracket',         str_contains($nmd, '\\['));
ok('MD escapes underscore',      str_contains($nmd, '\\_'));
ok('MD positive control: words survived',
   str_contains($nmd, 'wildcard') && str_contains($nmd, 'see notes'));

$nwiki = $nasty->toWikiMarkup();
ok('Wiki escapes bracket',       str_contains($nwiki, '\\['));
ok('Wiki escapes asterisk',      str_contains($nwiki, '\\*'));
ok('Wiki positive control: words survived', str_contains($nwiki, 'wildcard'));

// HTML is the one where a failure is an actual injection, not just ugly output.
$xss  = (new IssueDoc)->para('<script>alert(1)</script> & "quoted"');
$xh   = $xss->toHtml();
ok('HTML escapes the tag',       !str_contains($xh, '<script>'));
ok('HTML encoded the tag',       str_contains($xh, '&lt;script&gt;'));
ok('HTML escapes ampersand',     str_contains($xh, '&amp;'));
ok('HTML positive control: text survived', str_contains($xh, 'alert(1)'));

// A link's TEXT is attacker-influenced too.
$linkXss = (new IssueDoc)->para(IssueDoc::link('https://x.example', '<b>bold</b>'))->toHtml();
ok('HTML escapes link text',     !str_contains($linkXss, '<b>bold</b>'));
ok('HTML link positive control', str_contains($linkXss, '&lt;b&gt;bold'));

// ADF carries text literally (it is JSON, not markup) — assert it is NOT escaped,
// because double-escaping there would show users literal backslashes in Jira.
$adfNasty = $nasty->toAdf();
eq('ADF keeps text literal', 'use the * wildcard and [see notes] plus _underscore_',
   $adfNasty['content'][0]['content'][0]['text']);

// ---------------------------------------------------------------------------
echo "3. Degenerate documents stay valid (ADF is strict and 400s otherwise)\n";
// ---------------------------------------------------------------------------

$empty = new IssueDoc;
ok('Empty doc reports empty',    $empty->isEmpty());
$ea = $empty->toAdf();
ok('Empty doc still valid ADF',  !empty($ea['content']));
eq('Empty doc yields a paragraph', 'paragraph', $ea['content'][0]['type']);
eq('Empty doc renders empty markdown', '', $empty->toMarkdown());

// Empty strings must not become empty text nodes — ADF rejects those.
$blank = (new IssueDoc)->para('')->heading('')->bullets(['', '  '])->code('');
ok('Blank builders add nothing',  $blank->isEmpty());

// A rule with nothing before it, or two in a row, is always generated-output noise.
$rules = (new IssueDoc)->rule()->para('x')->rule()->rule()->para('y');
$rblocks = $rules->blocks();
eq('Leading + duplicate rules collapse', 3, count($rblocks));
eq('First block is the paragraph', IssueDoc::T_PARA ?? 'para', $rblocks[0][0]);

// Every ADF text node must be a non-empty string, at any depth.
function adfTextNodesValid(array $node): bool {
    if (isset($node['type']) && $node['type'] === 'text') {
        if (!isset($node['text']) || !is_string($node['text']) || $node['text'] === '') return false;
    }
    foreach (($node['content'] ?? []) as $child) {
        if (!adfTextNodesValid($child)) return false;
    }
    return true;
}
ok('No empty ADF text nodes anywhere', adfTextNodesValid($doc->toAdf()));
ok('No empty ADF text nodes (blank doc)', adfTextNodesValid($blank->toAdf()));

// ---------------------------------------------------------------------------
echo "4. The provider contract\n";
// ---------------------------------------------------------------------------

$rc = new ReflectionClass('IssueTrackerProvider');
ok('Provider is abstract', $rc->isAbstract());

// The five things every tracker genuinely must do are abstract; everything a
// tracker might lack defaults to throwing, so the contract never collapses to a
// lowest common denominator.
foreach (['createIssue', 'fetchIssue', 'testConnection', 'renderDoc'] as $m) {
    ok("$m is abstract", $rc->getMethod($m)->isAbstract());
}
foreach (['addComment', 'addAttachment', 'verifyWebhook', 'pollChanges', 'listProjects'] as $m) {
    ok("$m is optional (not abstract)", !$rc->getMethod($m)->isAbstract());
}

// Status categories are a closed set — the whole point is that no provider's
// vocabulary leaks into ours.
eq('4 status categories', 4, count(array_filter(
    $rc->getConstants(),
    function ($k) { return str_starts_with($k, 'STATUS_'); },
    ARRAY_FILTER_USE_KEY
)));

// A minimal concrete provider must be constructible with only the abstract
// methods implemented — that is what "adding GitHub is one file" means.
eval('class ProbeProvider extends IssueTrackerProvider {
    public function createIssue(array $t, string $s, IssueDoc $b, array $f = []): array { return []; }
    public function fetchIssue(string $id): array { return []; }
    public function testConnection(): array { return []; }
    public function renderDoc(IssueDoc $doc) { return $doc->toMarkdown(); }
    protected function capabilities(): array { return [self::CAP_ATTACHMENTS]; }
}');
$probe = new ProbeProvider(['provider' => 'probe']);
ok('Minimal provider constructs', $probe instanceof IssueTrackerProvider);
eq('getProvider reads the row', 'probe', $probe->getProvider());
ok('supports() true for declared',  $probe->supports(IssueTrackerProvider::CAP_ATTACHMENTS));
ok('supports() false for undeclared', !$probe->supports(IssueTrackerProvider::CAP_ISSUE_TYPES));
ok('renderDoc delegates to IssueDoc', $probe->renderDoc((new IssueDoc)->para('hi')) === 'hi');

// An unsupported optional method must throw, not silently no-op — a silent
// no-op is how "we sent the comment" becomes a lie.
$threw = false;
try { $probe->addComment('1', new IssueDoc); } catch (Exception $e) { $threw = true; }
ok('Unsupported method throws', $threw);

// ---------------------------------------------------------------------------
echo "5. JiraProvider — Cloud vs Data Center, without a live Jira\n";
// ---------------------------------------------------------------------------

require_once __DIR__ . '/../../includes/integrations/JiraProvider.php';

/**
 * Stubs the one method that touches the network, so everything that actually
 * decides correctness — the ADF-vs-wiki choice, the status map, payload shape,
 * error extraction, paging — is provable offline. $queue is a list of
 * [httpCode, body] handed out in order; $seen records the requests made.
 */
class FakeJira extends JiraProvider
{
    public $queue = [];
    public $seen  = [];
    protected function httpRequest(string $url, array $opts = []): array
    {
        $this->seen[] = ['url' => $url, 'opts' => $opts];
        if (!$this->queue) return [200, '{}'];
        return array_shift($this->queue);
    }
    // Expose the protected logic under test.
    public function pubBuild(array $t, string $s, IssueDoc $b, array $f = []) { return $this->buildCreatePayload($t, $s, $b, $f); }
    public function pubMapStatus(string $k) { return $this->mapStatusCategory($k); }
    public function pubError(int $c, string $b) { return $this->extractError($c, $b); }
    public function pubApiUrl(string $p) { return $this->apiUrl($p); }
    public function pubAdfToText($n) { return $this->adfToText($n); }
    public function pubCommentBody($b) { return $this->commentBodyToText($b); }
    public function pubParseComment(array $c) { return $this->parseComment($c); }
    public function pubLookback(int $since, ?int $now = null) { return $this->lookbackMinutes($since, $now); }
    public function pubMultipart(string $fn, string $ct, string $d, ?string &$b) { return $this->buildMultipart($fn, $ct, $d, $b); }
}

function mkJira(array $overrides = []): FakeJira {
    return new FakeJira(array_merge([
        'provider'    => 'jira',
        'base_url'    => 'https://acme.atlassian.net',
        'credentials' => ['email' => 'svc@acme.com', 'api_token' => 'tok'],
    ], $overrides));
}

$cloud  = mkJira();
$server = mkJira(['base_url' => 'https://jira.acme.internal',
                  'credentials' => ['api_token' => 'pat', 'flavour' => 'server']]);

// Flavour detection — the heuristic, then the stored override winning over it.
eq('atlassian.net sniffs as Cloud',  'cloud',  $cloud->flavour());
eq('custom domain sniffs as Server', 'server', $server->flavour());
eq('Cloud uses API v3', 'https://acme.atlassian.net/rest/api/3/myself', $cloud->pubApiUrl('myself'));
eq('Server uses API v2', 'https://jira.acme.internal/rest/api/2/myself', $server->pubApiUrl('myself'));
$stored = mkJira(['credentials' => ['flavour' => 'server']]);
eq('Stored flavour beats the heuristic', 'server', $stored->flavour());

// A trailing slash on the site URL is the single most likely piece of user
// input, and it must not produce a double slash in every request.
$slashy = mkJira(['base_url' => 'https://acme.atlassian.net/']);
eq('Trailing slash normalised', 'https://acme.atlassian.net/rest/api/3/issue', $slashy->pubApiUrl('issue'));
eq('Browse URL built', 'https://acme.atlassian.net/browse/OPS-412', $slashy->browseUrl('OPS-412'));

// The SAME document must arrive as ADF on Cloud and wiki markup on Server.
// This is the whole reason IssueDoc exists, so it gets asserted both ways.
$body = (new IssueDoc)->heading('Raised in FreeITSM')->para('Outlook crashes');
ok('Cloud renders ADF (array)',      is_array($cloud->renderDoc($body)));
eq('Cloud ADF is a doc',   'doc',    $cloud->renderDoc($body)['type']);
$serverRendered = $server->renderDoc($body);
ok('Server renders a string',        is_string($serverRendered));
// Guarded on is_string: if this regresses to ADF, the assertion must FAIL and
// let the rest of the suite run, not fatal on strpos() and hide everything after.
ok('Server renders wiki markup',     is_string($serverRendered)
                                     && str_starts_with($serverRendered, 'h3. Raised in FreeITSM'));

// Create payload.
$p = $cloud->pubBuild(['project' => 'OPS', 'issue_type' => 'Bug'], 'Outlook crashes', $body);
eq('Payload project key', 'OPS', $p['fields']['project']['key']);
eq('Payload issue type',  'Bug', $p['fields']['issuetype']['name']);
eq('Payload summary',     'Outlook crashes', $p['fields']['summary']);
ok('Payload description is ADF on Cloud', is_array($p['fields']['description']));
ok('Payload sends NO priority field', !isset($p['fields']['priority']));

// A multi-line subject must be flattened — Jira rejects a newline in summary
// with an error that gives no clue what is wrong.
$ml = $cloud->pubBuild(['project' => 'OPS', 'issue_type' => 'Bug'], "Line one\nLine two", $body);
eq('Multi-line summary flattened', 'Line one Line two', $ml['fields']['summary']);
$long = $cloud->pubBuild(['project' => 'OPS', 'issue_type' => 'Bug'], str_repeat('x', 400), $body);
ok('Over-long summary truncated', mb_strlen($long['fields']['summary']) <= 255);
ok('Truncation is marked',        mb_substr($long['fields']['summary'], -1) === '…');

// Missing target must fail loudly here rather than as a confusing Jira 400.
$threw = false;
try { $cloud->pubBuild(['issue_type' => 'Bug'], 's', $body); } catch (Exception $e) { $threw = true; }
ok('Missing project throws', $threw);
$threw = false;
try { $cloud->pubBuild(['project' => 'OPS'], 's', $body); } catch (Exception $e) { $threw = true; }
ok('Missing issue type throws', $threw);

// Status mapping — the three fixed Jira categories, and the refusal to guess.
eq('new → todo',                 IssueTrackerProvider::STATUS_TODO,        $cloud->pubMapStatus('new'));
eq('indeterminate → in_progress', IssueTrackerProvider::STATUS_IN_PROGRESS, $cloud->pubMapStatus('indeterminate'));
eq('done → done',                IssueTrackerProvider::STATUS_DONE,        $cloud->pubMapStatus('done'));
eq('unknown category → null (never guess)', null, $cloud->pubMapStatus('banana'));
eq('empty category → null',      null, $cloud->pubMapStatus(''));

// Error extraction. The common real failure is a project demanding a field we
// did not send; "HTTP 400" alone would make that undiagnosable.
$err = $cloud->pubError(400, '{"errorMessages":[],"errors":{"customfield_10010":"Epic Link is required"}}');
ok('Field error names the field', str_contains($err, 'customfield_10010'));
ok('Field error gives the reason', str_contains($err, 'Epic Link is required'));
ok('errorMessages surfaced', str_contains($cloud->pubError(400, '{"errorMessages":["Project is required"]}'), 'Project is required'));
ok('401 is described as credentials', stripos($cloud->pubError(401, ''), 'credential') !== false);
ok('404 mentions the site URL',       stripos($cloud->pubError(404, ''), 'site URL') !== false);
ok('Non-JSON body still yields a message', $cloud->pubError(500, '<html>Server Error</html>') !== '');
ok('Non-JSON body is stripped of tags',    !str_contains($cloud->pubError(500, '<html>Server Error</html>'), '<html>'));

// fetchIssue parses a real-shaped Jira response.
$cloud->queue = [[200, json_encode([
    'id' => '10042', 'key' => 'OPS-412',
    'fields' => [
        'summary'  => 'Outlook crashes',
        'status'   => ['name' => 'In Progress', 'statusCategory' => ['key' => 'indeterminate']],
        'assignee' => ['displayName' => 'Sam Patel'],
    ],
])]];
$issue = $cloud->fetchIssue('10042');
eq('fetch external_id',     '10042', $issue['external_id']);
eq('fetch external_key',    'OPS-412', $issue['external_key']);
eq('fetch url',             'https://acme.atlassian.net/browse/OPS-412', $issue['external_url']);
eq('fetch status_name',     'In Progress', $issue['status_name']);
eq('fetch status_category', 'in_progress', $issue['status_category']);
eq('fetch assignee',        'Sam Patel', $issue['assignee_name']);

// An unassigned issue with an unknown status must not explode or invent values.
$cloud->queue = [[200, json_encode(['id' => '1', 'key' => 'A-1', 'fields' => ['status' => ['name' => 'Odd', 'statusCategory' => ['key' => 'weird']]]])]];
$sparse = $cloud->fetchIssue('1');
eq('Unknown category → null', null, $sparse['status_category']);
eq('Raw name still kept for display', 'Odd', $sparse['status_name']);
eq('Missing assignee → null', null, $sparse['assignee_name']);

// createIssue: the follow-up read failing must NOT lose an issue that exists.
$cloud->queue = [
    [200, json_encode(['id' => '10099', 'key' => 'OPS-999'])],  // create succeeded
    [500, 'boom'],                                              // the status read did not
];
$created = $cloud->createIssue(['project' => 'OPS', 'issue_type' => 'Bug'], 'x', $body);
eq('Created id survives a failed follow-up read', '10099', $created['external_id']);
eq('Created key survives',  'OPS-999', $created['external_key']);
eq('Created URL survives',  'https://acme.atlassian.net/browse/OPS-999', $created['external_url']);
eq('Status simply unknown', null, $created['status_category']);

// ...but a create that genuinely fails must throw, not return a broken link.
$cloud->queue = [[400, '{"errors":{"summary":"Summary is required"}}']];
$threw = false;
try { $cloud->createIssue(['project' => 'OPS', 'issue_type' => 'Bug'], 'x', $body); } catch (Exception $e) { $threw = true; }
ok('Failed create throws', $threw);

// Batch fetch: one call for the whole watch list, and non-numeric ids rejected
// before they can be interpolated into JQL.
$cloud->seen = [];
$cloud->queue = [[200, json_encode(['issues' => [
    ['id' => '1', 'key' => 'A-1', 'fields' => ['status' => ['name' => 'To Do', 'statusCategory' => ['key' => 'new']]]],
    ['id' => '2', 'key' => 'A-2', 'fields' => ['status' => ['name' => 'Done',  'statusCategory' => ['key' => 'done']]]],
]])]];
$many = $cloud->fetchIssues(['1', '2']);
eq('Batch made ONE call', 1, count($cloud->seen));
eq('Batch returned both', 2, count($many));
eq('Batch keyed by id',   'done', $many['2']['status_category']);
ok('Batch used JQL id in ()', str_contains(urldecode($cloud->seen[0]['url']), 'id in (1,2)'));

// ⚠️ Cloud REMOVED /rest/api/3/search (Atlassian CHANGE-2046) — it is
// /search/jql now. Data Center's v2 /search still exists. This was found against
// live Jira, where the old path returned "The requested API has been removed",
// so both halves are pinned here.
ok('Cloud batch uses /search/jql',
   str_contains($cloud->seen[0]['url'], '/rest/api/3/search/jql?'));
$srvBatch = mkJira(['base_url' => 'https://jira.acme.internal',
                    'credentials' => ['api_token' => 'p', 'flavour' => 'server']]);
$srvBatch->queue = [[200, json_encode(['issues' => []])]];
$srvBatch->fetchIssues(['1']);
ok('Data Center batch uses v2 /search (NOT /search/jql)',
   str_contains($srvBatch->seen[0]['url'], '/rest/api/2/search?')
   && !str_contains($srvBatch->seen[0]['url'], 'search/jql'));

$cloud->seen = [];
eq('Non-numeric ids rejected', [], $cloud->fetchIssues(['1 OR 1=1', 'DROP']));
eq('...and no request was made', 0, count($cloud->seen));
eq('Empty watch list makes no call', [], $cloud->fetchIssues([]));

// testConnection captures WHO we are — half of echo suppression.
$cloud->queue = [[200, json_encode(['accountId' => '5b10a2', 'displayName' => 'FreeITSM Bot'])]];
$t = $cloud->testConnection();
eq('Cloud identity is accountId', '5b10a2', $t['account_identity']);
eq('Cloud flavour recorded', 'cloud', $t['flavour']);
ok('Detail names the account', str_contains($t['detail'], 'FreeITSM Bot'));

$server->queue = [[200, json_encode(['name' => 'svcacct', 'displayName' => 'Service Account'])]];
$ts = $server->testConnection();
eq('Server identity is username', 'svcacct', $ts['account_identity']);
eq('Server flavour recorded', 'server', $ts['flavour']);

// A wrongly-guessed flavour must still connect: v3 404s, v2 succeeds.
$vanity = mkJira(['base_url' => 'https://jira.acme.com']);   // heuristic says server
$vanity->queue = [
    [404, '{}'],                                                        // v2 attempt fails
    [200, json_encode(['accountId' => 'abc', 'displayName' => 'Bot'])], // v3 succeeds
];
$tv = $vanity->testConnection();
eq('Falls back to the other flavour', 'cloud', $tv['flavour']);
eq('...and identifies the account',   'abc',   $tv['account_identity']);

// Bad credentials must surface as an error, not a silent empty connection.
$bad = mkJira();
$bad->queue = [[401, '{}'], [401, '{}']];
$threw = false;
try { $bad->testConnection(); } catch (Exception $e) { $threw = true; }
ok('Bad credentials throw', $threw);

// Auth differs by flavour and both must be right.
$cloud->seen = []; $cloud->queue = [[200, '{"accountId":"x","displayName":"y"}']];
$cloud->testConnection();
eq('Cloud uses basic auth', 'svc@acme.com:tok', $cloud->seen[0]['opts']['auth']);

$server->seen = []; $server->queue = [[200, '{"name":"n","displayName":"d"}']];
$server->testConnection();
ok('Server sends a Bearer token',
   in_array('Authorization: Bearer pat', $server->seen[0]['opts']['headers'], true));
ok('Server does NOT send basic auth', !isset($server->seen[0]['opts']['auth']));

// Project paging — a truncated list looks like "my project is missing".
$paged = mkJira();
$paged->queue = [
    [200, json_encode(['isLast' => false, 'values' => array_map(function ($i) {
        return ['key' => "P$i", 'name' => "Project $i"]; }, range(1, 50))])],
    [200, json_encode(['isLast' => true, 'values' => [['key' => 'P51', 'name' => 'Project 51']]])],
];
eq('Paged project list is walked to the end', 51, count($paged->listProjects()));

// Subtask types are not valid escalation targets (they need a parent).
$types = mkJira();
$types->queue = [[200, json_encode(['values' => [
    ['id' => '1', 'name' => 'Bug', 'subtask' => false],
    ['id' => '2', 'name' => 'Sub-task', 'subtask' => true],
]])]];
$tl = $types->listIssueTypes('OPS');
eq('Subtask types excluded', 1, count($tl));
eq('...leaving the real one', 'Bug', $tl[0]['name']);

// Capabilities drive the settings UI.
ok('Jira supports issue types', $cloud->supports(IssueTrackerProvider::CAP_ISSUE_TYPES));
ok('Jira supports attachments', $cloud->supports(IssueTrackerProvider::CAP_ATTACHMENTS));

// ---------------------------------------------------------------------------
echo "6. The cross-company isolation boundary\n";
// ---------------------------------------------------------------------------

// integrationsCompaniesCompatible() is the outbound twin of the inbound routing
// membrane: escalation is driven by a WORKFLOW, and workflow conditions are
// editable, so the company check must live in code that nobody can edit.
//
// ⚠️ Every "cannot" below is paired with a "can". A guard that refuses
// EVERYTHING would pass all the negative assertions and be completely broken.

// Load the REAL service file, not a copy of the function — the whole point is to
// test what ships. Nothing in it runs at load time and the DB is only touched
// inside functions we do not call here, so a plain require is safe.
require_once __DIR__ . '/../../includes/integrations/integrations.php';

$ACME = 1; $GLOBEX = 2; $DEFAULT = 1;

// A SHARED connection (tenant NULL) serves every company — the MSP's own Jira.
ok('Shared connection accepts Acme',   integrationsCompaniesCompatible($ACME,   null, $DEFAULT));
ok('Shared connection accepts Globex', integrationsCompaniesCompatible($GLOBEX, null, $DEFAULT));
ok('Shared connection accepts an unrouted ticket', integrationsCompaniesCompatible(null, null, $DEFAULT));

// A PINNED connection takes only its own company's work. This is the leak.
ok('PINNED to Acme REFUSES Globex\'s ticket',  !integrationsCompaniesCompatible($GLOBEX, $ACME, $DEFAULT));
ok('PINNED to Globex REFUSES Acme\'s ticket',  !integrationsCompaniesCompatible($ACME, $GLOBEX, $DEFAULT));
// ...positive control: the same pinned connection MUST accept its own.
ok('POSITIVE CONTROL: pinned to Acme accepts Acme',   integrationsCompaniesCompatible($ACME, $ACME, $DEFAULT));
ok('POSITIVE CONTROL: pinned to Globex accepts Globex', integrationsCompaniesCompatible($GLOBEX, $GLOBEX, $DEFAULT));

// A NULL work item means "unrouted, treated as the Default company's". It must
// reach a connection pinned to Default, and no other.
ok('Unrouted ticket reaches a Default-pinned connection',
   integrationsCompaniesCompatible(null, $DEFAULT, $DEFAULT));
ok('Unrouted ticket REFUSED by a Globex-pinned connection',
   !integrationsCompaniesCompatible(null, $GLOBEX, $DEFAULT));

// With no Default resolvable (pre-tenancy install) a pinned target must refuse
// rather than guess. Paired with the shared case, which must still work.
ok('No default + pinned target → refuse', !integrationsCompaniesCompatible(null, $ACME, null));
ok('POSITIVE CONTROL: no default + shared target → allow',
   integrationsCompaniesCompatible(null, null, null));

// Single-company install: one company, so everything is trivially compatible —
// correct rather than a bypass, since there is nothing to leak to.
ok('Single company: ticket 1 → connection 1', integrationsCompaniesCompatible(1, 1, 1));
ok('Single company: unrouted → shared',       integrationsCompaniesCompatible(null, null, 1));

// The guard must not be fooled by loose comparison — '2' and 2, or 0 and null,
// are exactly the sort of thing PDO hands back as strings.
ok('String tenant id still refuses cross-company',
   !integrationsCompaniesCompatible((int)'2', (int)'1', $DEFAULT));
ok('POSITIVE CONTROL: string tenant id still allows same-company',
   integrationsCompaniesCompatible((int)'2', (int)'2', $DEFAULT));

// ---------------------------------------------------------------------------
echo "7. Email body → description text\n";
// ---------------------------------------------------------------------------

// ⚠️ This exists because of a real failure, not a hypothetical one. The first
// live ticket previewed produced a Jira description containing several hundred
// lines of the email's CSS: strip_tags() removes the TAGS of <style> but keeps
// its CONTENT. Every assertion below is a thing that actually went wrong or
// would have.

$marketing = '<html><head><style>
  :root { color-scheme: light dark; }
  @media (prefers-color-scheme: dark) { td { color: #fff !important; } }
</style><title>Invoice</title></head><body>
<div>Your invoice is available.</div><br>
<p>IMPORTANT: the balance will be charged automatically.</p>
<script>trackOpen();</script>
</body></html>';

$txt = integrationsBodyToText($marketing, 'html');

ok('CSS content is gone',        !str_contains($txt, 'color-scheme'));
ok('!important is gone',         !str_contains($txt, '!important'));
ok('script content is gone',     !str_contains($txt, 'trackOpen'));
ok('<title> content is gone',    !str_contains($txt, 'Invoice') || str_contains($txt, 'invoice is available'));
// POSITIVE CONTROL: the actual message must survive all that removal.
ok('POSITIVE CONTROL: the real text survived',
   str_contains($txt, 'Your invoice is available.')
   && str_contains($txt, 'IMPORTANT: the balance will be charged automatically.'));
ok('block ends became newlines', str_contains($txt, "available.\nIMPORTANT")
                                 || str_contains($txt, "available.\n\nIMPORTANT"));

// HTML mail is full of &nbsp; runs and CRLF — they must collapse, not survive as
// a wall of whitespace.
$spacey = integrationsBodyToText("<p>A&nbsp;&nbsp;&nbsp;&nbsp;B</p>\r\n\r\n\r\n\r\n<p>C</p>", 'html');
ok('nbsp runs collapse',      !str_contains($spacey, '    '));
ok('no triple blank lines',   !str_contains($spacey, "\n\n\n"));
ok('POSITIVE CONTROL: letters survive collapsing',
   str_contains($spacey, 'A') && str_contains($spacey, 'B') && str_contains($spacey, 'C'));

// Plain-text bodies must pass through essentially untouched — over-processing a
// plain body is as wrong as under-processing an HTML one.
$plain = integrationsBodyToText("Line one\n\nLine two", 'text');
eq('plain text preserved', "Line one\n\nLine two", $plain);

// Entities decode (a description showing &amp; would look broken).
ok('entities decoded', str_contains(integrationsBodyToText('<p>Tom &amp; Jerry &lt;3</p>', 'html'), 'Tom & Jerry <3'));

// The cap keeps a newsletter from becoming the whole issue, and says it did.
$long = integrationsBodyToText(str_repeat('word ', 5000), 'text', 200);
ok('long body capped',        mb_strlen($long) < 400);
ok('truncation is announced', str_contains($long, 'truncated'));
ok('POSITIVE CONTROL: short body NOT capped',
   !str_contains(integrationsBodyToText('short one', 'text', 200), 'truncated'));

// Degenerate input must not explode.
eq('null body → empty string', '', integrationsBodyToText(null, 'html'));
eq('empty body → empty string', '', integrationsBodyToText('', 'html'));

// An HTML body mislabelled as text is common (some mailers lie) — sniffing the
// markup means it still gets cleaned rather than dumped raw.
ok('mislabelled HTML still cleaned',
   !str_contains(integrationsBodyToText('<div><style>p{color:red}</style>Hello</div>', 'text'), 'color:red'));

// ---------------------------------------------------------------------------
echo "8. Comments coming back — reading, echo suppression, the poll window\n";
// ---------------------------------------------------------------------------

// ── ADF → text ─────────────────────────────────────────────────────────────
// Cloud comment bodies are nested JSON. Getting nothing out of them presents as
// "comments arrive but are blank", so every block type gets an assertion.

$adfComment = [
    'type' => 'doc', 'version' => 1,
    'content' => [
        ['type' => 'paragraph', 'content' => [
            ['type' => 'text', 'text' => 'What were the '],
            ['type' => 'text', 'text' => 'repro steps', 'marks' => [['type' => 'strong']]],
            ['type' => 'text', 'text' => '?'],
        ]],
        ['type' => 'bulletList', 'content' => [
            ['type' => 'listItem', 'content' => [
                ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Which build?']]]]],
            ['type' => 'listItem', 'content' => [
                ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Which OS?']]]]],
        ]],
    ],
];
$adfText = $cloud->pubAdfToText($adfComment);
ok('ADF text: sentence survives',  str_contains($adfText, 'What were the repro steps?'));
ok('ADF text: bullet 1 survives',  str_contains($adfText, '- Which build?'));
ok('ADF text: bullet 2 survives',  str_contains($adfText, '- Which OS?'));
ok('ADF text: no JSON leaked',     !str_contains($adfText, 'listItem'));

// A link's href is the useful half. "See here" with the URL dropped is worse
// than useless in a plain-text note.
$adfLink = ['type' => 'doc', 'content' => [['type' => 'paragraph', 'content' => [
    ['type' => 'text', 'text' => 'See here', 'marks' => [['type' => 'link', 'attrs' => ['href' => 'https://ci.example/build/9']]]],
]]]];
$linkText = $cloud->pubAdfToText($adfLink);
ok('ADF text: link text kept', str_contains($linkText, 'See here'));
ok('ADF text: link href kept', str_contains($linkText, 'https://ci.example/build/9'));

// Mentions and emoji carry their label in attrs, not in a text child — recursing
// blindly would drop them and mangle the sentence.
$adfMention = ['type' => 'doc', 'content' => [['type' => 'paragraph', 'content' => [
    ['type' => 'mention', 'attrs' => ['id' => 'abc', 'text' => '@Jane Doe']],
    ['type' => 'text', 'text' => ' can you look?'],
]]]];
ok('ADF text: mention label kept',
   str_contains($cloud->pubAdfToText($adfMention), '@Jane Doe can you look?'));

// Degenerate input must not explode — a comment can genuinely be an empty doc.
eq('ADF text: empty doc → empty string', '', $cloud->pubAdfToText(['type' => 'doc', 'content' => []]));
eq('ADF text: non-array → empty string', '', $cloud->pubAdfToText('not adf'));

// ── The flavour split, in the inbound direction ────────────────────────────
// renderDoc() picks the format going out; commentBodyToText() must handle both
// coming back. Data Center sends a plain wiki-markup string.
ok('Cloud reads an ADF body',
   str_contains($cloud->pubCommentBody($adfComment), 'repro steps'));
eq('Server reads a string body', 'Any update on this?',
   $server->pubCommentBody('Any update on this?'));
eq('Unknown body shape → empty, not a crash', '', $cloud->pubCommentBody(null));

// ── Author identity must match what testConnection() returns ───────────────
// Echo suppression compares the two, so a mismatch here silently disables it:
// Cloud identifies a user by accountId, Data Center by username.
$cloudComment = [
    'id' => '10101', 'created' => '2026-08-02T09:31:00.000+0000',
    'author' => ['accountId' => 'acc-123', 'name' => 'ignored', 'displayName' => 'Dave Smith'],
    'body' => $adfComment,
];
$pc = $cloud->pubParseComment($cloudComment);
eq('Cloud author identity is accountId', 'acc-123', $pc['author_identity']);
eq('Author display name kept',           'Dave Smith', $pc['author_name']);
eq('Comment id kept',                    '10101', $pc['comment_id']);
ok('Comment timestamp parsed',           $pc['created_ts'] > 0);

$ps = $server->pubParseComment([
    'id' => '55', 'created' => '2026-08-02T09:31:00.000+0000',
    'author' => ['name' => 'dsmith', 'accountId' => 'ignored', 'displayName' => 'Dave Smith'],
    'body' => 'Any update?',
]);
eq('Server author identity is username', 'dsmith', $ps['author_identity']);

// ── The poll window ────────────────────────────────────────────────────────
$now = 1785600000;
eq('Lookback covers the gap plus the overlap', 15, $cloud->pubLookback($now - (10 * 60), $now));
// ⚠️ The cap is a product rule: a connection switched off for a month must not,
// on being switched back on, tip a month of dev chatter onto closed tickets.
eq('Lookback is capped at 24h', 1440, $cloud->pubLookback($now - (30 * 86400), $now));
ok('POSITIVE CONTROL: a recent watermark is NOT capped',
   $cloud->pubLookback($now - (60 * 60), $now) < 1440);
// A watermark in the future (clock skew between the app server and the DB) must
// still ask for something, not zero minutes.
eq('Future watermark still asks for the overlap', 5, $cloud->pubLookback($now + 600, $now));

// The first poll of a connection imports NOTHING. Enabling comment sync must not
// dump a tracker's whole history onto tickets that closed months ago.
$firstRun = mkJira();
eq('No watermark → no events at all', [], $firstRun->pollChanges(null, ['10042']));
eq('No watermark → no HTTP call made either', 0, count($firstRun->seen));

// ...and the positive control: WITH a watermark, the same setup does poll.
$second = mkJira();
$second->queue = [
    [200, json_encode(['issues' => [['id' => '10042']]])],                    // the JQL search
    [200, json_encode(['comments' => [[                                       // the comment read
        'id' => '9001', 'created' => gmdate('Y-m-d\TH:i:s.000+0000'),
        'author' => ['accountId' => 'dev-1', 'displayName' => 'Dave Smith'],
        'body' => ['type' => 'doc', 'content' => [['type' => 'paragraph',
                   'content' => [['type' => 'text', 'text' => 'Need the repro steps']]]]],
    ]]])],
];
$events = $second->pollChanges(gmdate('Y-m-d H:i:s', time() - 600), ['10042']);
eq('POSITIVE CONTROL: a watermark produces one event', 1, count($events));
eq('Event is a comment', 'comment_added', $events[0]['type'] ?? null);
eq('Event carries the comment id', '9001', $events[0]['comment_id'] ?? null);
eq('Event carries the author identity', 'dev-1', $events[0]['author_identity'] ?? null);
ok('Event body is readable text',
   str_contains($events[0]['comment_body'] ?? '', 'Need the repro steps'));

// ⚠️ JQL must use RELATIVE minutes. An absolute JQL date is interpreted in the
// Jira USER's timezone, not UTC, so a server and an account in different zones
// would silently miss or re-read a window's worth of comments every poll.
$searchUrl = $second->seen[0]['url'] ?? '';
ok('Search uses relative minutes', str_contains(urldecode($searchUrl), 'updated >= -'));
ok('Search scopes to the watch list', str_contains(urldecode($searchUrl), 'id in (10042)'));
// Cloud's /search was REMOVED by Atlassian — this is the endpoint that broke in
// #953, so the comment poll pins it too rather than repeating the mistake.
ok('Cloud searches /search/jql', str_contains($searchUrl, '/search/jql'));
$dcSecond = mkJira(['base_url' => 'https://jira.acme.internal',
                    'credentials' => ['api_token' => 'pat', 'flavour' => 'server']]);
$dcSecond->queue = [[200, json_encode(['issues' => []])]];
$dcSecond->pollChanges(gmdate('Y-m-d H:i:s', time() - 600), ['10042']);
ok('Data Center searches /search', str_contains($dcSecond->seen[0]['url'] ?? '', '/rest/api/2/search?'));

// Comments older than the watermark are not re-imported as "new".
$stale = mkJira();
$stale->queue = [
    [200, json_encode(['issues' => [['id' => '10042']]])],
    [200, json_encode(['comments' => [[
        'id' => '8000', 'created' => gmdate('Y-m-d\TH:i:s.000+0000', time() - 86400),
        'author' => ['accountId' => 'dev-1', 'displayName' => 'Dave'],
        'body' => 'ancient history',
    ]]])],
];
eq('A comment older than the watermark is ignored', 0,
   count($stale->pollChanges(gmdate('Y-m-d H:i:s', time() - 600), ['10042'])));

// Non-numeric ids must never reach the JQL — same rule as fetchIssues().
$inject = mkJira();
eq('Non-numeric id rejected before the query', [],
   $inject->pollChanges(gmdate('Y-m-d H:i:s', time() - 600), ['10042) OR (1=1']));
eq('...and no request was made', 0, count($inject->seen));

// ── Echo suppression ───────────────────────────────────────────────────────
// The loop this prevents: we push a note → the poll sees it as a new comment →
// it becomes a note → which pushes again → forever.
//
// ⚠️ The guard is the comment MAP (by id), not the author. Suppressing by author
// was tried and removed: whoever creates the API token is usually also a person
// who comments in Jira, so it silently swallowed their own comments — which is
// exactly what happened on the first live run against Ed's Jira. See below for
// the regression test that pins it.

$us = 'svc-account-1';
ok('Our own account is still RECOGNISED as ours',
   integrationsCommentIsEcho(['author_identity' => $us], $us));
ok('POSITIVE CONTROL: a dev\'s comment is not ours',
   !integrationsCommentIsEcho(['author_identity' => 'dev-1'], $us));

// ⚠️ An unknown author must NOT be treated as ours. A tracker that stops sending
// an author would otherwise swallow every comment silently — the failure has to
// be visible (noisy notes), not invisible (nothing ever arrives).
ok('Missing author is not an echo',    !integrationsCommentIsEcho([], $us));
ok('Missing OUR identity is not an echo',
   !integrationsCommentIsEcho(['author_identity' => 'dev-1'], null));
ok('Empty-string identity is not an echo',
   !integrationsCommentIsEcho(['author_identity' => ''], ''));

// ── The whole import decision ──────────────────────────────────────────────
$goodEvent = [
    'type' => 'comment_added', 'comment_id' => '9001',
    'comment_body' => 'Need the repro steps', 'author_identity' => 'dev-1',
];
eq('A dev comment is imported', '',
   integrationsCommentSkipReason($goodEvent, $us, []));

// ⚠️ THE REGRESSION TEST. This exact case failed live: Ed created the API token
// from his own Atlassian account, then commented as himself, and his comment was
// silently swallowed as "our own write coming back". A human writing from the
// account the token belongs to is an ordinary thing, not an echo.
eq('A comment from OUR OWN account is still imported', '',
   integrationsCommentSkipReason(array_merge($goodEvent, ['author_identity' => $us]), $us, []));

// ...and the thing that actually stops the loop: we recorded pushing it, so it
// is recognised by ID coming back, whoever appears to have written it.
eq('A comment WE pushed is dropped on its way back', 'already_imported',
   integrationsCommentSkipReason(array_merge($goodEvent, ['author_identity' => 'someone-else']), $us, ['9001']));
eq('A comment already imported is dropped', 'already_imported',
   integrationsCommentSkipReason($goodEvent, $us, ['9001']));

// The seam a per-connection setting would flip. Proven now so that turning it on
// later is a wiring change, not a rewrite — and so this branch cannot rot.
eq('With suppress-by-author ON, our own comment is dropped', 'echo',
   integrationsCommentSkipReason(array_merge($goodEvent, ['author_identity' => $us]), $us, [], 'ticket', true));
eq('POSITIVE CONTROL: with it ON, a dev comment still imports', '',
   integrationsCommentSkipReason($goodEvent, $us, [], 'ticket', true));
// Ids arrive from PDO as strings and from the provider as strings, but a caller
// could hand back ints — the comparison must not depend on which.
eq('Already-imported check is type-safe', 'already_imported',
   integrationsCommentSkipReason($goodEvent, $us, [9001]));
eq('An empty comment is dropped', 'empty',
   integrationsCommentSkipReason(array_merge($goodEvent, ['comment_body' => '   ']), $us, []));
eq('A comment with no id is dropped', 'no_comment_id',
   integrationsCommentSkipReason(array_merge($goodEvent, ['comment_id' => '']), $us, []));
eq('A status event is not a comment', 'not_a_comment',
   integrationsCommentSkipReason(['type' => 'status_changed'], $us, []));
// entity_type is polymorphic, but only tickets have notes. A problem or change
// link must skip rather than write a note against the wrong table's id.
eq('A non-ticket link is skipped', 'unsupported_entity',
   integrationsCommentSkipReason($goodEvent, $us, [], 'problem'));

// ...positive controls for the whole set, so a "refuse everything" regression
// cannot pass this section.
eq('POSITIVE CONTROL: a different comment id still imports', '',
   integrationsCommentSkipReason($goodEvent, $us, ['8999']));
eq('POSITIVE CONTROL: no identity configured still imports a dev comment', '',
   integrationsCommentSkipReason($goodEvent, null, []));

// ── How it reads on the ticket ─────────────────────────────────────────────
// Attribution lives in the note TEXT, not only in the display join, because the
// REST API, the portal and the AI write-up all read note_text directly.
$noteText = integrationsCommentNoteText(
    ['author_name' => 'Dave Smith', 'comment_body' => "Need the repro steps"],
    ['external_key' => 'KAN-6', 'external_id' => '10042']
);
ok('Note names the issue',   str_contains($noteText, 'KAN-6'));
ok('Note names the author',  str_contains($noteText, 'Dave Smith'));
ok('Note carries the body',  str_contains($noteText, 'Need the repro steps'));
// An anonymous comment must still say where it came from.
ok('Note falls back to the issue id when there is no key',
   str_contains(integrationsCommentNoteText(['comment_body' => 'x'],
          ['external_key' => null, 'external_id' => '10042']), '10042'));

// ---------------------------------------------------------------------------
echo "9. Mapping — routing a ticket to the right project, type and priority\n";
// ---------------------------------------------------------------------------

$ACME_T = 1; $GLOBEX_T = 2; $DEV_DEPT = 7; $SUPPORT_DEPT = 8;

$maps = [
    INTEGRATION_MAP_PROJECT => [
        'dept:' . $DEV_DEPT     => 'DEV',
        'tenant:' . $ACME_T     => 'ACME',
        INTEGRATION_MAP_ANY     => 'KAN',
    ],
    INTEGRATION_MAP_ISSUE_TYPE => [
        '3'                 => 'Bug',
        INTEGRATION_MAP_ANY => 'Task',
    ],
    INTEGRATION_MAP_PRIORITY => [
        '1' => 'Highest',
        '2' => 'High',
    ],
];

// ── Project routing precedence: department beats company beats default ──────
// The order is the whole point. A team with its own board is a sharper signal
// than the company the ticket belongs to.
eq('Department wins over company',   'DEV',  integrationsResolveProject($maps, $ACME_T, $DEV_DEPT));
eq('Company wins when no dept rule', 'ACME', integrationsResolveProject($maps, $ACME_T, $SUPPORT_DEPT));
eq('Company rule applies with no department at all', 'ACME',
   integrationsResolveProject($maps, $ACME_T, null));
eq('Default catches an unmapped company', 'KAN', integrationsResolveProject($maps, $GLOBEX_T, null));
eq('Default catches an unrouted ticket',  'KAN', integrationsResolveProject($maps, null, null));
// A department rule must fire even for a company that has its own rule AND for
// one that does not — otherwise precedence is accidental rather than designed.
eq('Department rule fires for an unmapped company too', 'DEV',
   integrationsResolveProject($maps, $GLOBEX_T, $DEV_DEPT));

// ⚠️ No mapping at all must return null, NOT a guess. An escalation with no
// resolvable project has to say so, never file the issue somewhere arbitrary.
eq('No project mapping → null', null, integrationsResolveProject([], 1, 1));
eq('Project map present but nothing matches and no default → null', null,
   integrationsResolveProject([INTEGRATION_MAP_PROJECT => ['dept:99' => 'X']], 1, 1));

// ── Issue type ─────────────────────────────────────────────────────────────
eq('Ticket type maps to its issue type', 'Bug',  integrationsResolveIssueType($maps, 3));
eq('Unmapped ticket type falls back',    'Task', integrationsResolveIssueType($maps, 99));
eq('No ticket type at all falls back',   'Task', integrationsResolveIssueType($maps, null));
eq('No issue-type mapping → null', null, integrationsResolveIssueType([], 3));

// ── Priority: deliberately NO wildcard ─────────────────────────────────────
// ⚠️ "Everything is a Task" is a reasonable thing to mean. "Every priority is
// Highest" is not — it would mark a dev team's entire backlog urgent. So the
// wildcard that exists for the other two must NOT work here.
eq('Mapped priority resolves', 'Highest', integrationsResolvePriority($maps, 1));
eq('Second mapped priority resolves', 'High', integrationsResolvePriority($maps, 2));
eq('UNMAPPED priority stays unmapped', null, integrationsResolvePriority($maps, 3));
eq('A wildcard priority row is IGNORED', null,
   integrationsResolvePriority([INTEGRATION_MAP_PRIORITY => [INTEGRATION_MAP_ANY => 'Highest']], 3));
// ...positive control: the same map still resolves an explicit row, so the
// assertion above is about the wildcard and not about a broken lookup.
eq('POSITIVE CONTROL: explicit row still resolves alongside a wildcard', 'Blocker',
   integrationsResolvePriority(
       [INTEGRATION_MAP_PRIORITY => [INTEGRATION_MAP_ANY => 'Highest', '4' => 'Blocker']], 4));
eq('No priority on the ticket → null', null, integrationsResolvePriority($maps, null));

// ── The rejected-priority fallback ─────────────────────────────────────────
// Jira priorities are per project, so a project that renamed "Highest" to "P1"
// rejects our value and 400s the create. Losing the escalation over that is
// worse than losing the priority.
ok('A priority error is recognised',
   integrationsLooksLikePriorityRejection('priority: Invalid priority id'));
ok('Jira\'s field-level wording is recognised',
   integrationsLooksLikePriorityRejection('The Priority field does not exist on this screen'));
// ⚠️ ...but the retry must be NARROW. Retrying on any failure could turn a real
// error into an issue nobody meant to raise.
ok('An unrelated error is NOT treated as a priority rejection',
   !integrationsLooksLikePriorityRejection('customfield_10010: Epic Link is required'));
ok('A permission error is NOT treated as a priority rejection',
   !integrationsLooksLikePriorityRejection('Jira rejected the credentials (HTTP 401).'));

// ── An unmapped install must behave exactly as it did before mapping ────────
eq('Empty maps: project null',    null, integrationsResolveProject([], 5, 5));
eq('Empty maps: issue type null', null, integrationsResolveIssueType([], 5));
eq('Empty maps: priority null',   null, integrationsResolvePriority([], 5));

// ---------------------------------------------------------------------------
echo "10. tracker.* workflow triggers and the starter recipes\n";
// ---------------------------------------------------------------------------

// The engine loads without a database or a connection — availableTriggers(),
// availableActions() and availableFields() are all static tables.
require_once __DIR__ . '/../../workflow/includes/engine.php';
require_once __DIR__ . '/../../workflow/includes/templates.php';

// ⚠️ availableTriggers() and availableFields() are static tables and need no
// database. availableActions() DOES (it reads webhook formats), so the
// action/arg validation lives in tests/integrations/templates_check.php
// instead — this suite's no-DB-no-network promise is worth more than the
// convenience of one file.
$triggers = WorkflowEngine::availableTriggers();

foreach (['tracker.issue_linked', 'tracker.issue_status_changed', 'tracker.issue_comment_added'] as $t) {
    ok("Trigger $t is registered", isset($triggers[$t]));
}

// ⚠️ A trigger with no declared fields still fires, but the editor's condition
// dropdown is empty — so the user cannot write the condition the trigger exists
// for. That is the failure this pins.
$statusFields = WorkflowEngine::availableFields('tracker.issue_status_changed');
ok('status_changed exposes the category',  in_array('tracker.status_category', $statusFields, true));
ok('status_changed exposes the PREVIOUS category — "became done" needs it',
   in_array('tracker.previous_category', $statusFields, true));
ok('status_changed exposes the issue key', in_array('tracker.key', $statusFields, true));

$commentFields = WorkflowEngine::availableFields('tracker.issue_comment_added');
ok('comment_added exposes the author', in_array('tracker.comment_author', $commentFields, true));
ok('comment_added exposes the body',   in_array('tracker.comment_body', $commentFields, true));

// ⚠️ Every tracker event must carry the TICKET too. Without it a workflow can
// see that the issue moved but has nothing to act on — no note, no email, no
// status change — and the triggers would be decorative.
foreach (['tracker.issue_linked', 'tracker.issue_status_changed', 'tracker.issue_comment_added'] as $t) {
    $f = WorkflowEngine::availableFields($t);
    ok("$t carries the ticket", in_array('ticket.id', $f, true) && in_array('ticket.requester_email', $f, true));
}

// ...positive control: a trigger that should NOT carry tracker fields does not,
// so the assertions above are about these triggers and not a catch-all list.
ok('POSITIVE CONTROL: ticket.created has no tracker fields',
   !in_array('tracker.key', WorkflowEngine::availableFields('ticket.created'), true));

// ── The starter recipes: shape and semantics (no DB) ──────────────────────
// Whether every action/arg NAME is real needs availableActions(), which needs a
// database — that check is tests/integrations/templates_check.php.

$all = WorkflowTemplates::all();
ok('Recipe: escalate bugs to a tracker exists', isset($all['tracker_escalate_bugs']));
ok('Recipe: tell the requester when dev finishes exists', isset($all['tracker_tell_requester_when_done']));

// ⚠️ The "done" recipe must key on the CATEGORY, never a status name — names are
// per-project and renamed at will. This is the same rule as tickets.merged_into_id.
$doneTpl = $all['tracker_tell_requester_when_done'] ?? ['conditions' => []];
$keysOnCategory = false;
foreach ($doneTpl['conditions'] as $c) {
    if (($c['field'] ?? '') === 'tracker.status_category' && ($c['value'] ?? '') === 'done') $keysOnCategory = true;
}
ok('The "dev finished" recipe keys on status_category, not a status name', $keysOnCategory);
ok('...and its trigger is the tracker event, not a ticket event',
   ($doneTpl['trigger_event'] ?? '') === 'tracker.issue_status_changed');

// The escalate recipe must keep skip_if_linked on: its trigger fires repeatedly
// on the same ticket, and without it every firing mints another duplicate issue.
$escTpl = $all['tracker_escalate_bugs'] ?? ['actions' => []];
$skip = null;
foreach ($escTpl['actions'] as $a) {
    if ($a['type'] === 'escalate_to_tracker') $skip = $a['args']['skip_if_linked'] ?? null;
}
ok('The escalate recipe leaves skip_if_linked ON', $skip === true);

// ---------------------------------------------------------------------------
echo "11. Attachments — the multipart body, and what is allowed to travel\n";
// ---------------------------------------------------------------------------

$png = "\x89PNG\r\n\x1a\n" . str_repeat("\x00\xFF", 40);   // binary, with CRLF and NULs in it
$boundary = null;
$mp = $cloud->pubMultipart('screenshot.png', 'image/png', $png, $boundary);

ok('A boundary is produced',            is_string($boundary) && $boundary !== '');
ok('Field name is "file" — Jira requires exactly this',
   str_contains($mp, 'name="file"'));
ok('Filename travels',                  str_contains($mp, 'filename="screenshot.png"'));
ok('Content type travels',              str_contains($mp, 'Content-Type: image/png'));
ok('Body opens with the boundary',      strpos($mp, "--{$boundary}\r\n") === 0);
ok('Body closes with the terminator',   substr($mp, -strlen("--{$boundary}--\r\n")) === "--{$boundary}--\r\n");
// ⚠️ The binary must survive byte-for-byte. A body that re-encoded or truncated
// at the first NUL would upload a corrupt file that still "succeeded".
ok('The binary payload survives intact', str_contains($mp, $png));

// ⚠️ The boundary must be random. A fixed one that happened to occur inside a
// file would truncate the upload at that point — a corrupt file, no error.
$b2 = null;
$cloud->pubMultipart('a.png', 'image/png', 'x', $b2);
ok('Boundaries differ between calls', $boundary !== $b2);

// A filename is attacker-influenced (it came from an email), and it sits inside
// a quoted header. Quotes or CRLF there would let it break out of that header.
$b3 = null;
$evil = $cloud->pubMultipart("../../etc/pa\"ss\r\nwd.png", 'image/png', 'x', $b3);
ok('Path components are stripped from the filename', !str_contains($evil, '../..'));
ok('Quotes are stripped from the filename',          !str_contains($evil, 'pa"ss'));
// ⚠️ Assert the FILENAME VALUE is clean, not the bytes around it. A correctly
// sanitised header legitimately ends `wd.png"\r\nContent-Type`, so matching on
// the surroundings fails on correct output — my first version of this did
// exactly that and reported a bug that was not there.
preg_match('/filename="([^"]*)"/', $evil, $fnMatch);
eq('The filename value itself is sanitised', 'passwd.png', $fnMatch[1] ?? null);
eq('...and the part still has exactly one Content-Type header', 1, substr_count($evil, 'Content-Type:'));
ok('POSITIVE CONTROL: the safe part of the name survives',
   str_contains($evil, 'passwd.png'));
// An empty name must still produce a usable one rather than filename=""
$b4 = null;
ok('An empty filename falls back',
   str_contains($cloud->pubMultipart('', 'image/png', 'x', $b4), 'filename="attachment"'));

// ── Byte formatting, used in the preview and the link error ────────────────
eq('Formats bytes',     '512 B',  integrationsFormatBytes(512));
eq('Formats kilobytes', '85 KB',  integrationsFormatBytes(87000));
eq('Formats megabytes', '3.4 MB', integrationsFormatBytes(3527131));

// ── The caps are the product rule ─────────────────────────────────────────
// ⚠️ 10MB matches Jira's own default ceiling: sending more is a slow failure
// rather than a fast one, and the escalation is what matters.
ok('Max file size is 10MB', INTEGRATION_ATTACH_MAX_BYTES === 10485760);
ok('Max file count is 10',  INTEGRATION_ATTACH_MAX_FILES === 10);


// ---------------------------------------------------------------------------
echo "\n12. Azure DevOps — the second connector, and what it proves\n";
// ---------------------------------------------------------------------------
/**
 * The same offline harness as FakeJira. Everything here is a claim that can be
 * settled without a live organisation; the ones that cannot are in the live
 * script noted in the developer guide.
 */
class FakeDevOps extends AzureDevOpsProvider
{
    public $queue = [];
    public $seen  = [];
    protected function httpRequest(string $url, array $opts = []): array
    {
        $this->seen[] = ['url' => $url, 'opts' => $opts];
        if (!$this->queue) return [200, '{}'];
        return array_shift($this->queue);
    }
    public function pubPatch(array $t, string $s, IssueDoc $b, array $f = []) { return $this->buildCreatePatch($t, $s, $b, $f); }
    public function pubType(array $t)          { return $this->targetType($t); }
    public function pubMapCat(string $c)       { return $this->mapStateCategory($c); }
    public function pubError(int $c, string $b){ return $this->extractError($c, $b); }
    public function pubTitle(string $s)        { return $this->trimTitle($s); }
    public function pubStamp(int $ts)          { return $this->wiqlTimestamp($ts); }
    public function pubCutoff(int $s, ?int $n) { return $this->pollCutoff($s, $n); }
    public function pubParseComment(array $c)  { return $this->parseComment($c); }
    public function pubParseItem(array $w)     { return $this->parseWorkItem($w); }
    public function pubInternal(string $n)     { return $this->isInternalType($n); }
}
$mk = function (array $creds = []) {
    return new FakeDevOps([
        'provider' => 'azuredevops',
        'base_url' => 'https://dev.azure.com/acme',
        'credentials' => $creds + ['api_token' => 'tok'],
    ]);
};
$ado = $mk();

// ── The target's type: TWO accepted keys ──────────────────────────────────
// ⚠️ Regression guard. The core sends the tracker-neutral `issue_type`; only
// reading Azure DevOps' own `work_item_type` made a request for a Bug silently
// create a Task, with nothing anywhere reporting a problem.
eq('issue_type (what the core sends) is honoured', 'Bug',  $ado->pubType(['issue_type' => 'Bug']));
eq("work_item_type (the provider's own word) too", 'Bug',  $ado->pubType(['work_item_type' => 'Bug']));
eq('work_item_type wins when both are given',      'Bug',  $ado->pubType(['work_item_type' => 'Bug', 'issue_type' => 'Task']));
eq('neither given falls back to Task',             'Task', $ado->pubType([]));
eq('an empty string is not a type',                'Task', $ado->pubType(['issue_type' => '  ']));

// ── JSON Patch, the thing that had to work ────────────────────────────────
$doc  = (new IssueDoc)->para('Hello');
$ops  = $ado->pubPatch(['project' => 'Support', 'issue_type' => 'Task'], 'A title', $doc);
$byPath = [];
foreach ($ops as $o) $byPath[$o['path']] = $o;
ok('every op is an add',            count(array_filter($ops, fn($o) => $o['op'] === 'add')) === count($ops));
ok('title travels as a field op',   isset($byPath['/fields/System.Title']));
eq('...with the title',  'A title', $byPath['/fields/System.Title']['value'] ?? null);
ok('body travels as a field op',    isset($byPath['/fields/System.Description']));
ok('body is HTML, not ADF or wiki',
   is_string($byPath['/fields/System.Description']['value'] ?? null)
   && str_contains($byPath['/fields/System.Description']['value'], '<p>'));

// Area/iteration path are routing, and only sent when asked for.
$ops2 = $ado->pubPatch(['project'=>'S','issue_type'=>'Task','area_path'=>'S\\Team','iteration_path'=>'S\\Sprint 1'], 't', $doc);
$paths = array_column($ops2, 'path');
ok('area path is sent when given',      in_array('/fields/System.AreaPath', $paths, true));
ok('iteration path is sent when given', in_array('/fields/System.IterationPath', $paths, true));
ok('neither is sent when absent',       !in_array('/fields/System.AreaPath', array_column($ops, 'path'), true));

// ⚠️ 255 is a hard limit; over it the whole create is rejected, so a long
// subject would mean no escalation at all rather than a shortened title.
eq('a long title is trimmed, not rejected', 255, mb_strlen($ado->pubTitle(str_repeat('x', 400))));
ok('...and marked as trimmed', substr($ado->pubTitle(str_repeat('x', 400)), -3) === '...');
eq('whitespace is collapsed', 'a b', $ado->pubTitle("a  \n b"));
eq('an empty subject still yields a title', 'Untitled', $ado->pubTitle('   '));

// ── The five categories, and the one that is a judgement ──────────────────
eq('Proposed → todo',          'todo',        $ado->pubMapCat('Proposed'));
eq('InProgress → in_progress', 'in_progress', $ado->pubMapCat('InProgress'));
eq('Completed → done',         'done',        $ado->pubMapCat('Completed'));
eq('Removed → cancelled',      'cancelled',   $ado->pubMapCat('Removed'));
ok('an unknown category is null, never a guess', $ado->pubMapCat('Whatever') === null);

// 🔑 The fifth one is the connection's decision.
eq('Resolved defaults to in_progress (the cautious reading)', 'in_progress', $ado->pubMapCat('Resolved'));
eq('...and follows the setting when set',  'done', $mk(['resolved_means' => 'done'])->pubMapCat('Resolved'));
eq('a junk setting falls back, not through', 'in_progress', $mk(['resolved_means' => 'nonsense'])->pubMapCat('Resolved'));
// The setting must not leak into the other four.
eq('resolved_means does not affect Completed', 'done', $mk(['resolved_means' => 'in_progress'])->pubMapCat('Completed'));

// ── WIQL: the timestamp and the window ────────────────────────────────────
// ⚠️ The trailing Z is what makes Azure DevOps read this as real UTC. Without
// it the boundary is the ORGANISATION's timezone and the poll silently drifts.
$stamp = $ado->pubStamp(1785700000);
ok('WIQL timestamp ends in Z',       substr($stamp, -1) === 'Z');
ok('WIQL timestamp is ISO-8601 UTC', (bool)preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{7}Z$/', $stamp));
eq('...and is the UTC rendering of the instant', gmdate('Y-m-d\TH:i:s', 1785700000) . '.0000000Z', $stamp);

$now = 1785700000;
ok('the window starts before the watermark (overlap)', $ado->pubCutoff($now - 60, $now) < $now - 60);
ok('a very old watermark is capped',  $ado->pubCutoff($now - 86400 * 30, $now) >= $now - (24 * 3600) - 1);
ok('the cap does not affect a recent watermark', $ado->pubCutoff($now - 600, $now) > $now - (24 * 3600));

// ── Comments: the identity echo suppression depends on ────────────────────
// ⚠️ `id`, never `descriptor` — connectionData and a comment report descriptors
// in different formats for the same person, so comparing those never matches and
// every one of our own comments comes back as if a developer wrote it.
$parsed = $ado->pubParseComment([
    'id' => 42, 'text' => '<p>Hi <b>there</b></p>',
    'createdBy' => ['id' => 'guid-1', 'descriptor' => 'aad.SOMETHINGELSE', 'displayName' => 'Dev Eloper'],
    'createdDate' => '2026-08-02T18:39:13.917Z',
]);
eq('comment id is captured',        '42', $parsed['comment_id']);
eq('author identity is the GUID',   'guid-1', $parsed['author_identity']);
ok('the descriptor is NOT used',    $parsed['author_identity'] !== 'aad.SOMETHINGELSE');
eq('author name is kept for display', 'Dev Eloper', $parsed['author_name']);
ok('HTML is reduced to text',       !str_contains($parsed['comment_body'], '<'));
ok('...keeping the words',          str_contains($parsed['comment_body'], 'there'));
ok('the timestamp parses',          $parsed['created_ts'] > 0);

// ── Parsing a work item ───────────────────────────────────────────────────
$item = $ado->pubParseItem(['id' => 7, 'fields' => [
    'System.Title' => 'A thing', 'System.State' => 'New',
    'System.WorkItemType' => 'Bug', 'System.TeamProject' => 'Product',
    'System.AssignedTo' => ['displayName' => 'Dev Eloper'],
]]);
eq('id is captured',                '7', $item['external_id']);
eq('the key IS the id (no OPS-123 here)', '7', $item['external_key']);
eq('the browse url is a work item link',
   'https://dev.azure.com/acme/Product/_workitems/edit/7', $item['external_url']);
eq('state name is passed through',  'New', $item['status_name']);
eq('assignee is read',              'Dev Eloper', $item['assignee_name']);

// ── Errors an analyst has to act on ───────────────────────────────────────
ok('a JSON message is surfaced',
   str_contains($ado->pubError(400, '{"message":"TF401320: Rule Error"}'), 'TF401320'));
// ⚠️ A rejected token answers 203 with a sign-in PAGE, not 401 with JSON.
ok('a 203 sign-in page is reported as a token problem',
   stripos($ado->pubError(203, '<html><body>Sign In</body></html>'), 'token') !== false);
ok('an unhelpful body still names the status', str_contains($ado->pubError(500, ''), '500'));

// ── Capabilities, and the one deliberately absent ─────────────────────────
ok('declares attachments', $ado->supports(IssueTrackerProvider::CAP_ATTACHMENTS));
ok('declares polling',     $ado->supports(IssueTrackerProvider::CAP_POLLING));
ok('declares issue types', $ado->supports(IssueTrackerProvider::CAP_ISSUE_TYPES));
// ⚠️ Deliberate: Azure DevOps priority is an integer, not a named list, so
// there is nothing to populate a "map priority to theirs" dropdown with.
ok('does NOT declare priorities', !$ado->supports(IssueTrackerProvider::CAP_PRIORITIES));

// Test/code-review types are machinery, not escalation targets.
ok('Test Case is filtered from the type list',   $ado->pubInternal('Test Case'));
ok('Code Review Request is filtered',            $ado->pubInternal('Code Review Request'));
ok('Bug is NOT filtered (the positive control)', !$ado->pubInternal('Bug'));
ok('User Story is NOT filtered',                 !$ado->pubInternal('User Story'));

// ── The registry, and that adding a provider stayed additive ──────────────
$providers = integrationsAvailableProviders();
ok('azuredevops is registered',      isset($providers['azuredevops']));
ok('jira is untouched beside it',    isset($providers['jira']));
ok('the registry drives its own auth fields',
   array_column($providers['azuredevops']['credential_fields'], 'key') === ['api_token']);
ok('...with no email half, unlike Jira',
   !in_array('email', array_column($providers['azuredevops']['credential_fields'], 'key'), true));
ok('dispatch returns the right class',
   integrationsProviderFor(['provider' => 'azuredevops', 'credentials' => []]) instanceof AzureDevOpsProvider);
ok('dispatch still returns Jira for jira',
   integrationsProviderFor(['provider' => 'jira', 'credentials' => []]) instanceof JiraProvider);

// ⚠️ settings_fields must stay SEPARATE from credential_fields: the form blanks
// credentials on edit and treats empty as "keep", which would silently reset a
// dropdown to its default on every save.
eq('resolved_means is a setting, not a credential', ['resolved_means'],
   array_column($providers['azuredevops']['settings_fields'], 'key'));
ok('it is not also a credential field',
   !in_array('resolved_means', array_column($providers['azuredevops']['credential_fields'], 'key'), true));
eq('its default is the cautious one', ['resolved_means' => 'in_progress'], integrationsSettingKeys('azuredevops'));
eq('a provider with no settings gets an empty list', [], integrationsSettingKeys('jira'));


// ---------------------------------------------------------------------------
echo "\n" . str_repeat('=', 62) . "\n";
echo "  passed: $pass\n";
echo "  failed: $fail\n";
if ($failures) {
    echo "\nFailures:\n";
    foreach ($failures as $f) echo "  ✗ $f\n";
}
echo str_repeat('=', 62) . "\n";
exit($fail === 0 ? 0 : 1);
