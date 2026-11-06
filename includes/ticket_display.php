<?php
/**
 * How a ticket's messages are displayed — the collapsing of long ones.
 *
 * From discussion #104: long email chains bury the thing you opened the ticket
 * to read. Same idea as Gmail's "···", and Zammad does it too.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * 🔑 WHY HEIGHT AND NOT LINES
 *
 * The request asked for a line threshold, and measuring real inbound mail says
 * that is the wrong unit. On a live install: 122 inbound emails, averaging 8,337
 * characters of which only ~1,800 are visible text — 38 of them laid out with
 * `<table>` and 26 carrying their own `<style>`. A vendor notification like
 * "Your Microsoft invoice is ready" is 57,750 characters and a handful of source
 * lines, and renders about a metre tall.
 *
 * So the trigger is RENDERED HEIGHT, measured in the browser after the message
 * is laid out, which is the only number that corresponds to what somebody
 * actually has to scroll past. The SETTING is still expressed in lines, because
 * "collapse after about 12 lines" is a sentence an administrator can reason
 * about and "collapse after 260 pixels" is not — the conversion lives in one
 * place, below.
 *
 * ⚠️ Nothing here deletes anything. Collapsing is presentation: the full message
 * is in the page, one tap away, always. That matters because the quote-boundary
 * problem is genuinely hard — every mail client marks a quoted chain differently,
 * plenty do not mark it at all, and a forward can look identical to a reply — so
 * any approach is wrong some of the time, and a wrong boundary should cost a
 * click, never a fact.
 *
 * @wiki Reading-Long-Tickets-Developer-Guide
 */

/** Roughly what one line of message text occupies once rendered. */
const TICKET_COLLAPSE_LINE_PX = 22;

/**
 * The display settings, with defaults, in one place.
 *
 * Defaults chosen so an install that never opens the settings behaves sensibly
 * rather than surprisingly: collapsing ON (it is what was asked for), the newest
 * message always open (it is the one you came for), and the threshold generous
 * enough that ordinary human replies are never touched.
 */
function ticketDisplaySettings(?PDO $conn = null): array
{
    $defaults = [
        // Collapse long messages at all.
        'collapse_enabled'      => 1,
        // About how many lines before a message is collapsed.
        'collapse_lines'        => 12,
        // The newest message in the thread is always shown in full.
        'collapse_expand_newest' => 1,
        // Collapse quoted history separately from length — a two-line reply with
        // a thousand lines of chain under it is short by any line count and still
        // unreadable.
        'collapse_quoted'       => 1,
        // Remember, per analyst, which messages they opened.
        'collapse_remember'     => 1,

        // Fold the older part of a long ticket into a list you can open.
        // A long ticket is a different problem from a long message and needs
        // its own answer (idea #4).
        'group_older'           => 1,
        // How many of the most recent messages stay expanded as messages.
        'group_show'            => 6,
        // Point out a message that has arrived before (idea #10).
        'flag_duplicates'       => 1,
    ];

    try {
        $conn = $conn ?: connectToDatabase();
        $keys = array_map(fn($k) => 'ticket_' . $k, array_keys($defaults));
        $place = implode(',', array_fill(0, count($keys), '?'));
        $stmt = $conn->prepare("SELECT setting_key, setting_value FROM system_settings WHERE setting_key IN ($place)");
        $stmt->execute($keys);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $k = substr($row['setting_key'], strlen('ticket_'));
            if (!array_key_exists($k, $defaults)) continue;
            $defaults[$k] = in_array($k, ['collapse_lines', 'group_show'], true)
                // Clamped, not trusted. 4 lines is a peephole and 80 is no
                // collapsing at all; both are worse than the default.
                ? max($k === 'group_show' ? 2 : 4, min(80, (int)$row['setting_value']))
                : (int)(bool)(int)$row['setting_value'];
        }
    } catch (Exception) {
        // Defaults. A reading pane that cannot reach the settings still has to render.
    }

    // The one place lines become pixels.
    $defaults['collapse_px'] = $defaults['collapse_lines'] * TICKET_COLLAPSE_LINE_PX;
    return $defaults;
}
