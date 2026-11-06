<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/** Workspace defaults and the complete install/personal/ticket matrix. No database needed. */
require_once __DIR__ . '/../includes/ticket_workspace.php';

$checks = 0;
function checkWorkspace($actual, $expected, string $label): void
{
    global $checks;
    if ($actual !== $expected) {
        fwrite(STDERR, "FAIL: $label\n");
        exit(1);
    }
    $checks++;
}

checkWorkspace(TicketWorkspaceMode::tryFrom('invalid'), null, 'unknown mode is not accepted');
foreach ([null, '', 'invalid'] as $value) {
    checkWorkspace(ticketWorkspacePersonal($value), true, 'missing/invalid personal preference defaults on');
}
checkWorkspace(ticketWorkspacePersonal('0'), false, 'explicit opt-out');
checkWorkspace(ticketWorkspacePersonal('1'), true, 'explicit opt-in');
foreach (TicketWorkspaceMode::cases() as $mode) {
    foreach ([false, true] as $personal) {
        foreach ([false, true] as $flag) {
            $expected = $personal && ($mode === TicketWorkspaceMode::All
                || ($mode === TicketWorkspaceMode::Selected && $flag));
            checkWorkspace(ticketWorkspaceEnabled($mode, $personal, $flag), $expected,
                $mode->value . '/' . (int)$personal . '/' . (int)$flag);
        }
    }
}
$beforeVerification = new class extends PDO {
    public function __construct() {}
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false {
        throw new PDOException('Column is not installed yet');
    }
};
checkWorkspace(ticketWorkspaceReady($beforeVerification), false, 'missing column degrades safely');
checkWorkspace(ticketWorkspaceMode($beforeVerification), TicketWorkspaceMode::Off, 'missing setting defaults off');
echo "$checks workspace setting checks passed\n";
