<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The task recurrence date engine (discussion #94).
 *
 * Pure date arithmetic, no database, which is precisely why it is worth testing
 * hard: every interesting bug in a recurrence feature is a calendar edge case,
 * and none of them show up in a demo where everything falls on the 15th of a
 * 31-day month.
 *
 * The cases below are the ones that break naive implementations:
 *   - the 31st in a 30-day month, and in February
 *   - "the last day of the month" across month lengths and a leap year
 *   - "the 5th Tuesday" of a month that has only four
 *   - "every 2 weeks on Mon and Thu" not collapsing into every week
 *   - 29 February under a yearly rule
 *
 * Run:  php tests/task-recurrence-dates.php
 */

require_once __DIR__ . '/../includes/services/task_recurrence.php';

$pass = 0; $fail = 0;
function is_(string $what, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { $pass++; printf("  PASS  %-58s %s\n", $what, var_export($got, true)); }
    else { $fail++; printf("  FAIL  %-58s got %s, wanted %s\n", $what, var_export($got, true), var_export($want, true)); }
}
function nxt(array $rule, string $from) { return TaskRecurrence::nextDate($rule, $from); }

echo "\nTask recurrence date engine (#94)\n" . str_repeat('=', 78) . "\n";

// ── Daily ────────────────────────────────────────────────────────────────────
echo "\nDaily:\n";
is_('every day',            nxt(['freq'=>'daily','interval_n'=>1], '2026-08-30'), '2026-08-31');
is_('every 3 days',         nxt(['freq'=>'daily','interval_n'=>3], '2026-08-30'), '2026-09-02');
is_('every day over a month end', nxt(['freq'=>'daily','interval_n'=>1], '2026-08-31'), '2026-09-01');
is_('every day over a year end',  nxt(['freq'=>'daily','interval_n'=>1], '2026-12-31'), '2027-01-01');

// ── Weekly ───────────────────────────────────────────────────────────────────
echo "\nWeekly:\n";
// 2026-08-30 is a Sunday.
is_('every week, same weekday',    nxt(['freq'=>'weekly','interval_n'=>1], '2026-08-30'), '2026-09-06');
is_('every 2 weeks, same weekday', nxt(['freq'=>'weekly','interval_n'=>2], '2026-08-30'), '2026-09-13');
// Mon=1 Thu=4. From Monday 2026-08-31 the next named day is Thursday.
is_('weekly on Mon+Thu, from a Monday', nxt(['freq'=>'weekly','interval_n'=>1,'weekdays'=>'1,4'], '2026-08-31'), '2026-09-03');
// From that Thursday, the week is out of named days, so back to Monday.
is_('weekly on Mon+Thu, from the Thursday', nxt(['freq'=>'weekly','interval_n'=>1,'weekdays'=>'1,4'], '2026-09-03'), '2026-09-07');
// ⭐ The one that catches a naive implementation: with interval 2 the wrap must
// skip a whole week, or "every fortnight" silently becomes "every week".
is_('FORTNIGHTLY on Mon+Thu, wrapping the week', nxt(['freq'=>'weekly','interval_n'=>2,'weekdays'=>'1,4'], '2026-09-03'), '2026-09-14');
is_('fortnightly on Mon+Thu, within the week',   nxt(['freq'=>'weekly','interval_n'=>2,'weekdays'=>'1,4'], '2026-08-31'), '2026-09-03');

// ── Monthly by day number ────────────────────────────────────────────────────
echo "\nMonthly, by day of the month:\n";
is_('the 15th, next month',        nxt(['freq'=>'monthly','interval_n'=>1,'day_of_month'=>15], '2026-08-15'), '2026-09-15');
is_('every 3 months',              nxt(['freq'=>'monthly','interval_n'=>3,'day_of_month'=>15], '2026-08-15'), '2026-11-15');
// ⭐ 31 January + 1 month. Naive addition lands on 2 or 3 March.
is_('the 31st into a SHORT month',  nxt(['freq'=>'monthly','interval_n'=>1,'day_of_month'=>31], '2026-01-31'), '2026-02-28');
is_('the 31st into a 30-day month', nxt(['freq'=>'monthly','interval_n'=>1,'day_of_month'=>31], '2026-03-31'), '2026-04-30');
is_('the 31st, and the month has one', nxt(['freq'=>'monthly','interval_n'=>1,'day_of_month'=>31], '2026-04-30'), '2026-05-31');
is_('the LAST day (-1), into February', nxt(['freq'=>'monthly','interval_n'=>1,'day_of_month'=>-1], '2026-01-31'), '2026-02-28');
is_('the LAST day (-1), leap February',  nxt(['freq'=>'monthly','interval_n'=>1,'day_of_month'=>-1], '2028-01-31'), '2028-02-29');

// ── Monthly by nth weekday ───────────────────────────────────────────────────
echo "\nMonthly, by nth weekday (Ed's ask):\n";
// September 2026: the 1st is a Tuesday.
is_('1st Tuesday of next month',  nxt(['freq'=>'monthly','interval_n'=>1,'month_mode'=>'nth','nth'=>1,'nth_weekday'=>TaskRecurrence::TUE], '2026-08-04'), '2026-09-01');
is_('2nd Tuesday of next month',  nxt(['freq'=>'monthly','interval_n'=>1,'month_mode'=>'nth','nth'=>2,'nth_weekday'=>TaskRecurrence::TUE], '2026-08-04'), '2026-09-08');
is_('3rd Monday of next month',   nxt(['freq'=>'monthly','interval_n'=>1,'month_mode'=>'nth','nth'=>3,'nth_weekday'=>TaskRecurrence::MON], '2026-08-04'), '2026-09-21');
is_('LAST Friday of next month',  nxt(['freq'=>'monthly','interval_n'=>1,'month_mode'=>'nth','nth'=>-1,'nth_weekday'=>TaskRecurrence::FRI], '2026-08-04'), '2026-09-25');
// September 2026 HAS five Tuesdays (1, 8, 15, 22, 29), so this asks for a real
// fifth one and must get it rather than spilling into October.
is_('5th Tuesday, and the month has one', nxt(['freq'=>'monthly','interval_n'=>1,'month_mode'=>'nth','nth'=>5,'nth_weekday'=>TaskRecurrence::TUE], '2026-08-04'), '2026-09-29');
// ⭐ The fallback case. September 2026 has only FOUR Fridays (4, 11, 18, 25), so
// a rule asking for the fifth must land on the last one and not on 2 October.
// An earlier version of this test used Tuesday, which that month has five of -
// it passed without ever exercising the fallback it claimed to cover.
is_('5th Friday when the month has only four', nxt(['freq'=>'monthly','interval_n'=>1,'month_mode'=>'nth','nth'=>5,'nth_weekday'=>TaskRecurrence::FRI], '2026-08-04'), '2026-09-25');

// ── Yearly ───────────────────────────────────────────────────────────────────
echo "\nYearly:\n";
is_('same day next year',        nxt(['freq'=>'yearly','interval_n'=>1,'month_of_year'=>8,'day_of_month'=>30], '2026-08-30'), '2027-08-30');
is_('every 2 years',             nxt(['freq'=>'yearly','interval_n'=>2,'month_of_year'=>8,'day_of_month'=>30], '2026-08-30'), '2028-08-30');
// ⭐ 29 Feb in a non-leap year has to clamp rather than roll into March.
is_('29 Feb into a NON-leap year', nxt(['freq'=>'yearly','interval_n'=>1,'month_of_year'=>2,'day_of_month'=>29], '2028-02-29'), '2029-02-28');
is_('29 Feb, four years on',       nxt(['freq'=>'yearly','interval_n'=>4,'month_of_year'=>2,'day_of_month'=>29], '2028-02-29'), '2032-02-29');
is_('2nd Monday of March, yearly', nxt(['freq'=>'yearly','interval_n'=>1,'month_of_year'=>3,'month_mode'=>'nth','nth'=>2,'nth_weekday'=>TaskRecurrence::MON], '2026-03-09'), '2027-03-08');

// ── Guards ───────────────────────────────────────────────────────────────────
echo "\nRefusals and edges:\n";
is_('an unknown frequency yields nothing', nxt(['freq'=>'fortnightly'], '2026-08-30'), null);
is_('a malformed date yields nothing',     nxt(['freq'=>'daily'], 'not-a-date'), null);
is_('interval 0 is treated as 1',          nxt(['freq'=>'daily','interval_n'=>0], '2026-08-30'), '2026-08-31');
is_('a negative interval is treated as 1', nxt(['freq'=>'daily','interval_n'=>-5], '2026-08-30'), '2026-08-31');
is_('nth with no weekday yields nothing',  nxt(['freq'=>'monthly','month_mode'=>'nth','nth'=>2,'nth_weekday'=>0], '2026-08-04'), null);
is_('weekly with a junk weekday list falls back to every N weeks',
    nxt(['freq'=>'weekly','interval_n'=>1,'weekdays'=>'9,0,x'], '2026-08-30'), '2026-09-06');

// ── Ending a series ──────────────────────────────────────────────────────────
echo "\nWhen a series stops:\n";
is_('never ends',            TaskRecurrence::isExhausted(['ends_mode'=>'never'], '2099-01-01'), false);
is_('before the end date',   TaskRecurrence::isExhausted(['ends_mode'=>'on_date','ends_on'=>'2026-12-31'], '2026-12-30'), false);
is_('exactly on the end date is still allowed', TaskRecurrence::isExhausted(['ends_mode'=>'on_date','ends_on'=>'2026-12-31'], '2026-12-31'), false);
is_('past the end date',     TaskRecurrence::isExhausted(['ends_mode'=>'on_date','ends_on'=>'2026-12-31'], '2027-01-01'), true);
is_('5 of 6 made',           TaskRecurrence::isExhausted(['ends_mode'=>'after_count','max_occurrences'=>6,'occurrences_created'=>5], '2026-09-01'), false);
is_('6 of 6 made - stop',    TaskRecurrence::isExhausted(['ends_mode'=>'after_count','max_occurrences'=>6,'occurrences_created'=>6], '2026-09-01'), true);

// ── When work on an occurrence can begin ─────────────────────────────────────
//
// The worker creates an occurrence when its START arrives, not when it falls
// due, so that a fortnight of work does not appear on the day it is already
// due. These are here rather than in the spawning tests because they need no
// database — and because the two faults below are invisible to a test that
// only uses tasks with a due date, which is every other test in the suite.
echo "\nWhen work on an occurrence can begin:\n";
$span = fn(string $start, string $due) => ['start_date' => $start, 'due_date' => $due];

is_('a fortnight of work starts a fortnight before it is due',
    TaskRecurrence::startForDue($span('2026-08-17', '2026-08-31'), '2026-09-14'), '2026-08-31');
is_('a same-day task has no start of its own',
    TaskRecurrence::startForDue($span('2026-08-31', '2026-08-31'), '2026-09-14'), null);
is_('no start date means the due date is all there is',
    TaskRecurrence::startForDue(['due_date' => '2026-08-31'], '2026-09-14'), null);
is_('a start after the due date is nonsense, not a negative gap',
    TaskRecurrence::startForDue($span('2026-09-30', '2026-08-31'), '2026-09-14'), null);

// This one was genuinely wrong: the old code read a bare date as LOCAL midnight
// and wrote the answer with gmdate, so it landed a day early — and only while
// the clocks are forward. Checked against the old implementation, which returns
// 2026-08-16 here and the right answer in midwinter, so a fault present for
// seven months of the year was invisible for the other five.
is_('British Summer Time does not shift it a day early',
    TaskRecurrence::startForDue($span('2026-08-17', '2026-08-31'), '2026-08-31'), '2026-08-17');

// This one the old code got right, by luck rather than design: dividing a
// 14-day-and-one-hour gap by 86400 truncated it to 13, and the timezone skew
// above shifted it back a day, so two faults cancelled. Kept as a guard,
// because fixing either one alone would have exposed the other.
is_('a gap spanning the clock change is still the same number of days',
    TaskRecurrence::startForDue($span('2026-03-22', '2026-04-05'), '2026-05-03'), '2026-04-19');

// ── Previewing a rule before committing to it ────────────────────────────────
//
// Ed's idea: show every date the current settings would produce, so "every
// second Tuesday of the month, 5 times" does not have to be saved and waited
// on to find out what it means. The list must come from the same engine the
// worker uses, so these check the two agree about where a series stops.
echo "\nPreviewing a rule:\n";
$prevDates = function (array $rule, array $task, int $limit = 25): string {
    $p = TaskRecurrence::previewRule($rule, $task, $limit);
    return implode(' ', array_column($p['occurrences'], 'due_date')) . ($p['truncated'] ? ' …' : '');
};
$weeklyMon = ['mode'=>'schedule','freq'=>'weekly','interval_n'=>1,'weekdays'=>'1',
              'ends_mode'=>'after_count','max_occurrences'=>5];
$aaa = ['due_date'=>'2026-08-28','start_date'=>'2026-08-24'];

is_('the task itself is occurrence 1, then four more',
    $prevDates($weeklyMon, $aaa),
    '2026-08-28 2026-08-31 2026-09-07 2026-09-14 2026-09-21');
is_('and the fifth is the last - the count includes the task',
    count(TaskRecurrence::previewRule($weeklyMon, $aaa)['occurrences']), 5);
is_('each one carries the span of the task it came from',
    TaskRecurrence::previewRule($weeklyMon, $aaa)['occurrences'][1]['start_date'], '2026-08-27');

// A repeat that fires on completion has no dates to list: the next one is
// counted from the day somebody finishes, which has not happened.
is_('a completion-mode repeat previews only the task in front of you',
    $prevDates(['mode'=>'completion','freq'=>'daily','interval_n'=>30], $aaa), '2026-08-28');

// An endless series has to stop somewhere, and must SAY it stopped.
$endless = ['mode'=>'schedule','freq'=>'daily','interval_n'=>1,'ends_mode'=>'never'];
is_('an endless series is capped and flagged as truncated',
    $prevDates($endless, $aaa, 4), '2026-08-28 2026-08-29 2026-08-30 2026-08-31 …');

// The end date is the worker's rule, not a second opinion.
is_('an end date stops the list where the worker would stop',
    $prevDates(['mode'=>'schedule','freq'=>'weekly','interval_n'=>1,'weekdays'=>'1',
                'ends_mode'=>'on_date','ends_on'=>'2026-09-08'], $aaa),
    '2026-08-28 2026-08-31 2026-09-07');

// Settings that produce nothing give one entry - the task - which is what the
// screen turns into "these settings do not produce another occurrence".
is_('a rule that has already ended previews nothing further',
    $prevDates(['mode'=>'schedule','freq'=>'weekly','interval_n'=>1,'weekdays'=>'1',
                'ends_mode'=>'on_date','ends_on'=>'2026-08-29'], $aaa), '2026-08-28');

// ── Sanitising what the browser sends ────────────────────────────────────────
// One home, shared by saving and previewing. Two sanitisers would let a preview
// show dates the worker never produces.
echo "\nSanitising rule input:\n";
is_('an unknown mode falls back to completion',
    TaskRecurrence::ruleFromInput(['mode'=>'whenever'])['mode'], 'completion');
is_('an unknown frequency falls back to weekly',
    TaskRecurrence::ruleFromInput(['freq'=>'fortnightly'])['freq'], 'weekly');
is_('junk weekdays are dropped, valid ones kept',
    TaskRecurrence::ruleFromInput(['weekdays'=>'0,1,9,5,x'])['weekdays'], '1,5');
is_('weekdays may arrive as an array',
    TaskRecurrence::ruleFromInput(['weekdays'=>[2,3]])['weekdays'], '2,3');
is_('the last day of the month survives as -1',
    TaskRecurrence::ruleFromInput(['day_of_month'=>-1])['day_of_month'], -1);
is_('an absurd interval is clamped, not accepted',
    TaskRecurrence::ruleFromInput(['interval_n'=>99999])['interval_n'], 365);
is_('an end date is ignored unless the ending asks for one',
    TaskRecurrence::ruleFromInput(['ends_mode'=>'never','ends_on'=>'2026-12-31'])['ends_on'], null);

echo "\n" . str_repeat('=', 78) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);
