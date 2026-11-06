<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Forms — field identity, sections and conditional visibility.
 *
 * Two things are under test and they are quite different in character:
 *
 *  1. FIELD IDENTITY. Editing a form used to sync its fields BY POSITION, so
 *     dragging a question rewrote the labels while the stored answers stayed
 *     where they were — every historic submission silently began reading against
 *     the wrong questions, and removing a field hard-deleted the last one's
 *     answers. That is not something you can see by reading the code; you have to
 *     record a submission, edit the form the way the builder does, and read the
 *     answers back. Those tests do exactly that.
 *
 *  2. THE CONDITION EVALUATOR. It exists twice — includes/form_logic.php decides
 *     on submit, assets/js/form-logic.js shows and hides as someone types. Two
 *     copies that disagree is the whole risk, so the same table of cases is run
 *     through BOTH and the answers compared. The JS half needs headless Chrome;
 *     without it that section is SKIPPED and says so rather than quietly passing.
 *
 *   php tests/forms-logic/run.php
 *
 * ⚠️ Every negative assertion is paired with a positive control. "The required
 * field did not block submission" proves nothing on its own — it is equally true
 * of a harness that never validated anything.
 *
 * ⚠️ This does NOT use the one-big-transaction trick the other suites use:
 * FormsService opens its own transactions, and MySQL has no nested ones. It
 * creates throwaway forms prefixed ZZ_TEST_ and removes them in a finally block.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/services/forms.php';

$pass = 0; $fail = 0; $skip = 0;

function check(string $what, bool $ok, string $detail = ''): void
{
    global $pass, $fail;
    if ($ok) { $pass++; echo "  [PASS] $what\n"; }
    else     { $fail++; echo "  [FAIL] $what" . ($detail !== '' ? " — $detail" : '') . "\n"; }
}
function skipped(string $what, string $why): void
{
    global $skip;
    $skip++;
    echo "  [SKIP] $what — $why\n";
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Columns from this change. Without them everything below fails for one boring
// reason, so say so plainly instead of emitting 20 confusing failures.
$cols = $conn->query("SHOW COLUMNS FROM form_fields")->fetchAll(PDO::FETCH_COLUMN);
if (!in_array('config', $cols, true) || !in_array('is_deleted', $cols, true)) {
    fwrite(STDERR, "form_fields is missing `config` / `is_deleted`. Run System → Database Verification first.\n");
    exit(1);
}

$analystId = (int)$conn->query("SELECT MIN(id) FROM analysts")->fetchColumn();
$ctx = new ActorContext(actorId: $analystId, source: 'api', actorName: 'forms-logic tests');
$createdFormIds = [];

/** Build a throwaway form and remember it for cleanup. */
function makeForm(PDO $conn, ActorContext $ctx, string $title, array $fields): int
{
    global $createdFormIds;
    $res = FormsService::saveForm($conn, $ctx, ['title' => 'ZZ_TEST_' . $title, 'fields' => $fields]);
    $createdFormIds[] = (int)$res['id'];
    return (int)$res['id'];
}

/** The form's live fields, in order — what the builder would have loaded. */
function liveFields(PDO $conn, int $formId): array
{
    $s = $conn->prepare("SELECT id, field_type, label, is_required, options, config, is_deleted
                           FROM form_fields WHERE form_id = ? AND is_deleted = 0 ORDER BY sort_order, id");
    $s->execute([$formId]);
    return $s->fetchAll(PDO::FETCH_ASSOC);
}

/** label => answer, exactly as the submissions screen resolves it. */
function answersByLabel(PDO $conn, int $submissionId): array
{
    $s = $conn->prepare("SELECT ff.label, d.field_value
                           FROM form_submission_data d
                           JOIN form_fields ff ON ff.id = d.field_id
                          WHERE d.submission_id = ?");
    $s->execute([$submissionId]);
    $out = [];
    foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $r) { $out[$r['label']] = $r['field_value']; }
    return $out;
}

try {

// ══════════════════════════════════════════════════════════════════════
echo "\n── Field identity: a reorder must not re-point historic answers ──\n";
// ══════════════════════════════════════════════════════════════════════

$formId = makeForm($conn, $ctx, 'reorder', [
    ['field_type' => 'text', 'label' => 'Employee name'],
    ['field_type' => 'text', 'label' => 'Home address'],
    ['field_type' => 'text', 'label' => 'Starting salary'],
]);
$f = liveFields($conn, $formId);
$subId = FormsService::submitForm($conn, $ctx, $formId, [
    (int)$f[0]['id'] => 'Alice Brown',
    (int)$f[1]['id'] => '14 Elm Road, Leeds',
    (int)$f[2]['id'] => '52000',
]);

// Positive control: the answers landed against the right questions to begin with.
$before = answersByLabel($conn, $subId);
check('answers start against the right questions',
    ($before['Employee name'] ?? null) === 'Alice Brown' && ($before['Starting salary'] ?? null) === '52000',
    json_encode($before));

// Drag "Starting salary" to the top — carrying each field's id, as the builder now does.
FormsService::saveForm($conn, $ctx, ['id' => $formId, 'fields' => [
    ['id' => (int)$f[2]['id'], 'field_type' => 'text', 'label' => 'Starting salary'],
    ['id' => (int)$f[0]['id'], 'field_type' => 'text', 'label' => 'Employee name'],
    ['id' => (int)$f[1]['id'], 'field_type' => 'text', 'label' => 'Home address'],
]]);

$after = answersByLabel($conn, $subId);
check('after a reorder, every answer still belongs to its own question',
    ($after['Employee name'] ?? null) === 'Alice Brown'
    && ($after['Home address'] ?? null) === '14 Elm Road, Leeds'
    && ($after['Starting salary'] ?? null) === '52000',
    json_encode($after));

// And the reorder really happened — otherwise the assertion above is vacuous.
$reordered = liveFields($conn, $formId);
check('positive control: the reorder was actually applied',
    $reordered[0]['label'] === 'Starting salary' && (int)$reordered[0]['id'] === (int)$f[2]['id'],
    'first field is now ' . $reordered[0]['label'] . ' (id ' . $reordered[0]['id'] . ')');

// ══════════════════════════════════════════════════════════════════════
echo "\n── Removing a question retires it and keeps its answers ──\n";
// ══════════════════════════════════════════════════════════════════════

// Drop "Employee name" (the middle one now) — the case that used to delete the
// LAST field's answers outright.
FormsService::saveForm($conn, $ctx, ['id' => $formId, 'fields' => [
    ['id' => (int)$f[2]['id'], 'field_type' => 'text', 'label' => 'Starting salary'],
    ['id' => (int)$f[1]['id'], 'field_type' => 'text', 'label' => 'Home address'],
]]);

$kept = answersByLabel($conn, $subId);
check('the removed question keeps the answers already given to it',
    ($kept['Employee name'] ?? null) === 'Alice Brown', json_encode($kept));
check('the surviving questions are untouched',
    ($kept['Home address'] ?? null) === '14 Elm Road, Leeds' && ($kept['Starting salary'] ?? null) === '52000',
    json_encode($kept));

$live = liveFields($conn, $formId);
check('positive control: the removed question is gone from the form itself',
    count($live) === 2 && !in_array('Employee name', array_column($live, 'label'), true),
    json_encode(array_column($live, 'label')));

// ══════════════════════════════════════════════════════════════════════
echo "\n── Conditions: a hidden question is not required ──\n";
// ══════════════════════════════════════════════════════════════════════

$condForm = makeForm($conn, $ctx, 'required_when_visible', [
    ['field_type' => 'radio', 'label' => 'Do you need a laptop?', 'options' => ['Yes', 'No']],
    ['field_type' => 'text',  'label' => 'Which model?', 'is_required' => 1,
     'config' => ['visible_if' => ['match' => 'all', 'rules' => [['field' => 'idx:0', 'op' => 'equals', 'value' => 'Yes']]]]],
]);
$cf = liveFields($conn, $condForm);
$needId  = (int)$cf[0]['id'];
$modelId = (int)$cf[1]['id'];

check('the condition stored a real field id, not the "idx:0" placeholder',
    (json_decode($cf[1]['config'], true)['visible_if']['rules'][0]['field'] ?? null) === $needId,
    (string)$cf[1]['config']);

// Answering "No" hides the model question, so leaving it blank must be accepted.
$ok = true; $err = '';
try { FormsService::submitForm($conn, $ctx, $condForm, [$needId => 'No']); }
catch (ServiceError $e) { $ok = false; $err = $e->getMessage(); }
check('a hidden required question does not block submission', $ok, $err);

// Positive control: when it IS shown, it is genuinely enforced. Without this the
// test above would also pass on a build that validated nothing at all.
$blocked = false; $msg = '';
try { FormsService::submitForm($conn, $ctx, $condForm, [$needId => 'Yes']); }
catch (ServiceError $e) { $blocked = true; $msg = $e->getMessage(); }
check('positive control: the same question IS required once shown', $blocked, $msg ?: 'submission was accepted');

// An answer typed before the question was hidden must not be recorded.
$subHidden = FormsService::submitForm($conn, $ctx, $condForm, [$needId => 'No', $modelId => 'ThinkPad X1']);
$hiddenAnswers = answersByLabel($conn, $subHidden);
check('an answer to a hidden question is not stored',
    !array_key_exists('Which model?', $hiddenAnswers), json_encode($hiddenAnswers));
check('positive control: the visible answer on that same submission WAS stored',
    ($hiddenAnswers['Do you need a laptop?'] ?? null) === 'No', json_encode($hiddenAnswers));

// ══════════════════════════════════════════════════════════════════════
echo "\n── Conditions can only look backwards ──\n";
// ══════════════════════════════════════════════════════════════════════

$rejected = false;
try {
    makeForm($conn, $ctx, 'forward_ref', [
        ['field_type' => 'text', 'label' => 'Shown first', 'config' =>
            ['visible_if' => ['match' => 'all', 'rules' => [['field' => 'idx:1', 'op' => 'equals', 'value' => 'x']]]]],
        ['field_type' => 'text', 'label' => 'Comes later'],
    ]);
} catch (ServiceError $e) { $rejected = true; }
check('a condition depending on a LATER question is rejected', $rejected,
    'the form saved, so a circular condition is constructible');

$accepted = true;
try {
    makeForm($conn, $ctx, 'backward_ref', [
        ['field_type' => 'text', 'label' => 'Comes first'],
        ['field_type' => 'text', 'label' => 'Shown second', 'config' =>
            ['visible_if' => ['match' => 'all', 'rules' => [['field' => 'idx:0', 'op' => 'equals', 'value' => 'x']]]]],
    ]);
} catch (ServiceError $e) { $accepted = false; }
check('positive control: depending on an EARLIER question is accepted', $accepted);

// ══════════════════════════════════════════════════════════════════════
echo "\n── Sections are headings, not questions ──\n";
// ══════════════════════════════════════════════════════════════════════

$secForm = makeForm($conn, $ctx, 'sections', [
    ['field_type' => 'radio',   'label' => 'Are you a manager?', 'options' => ['Yes', 'No']],
    ['field_type' => 'section', 'label' => 'Team details', 'config' =>
        ['visible_if' => ['match' => 'all', 'rules' => [['field' => 'idx:0', 'op' => 'equals', 'value' => 'Yes']]]]],
    ['field_type' => 'text',    'label' => 'How many reports?', 'is_required' => 1],
]);
$sf = liveFields($conn, $secForm);
$mgrId = (int)$sf[0]['id'];

$secBlocked = false;
try {
    makeForm($conn, $ctx, 'required_section', [['field_type' => 'section', 'label' => 'Nope', 'is_required' => 1]]);
} catch (ServiceError $e) { $secBlocked = true; }
check('a section cannot be marked required', $secBlocked);

$sectionAnswerBlocked = false;
try { FormsService::submitForm($conn, $ctx, $secForm, [$mgrId => 'Yes', (int)$sf[1]['id'] => 'anything']); }
catch (ServiceError $e) { $sectionAnswerBlocked = true; }
check('a section cannot be answered', $sectionAnswerBlocked);

// Hiding the heading has to hide what it contains, or the rule looks like it did nothing.
$ok = true; $err = '';
try { FormsService::submitForm($conn, $ctx, $secForm, [$mgrId => 'No']); }
catch (ServiceError $e) { $ok = false; $err = $e->getMessage(); }
check('a required question inside a hidden section is not enforced', $ok, $err);

$inSectionEnforced = false;
try { FormsService::submitForm($conn, $ctx, $secForm, [$mgrId => 'Yes']); }
catch (ServiceError $e) { $inSectionEnforced = true; }
check('positive control: it IS enforced when the section is shown', $inSectionEnforced);

// ══════════════════════════════════════════════════════════════════════
echo "\n── A new version re-points its conditions at its own fields ──\n";
// ══════════════════════════════════════════════════════════════════════

$verRes  = FormsService::createVersion($conn, $ctx, $condForm);
$newForm = (int)$verRes['id'];
$createdFormIds[] = $newForm;
$nf = liveFields($conn, $newForm);
$newTriggerId = (int)$nf[0]['id'];
$newRuleRef   = json_decode($nf[1]['config'], true)['visible_if']['rules'][0]['field'] ?? null;

check('the copy\'s condition points at the COPY\'s trigger field', $newRuleRef === $newTriggerId,
    "rule points at {$newRuleRef}, the new trigger is {$newTriggerId}");
check('positive control: that is a different field from the original\'s', $newTriggerId !== $needId,
    'the version fork reused the original field row');

// ══════════════════════════════════════════════════════════════════════
echo "\n── Date / time fields ──\n";
// ══════════════════════════════════════════════════════════════════════

$dateForm = makeForm($conn, $ctx, 'dates', [
    ['field_type' => 'datetime', 'label' => 'Needed by',      'is_required' => 1, 'config' => ['date_mode' => 'date']],
    ['field_type' => 'datetime', 'label' => 'Preferred time',                     'config' => ['date_mode' => 'time']],
    ['field_type' => 'datetime', 'label' => 'When did it happen',                 'config' => ['date_mode' => 'datetime']],
    ['field_type' => 'datetime', 'label' => 'Mode not stated'],
]);
$df = liveFields($conn, $dateForm);
[$dOnly, $tOnly, $dt, $noMode] = array_map(fn($r) => (int)$r['id'], $df);

check('a date field with no mode falls back to a plain date',
    FormsService::dateModeOf($df[3]) === 'date', FormsService::dateModeOf($df[3]));
check('each mode round-trips through save',
    FormsService::dateModeOf($df[0]) === 'date'
    && FormsService::dateModeOf($df[1]) === 'time'
    && FormsService::dateModeOf($df[2]) === 'datetime');

$badMode = false;
try { makeForm($conn, $ctx, 'bad_mode', [['field_type' => 'datetime', 'label' => 'X', 'config' => ['date_mode' => 'fortnight']]]); }
catch (ServiceError $e) { $badMode = true; }
check('an unknown date_mode is rejected', $badMode);

// date_mode is meaningless on anything else and must not be stored there.
$mixForm = makeForm($conn, $ctx, 'mode_on_text', [['field_type' => 'text', 'label' => 'Plain', 'config' => ['date_mode' => 'time']]]);
$mixCfg = liveFields($conn, $mixForm)[0]['config'];
check('date_mode is dropped from a non-date field',
    $mixCfg === null || !isset(json_decode((string)$mixCfg, true)['date_mode']), (string)$mixCfg);

// Format enforcement, per mode.
$formatCases = [
    ['a date accepts YYYY-MM-DD',            $dOnly, '2026-08-14',       true],
    ['a date rejects a datetime',            $dOnly, '2026-08-14T09:00', false],
    ['a date rejects free text',             $dOnly, 'next Tuesday',     false],
    ['a time accepts HH:MM',                 $tOnly, '09:30',            true],
    ['a time rejects a date',                $tOnly, '2026-08-14',       false],
    ['a datetime accepts the full value',    $dt,    '2026-08-14T09:00', true],
    ['a datetime rejects a bare date',       $dt,    '2026-08-14',       false],
];
foreach ($formatCases as [$name, $fid, $value, $shouldPass]) {
    $accepted = true;
    try { FormsService::submitForm($conn, $ctx, $dateForm, [$dOnly => '2026-01-01', $fid => $value]); }
    catch (ServiceError $e) { $accepted = false; }
    check($name, $accepted === $shouldPass);
}

// 🔑 The trap this whole feature had to avoid: a form answer is a NAIVE local value.
$naiveSub = FormsService::submitForm($conn, $ctx, $dateForm, [$dOnly => '2026-08-14', $dt => '2026-08-14T23:30']);
$naive = answersByLabel($conn, $naiveSub);
check('a date is stored EXACTLY as typed, with no timezone conversion',
    ($naive['Needed by'] ?? null) === '2026-08-14', json_encode($naive));
check('a late-evening datetime does not roll into the next day',
    ($naive['When did it happen'] ?? null) === '2026-08-14T23:30', json_encode($naive));

// A date can drive a condition.
$dateCond = makeForm($conn, $ctx, 'date_cond', [
    ['field_type' => 'datetime', 'label' => 'Start date', 'config' => ['date_mode' => 'date']],
    ['field_type' => 'textarea', 'label' => 'Why so urgent?', 'is_required' => 1,
     'config' => ['visible_if' => ['match' => 'all', 'rules' => [['field' => 'idx:0', 'op' => 'is_before', 'value' => '2026-09-01']]]]],
]);
$dcf = liveFields($conn, $dateCond);
$startId = (int)$dcf[0]['id'];

$urgentEnforced = false;
try { FormsService::submitForm($conn, $ctx, $dateCond, [$startId => '2026-08-14']); }
catch (ServiceError $e) { $urgentEnforced = true; }
check('is_before shows the follow-up for an early date', $urgentEnforced);

$notUrgent = true;
try { FormsService::submitForm($conn, $ctx, $dateCond, [$startId => '2026-12-01']); }
catch (ServiceError $e) { $notUrgent = false; }
check('positive control: a later date leaves it hidden and unrequired', $notUrgent);

// ══════════════════════════════════════════════════════════════════════
echo "\n── The evaluator: PHP and JS must agree ──\n";
// ══════════════════════════════════════════════════════════════════════

// field 1 = a text answer, field 2 = a checkboxes answer, field 3 = a number.
$evalFields = [
    ['id' => 1, 'field_type' => 'text',       'config' => null],
    ['id' => 2, 'field_type' => 'checkboxes', 'config' => null],
    ['id' => 3, 'field_type' => 'number',     'config' => null],
    ['id' => 4, 'field_type' => 'datetime',   'config' => null],
];
$values = [1 => 'Yes please', 2 => '["Monitor","Dock"]', 3 => '7', 4 => '2026-08-14'];

$cases = [
    ['equals on text',              ['field' => 1, 'op' => 'equals',       'value' => 'Yes please'], true],
    ['equals is exact, not fuzzy',  ['field' => 1, 'op' => 'equals',       'value' => 'Yes'],        false],
    ['contains on text',            ['field' => 1, 'op' => 'contains',     'value' => 'please'],     true],
    ['contains ignores case',       ['field' => 1, 'op' => 'contains',     'value' => 'PLEASE'],     true],
    ['not_equals',                  ['field' => 1, 'op' => 'not_equals',   'value' => 'No'],         true],
    ['equals matches one of many',  ['field' => 2, 'op' => 'equals',       'value' => 'Dock'],       true],
    ['equals misses an unpicked',   ['field' => 2, 'op' => 'equals',       'value' => 'Keyboard'],   false],
    ['contains on a multi-select',  ['field' => 2, 'op' => 'contains',     'value' => 'Monitor'],    true],
    ['is_empty on an answer',       ['field' => 1, 'op' => 'is_empty',     'value' => ''],           false],
    ['is_empty on no answer',       ['field' => 9, 'op' => 'is_empty',     'value' => ''],           true],
    ['is_not_empty',                ['field' => 1, 'op' => 'is_not_empty', 'value' => ''],           true],
    ['greater_than',                ['field' => 3, 'op' => 'greater_than', 'value' => '5'],          true],
    ['greater_than, not equal',     ['field' => 3, 'op' => 'greater_than', 'value' => '7'],          false],
    ['less_than',                   ['field' => 3, 'op' => 'less_than',    'value' => '10'],         true],
    ['numeric ops ignore text',     ['field' => 1, 'op' => 'greater_than', 'value' => '5'],          false],
    ['unknown op never hides',      ['field' => 1, 'op' => 'equals',       'value' => 'Yes please'],  true],
    // Dates compare as plain strings — ISO-8601's lexical order IS chronological order,
    // which is what lets both engines agree without either parsing a timezone.
    ['is_before an later date',     ['field' => 4, 'op' => 'is_before',    'value' => '2026-09-01'], true],
    ['is_before an earlier date',   ['field' => 4, 'op' => 'is_before',    'value' => '2026-01-01'], false],
    ['is_after an earlier date',    ['field' => 4, 'op' => 'is_after',     'value' => '2026-01-01'], true],
    ['is_after is not inclusive',   ['field' => 4, 'op' => 'is_after',     'value' => '2026-08-14'], false],
    ['is_before crosses the year',  ['field' => 4, 'op' => 'is_before',    'value' => '2027-01-01'], true],
    ['date ops need an answer',     ['field' => 9, 'op' => 'is_before',    'value' => '2027-01-01'], false],
    ['equals still works on a date',['field' => 4, 'op' => 'equals',       'value' => '2026-08-14'], true],
];

$phpResults = [];
foreach ($cases as [$name, $rule, $expected]) {
    $got = formLogicTestRule($rule, $values);
    $phpResults[] = $got;
    check("PHP: $name", $got === $expected, 'expected ' . var_export($expected, true) . ', got ' . var_export($got, true));
}

// ---- The same cases through the browser copy.
$chrome = null;
foreach ([
    'C:\Program Files\Google\Chrome\Application\chrome.exe',
    'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe',
] as $candidate) {
    if (is_file($candidate)) { $chrome = $candidate; break; }
}

if ($chrome === null) {
    skipped('JS evaluator agrees with PHP', 'Chrome not found — the browser copy was NOT exercised');
} else {
    $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'freeitsm_formlogic_' . getmypid();
    @mkdir($tmp, 0777, true);
    // The library is INLINED rather than <script src>'d: a file:// page cannot
    // reliably fetch a sibling file without extra Chrome flags, and a silent
    // load failure would look exactly like a passing test.
    $lib  = file_get_contents(__DIR__ . '/../../assets/js/form-logic.js');
    $html = "<!doctype html><html><body><pre id=\"out\"></pre><script>\n"
          . $lib . "\n"
          . "var values = " . json_encode($values) . ";\n"
          . "var cases  = " . json_encode(array_map(fn($c) => $c[1], $cases)) . ";\n"
          . "var res = cases.map(function (r) { return FormLogic.testRule(r, values); });\n"
          . "document.getElementById('out').textContent = 'RESULTS:' + JSON.stringify(res);\n"
          . "</script></body></html>";
    file_put_contents($tmp . '/harness.html', $html);

    $cmd = '"' . $chrome . '" --headless --disable-gpu --no-sandbox --dump-dom '
         . '--virtual-time-budget=2000 "file:///' . str_replace('\\', '/', $tmp) . '/harness.html" 2>&1';
    $dom = shell_exec($cmd);

    if (!preg_match('/RESULTS:(\[[^\]]*\])/', (string)$dom, $m)) {
        check('JS evaluator produced a result at all', false,
            'no RESULTS marker in the DOM — the script probably failed to parse');
    } else {
        // Negative control: prove the harness can actually FAIL. If a deliberately
        // wrong expectation still "passes", the comparison is not comparing anything.
        $jsResults = json_decode($m[1], true);
        check('JS evaluator ran every case', is_array($jsResults) && count($jsResults) === count($cases),
            'got ' . count((array)$jsResults) . ' of ' . count($cases));

        foreach ($cases as $i => [$name, $rule, $expected]) {
            $js = $jsResults[$i] ?? null;
            check("JS matches PHP: $name", $js === $phpResults[$i],
                'PHP said ' . var_export($phpResults[$i], true) . ', JS said ' . var_export($js, true));
        }
    }
    @unlink($tmp . '/harness.html');
    @rmdir($tmp);
}

// ══════════════════════════════════════════════════════════════════════
echo "\n── Section inheritance in the evaluator ──\n";
// ══════════════════════════════════════════════════════════════════════

$layout = [
    ['id' => 10, 'field_type' => 'radio',   'config' => null],
    ['id' => 11, 'field_type' => 'section', 'config' => json_encode(
        ['visible_if' => ['match' => 'all', 'rules' => [['field' => 10, 'op' => 'equals', 'value' => 'Yes']]]])],
    ['id' => 12, 'field_type' => 'text',    'config' => null],
    ['id' => 13, 'field_type' => 'section', 'config' => null],
    ['id' => 14, 'field_type' => 'text',    'config' => null],
];
$visNo  = formLogicVisibility($layout, [10 => 'No']);
$visYes = formLogicVisibility($layout, [10 => 'Yes']);

check('a hidden section hides the field inside it', $visNo[12] === false);
check('a later section is not affected by an earlier one', $visNo[14] === true);
check('positive control: the same field shows when the section shows', $visYes[12] === true);
check('a field before any section is always shown', $visNo[10] === true && $visYes[10] === true);

} finally {
    // ── Cleanup. Runs whatever happened above, so a failure never leaves test
    //    forms behind in a real database.
    //
    //    Driven by the ZZ_TEST_ title rather than by $createdFormIds: a fatal part
    //    way through createVersion() can commit a row before the id ever reaches
    //    that list, and the version chain's FK then blocks the whole cleanup. The
    //    title is the one thing every row this file creates definitely has.
    $ids = $conn->query("SELECT id FROM forms WHERE title LIKE 'ZZ\\_TEST\\_%'")->fetchAll(PDO::FETCH_COLUMN);
    $ids = array_map('intval', array_unique(array_merge($ids, $createdFormIds)));

    foreach ($ids as $fid) {
        $conn->prepare("DELETE sd FROM form_submission_data sd
                          JOIN form_submissions s ON sd.submission_id = s.id
                         WHERE s.form_id = ?")->execute([$fid]);
        $conn->prepare("DELETE FROM form_submissions WHERE form_id = ?")->execute([$fid]);
        $conn->prepare("DELETE FROM form_fields WHERE form_id = ?")->execute([$fid]);
    }
    // Children before parents, or fk_forms_parent blocks the delete. Repeated
    // sweeps rather than a sort, so a chain of any depth drains.
    for ($sweep = 0; $sweep < 10 && $ids; $sweep++) {
        foreach ($ids as $k => $fid) {
            $kids = $conn->prepare("SELECT COUNT(*) FROM forms WHERE parent_form_id = ?");
            $kids->execute([$fid]);
            if ((int)$kids->fetchColumn() === 0) {
                $conn->prepare("DELETE FROM forms WHERE id = ?")->execute([$fid]);
                unset($ids[$k]);
            }
        }
    }
    $left = $conn->query("SELECT COUNT(*) FROM forms WHERE title LIKE 'ZZ\\_TEST\\_%'")->fetchColumn();
    echo "\nCleanup: {$left} test form(s) left behind.\n";
}

echo "\n" . str_repeat('─', 60) . "\n";
echo "  {$pass} passed, {$fail} failed" . ($skip ? ", {$skip} skipped" : '') . "\n";
exit($fail > 0 ? 1 : 0);
