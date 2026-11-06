<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/** The workflow "contains" / "does not contain" condition (GH #214); PHP only, no database.
 * It ignores capitals and the spaces around the typed value - an email domain
 * has no case. Control: with str_contains() back, the capitals and space rows fail.
 */
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../workflow/includes/engine.php';

$op = new ReflectionMethod('WorkflowEngine', 'evaluateOperator');
$op->setAccessible(true);
$failed = 0;
foreach ([
    // [operator, actual, value, expected, label]
    ['contains',     'user@abc.com',      'abc.com',  true,  'the reported case'],
    ['contains',     'User@ABC.com',      'abc.com',  true,  'capitals in the address'],
    ['contains',     'user@abc.com',      'ABC.com',  true,  'capitals in the value'],
    ['contains',     'user@abc.com',      ' abc.com ', true, 'spaces around the value'],
    ['contains',     'URGENT: printer',   'urgent',   true,  'a subject in capitals'],
    ['contains',     'Ärger im Büro',     'ärger',    true,  'non-English capitals'],
    ['contains',     'user@xyz.com',      'abc.com',  false, 'another domain'],
    ['contains',     null,                'abc.com',  false, 'no value in the payload'],
    ['contains',     'user@abc.com',      '   ',      false, 'a blank value matches nothing'],
    ['not_contains', 'User@ABC.com',      'abc.com',  false, 'does not contain, capitals'],
    ['not_contains', 'user@xyz.com',      'abc.com',  true,  'does not contain, another domain'],
] as [$o, $actual, $value, $expected, $label]) {
    $ok = $op->invoke(null, $o, $actual, $value) === $expected;
    if (!$ok) $failed++;
    echo ($ok ? '  PASS  ' : '  FAIL  ') . $label . "\n";
}
echo $failed ? "$failed failed\n" : "all passed\n";
exit($failed ? 1 : 0);
