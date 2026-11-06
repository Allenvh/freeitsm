<?php
/**
 * The ticket workspace (PR #206/#211, by @Dr4inder): who sees a ticket laid out
 * for WORKING - properties in a column, conversation / work / links / time /
 * history on tabs - rather than in the classic reading pane.
 *
 * THREE SWITCHES, EACH OWNED BY SOMEONE DIFFERENT
 * -----------------------------------------------
 *   1. The install mode (system_settings 'ticket_workspace_mode'), owned by an
 *      administrator: Off / Only tickets turned into a workspace / Every ticket.
 *      Off is the default, so an upgrade changes nothing anyone sees.
 *   2. The analyst's own switch (user_preferences 'ticket_workspace'), on unless
 *      they turn it off. Shown only when the install mode is not Off.
 *   3. The ticket's own flag (tickets.is_workspace), set with More -> Make
 *      workspace in the middle mode, so everyone working that ticket sees it the
 *      same way. Written ONLY through TicketsService::updateTicket(), which audits
 *      it - see the 'workspace' block there.
 *
 * The three are independent and none ever clears another: switching the install
 * to Off and back restores the same flagged tickets.
 *
 * 🔑 The workspace is a way to DRAW the reading pane, not a second ticket page.
 * displayEmail() in assets/js/inbox.js defines every control once and chooses
 * where it goes as it renders; assets/js/ticket-workspace.js arranges the pieces.
 * Nothing in the workspace writes anything the reading pane does not.
 *
 * @wiki Ticket-Workspace-Developer-Guide
 */

/**
 * The install setting. The value is what is stored in system_settings, so a
 * case's value must never change. Read with ticketWorkspaceMode(), which falls
 * back to Off for anything it does not recognise.
 */
enum TicketWorkspaceMode: string
{
    case Off      = 'off';
    case Selected = 'selected';   // "Only tickets turned into a workspace"
    case All      = 'all';        // "Every ticket"
}

/**
 * The analyst's own switch, from its stored preference value.
 *
 * ON BY DEFAULT (design decision 1): an administrator who turns the workspace on
 * expects people to see it, so a missing preference means "yes". Only an
 * explicit '0' opts out - a blank or unrecognised value is not a choice.
 *
 * @param mixed $value  the stored preference_value, or null when there is none
 */
function ticketWorkspacePersonal($value): bool
{
    return $value !== '0' && $value !== 0 && $value !== false;
}

/**
 * Does this analyst get the workspace for this ticket? The whole rule in one
 * place, so the tests can walk every combination of the three switches.
 *
 * TRAP: assets/js/ticket-workspace.js enabled() applies the same rule in the
 * browser, from window.TICKET_WORKSPACE. Change one, change both.
 */
function ticketWorkspaceEnabled(TicketWorkspaceMode $mode, bool $personal, bool $flag): bool
{
    return $personal && ($mode === TicketWorkspaceMode::All
        || ($mode === TicketWorkspaceMode::Selected && $flag));
}

/**
 * Has Database Verification added tickets.is_workspace yet? New code reaches an
 * install before anyone runs it, and the ticket detail read calls in here, so a
 * missing column must mean "no ticket is a workspace", never an error.
 */
function ticketWorkspaceReady(PDO $conn): bool
{
    static $ready = null;
    if ($ready === null) {
        try {
            $conn->query('SELECT is_workspace FROM tickets LIMIT 0');
            $ready = true;
        } catch (Throwable) {
            $ready = false;
        }
    }
    return $ready;
}

/** The install mode. Off when never saved, unrecognised, or unreadable. */
function ticketWorkspaceMode(PDO $conn): TicketWorkspaceMode
{
    try {
        $stmt = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'ticket_workspace_mode'");
        return TicketWorkspaceMode::tryFrom((string)$stmt->fetchColumn()) ?? TicketWorkspaceMode::Off;
    } catch (Throwable) {
        return TicketWorkspaceMode::Off;
    }
}

/**
 * What tickets/index.php hands the browser as window.TICKET_WORKSPACE:
 *   mode      'off' | 'selected' | 'all'
 *   personal  this analyst's own switch
 *   ready     whether the column exists, so Make workspace is not offered before
 *             Database Verification (the service would refuse it)
 * The ticket's own flag travels with the ticket, as is_workspace.
 */
function ticketWorkspaceConfig(PDO $conn, int $analystId): array
{
    $personal = true;
    try {
        $stmt = $conn->prepare("SELECT preference_value FROM user_preferences WHERE analyst_id = ? AND preference_key = 'ticket_workspace'");
        $stmt->execute([$analystId]);
        $value = $stmt->fetchColumn();
        $personal = ticketWorkspacePersonal($value === false ? null : $value);
    } catch (Throwable) {
        // A preferences table not created yet: nobody has opted out.
    }
    return ['mode' => ticketWorkspaceMode($conn)->value, 'personal' => $personal, 'ready' => ticketWorkspaceReady($conn)];
}
