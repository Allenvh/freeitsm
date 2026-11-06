/**
 * Scheduling maths, shared by every screen that can schedule a ticket.
 *
 * There are now two: the inbox (its Schedule modal, reached from the ticket or
 * the right-click menu) and the tickets calendar (clicking an event). They must
 * agree exactly on what a duration means, what an all-day ticket is stored as,
 * and how a naive wall-clock value is parsed — so it is stated once here rather
 * than copied into both and left to drift. A third caller is coming when
 * scheduled work is pushed to Outlook.
 *
 * ⚠️ SCHEDULING VALUES ARE NAIVE WALL-CLOCK. "2pm" means 2pm to every analyst in
 * every timezone; they are stored without a zone and must never be round-tripped
 * through Date.toISOString(), which converts to UTC first and moved a 00:30
 * ticket to the previous DAY for anyone east of UTC (#1161).
 *
 * @wiki Ticket-Calendar-Sync-Developer-Guide
 */
(function () {
    'use strict';

    if (window.FreeITSMSchedule) return;      // self-guard against double loading

    // Mirrors TicketsService::SCHEDULE_DEFAULT_MINUTES. The server resolves the
    // same default for the calendar, so the two must not disagree about what an
    // unspecified duration means.
    var DEFAULT_MINUTES = 60;

    /** Pull a naive "YYYY-MM-DD HH:MM:SS" apart without letting Date touch it. */
    function parseNaive(value) {
        var m = String(value || '').replace('T', ' ')
            .match(/(\d{4})-(\d{2})-(\d{2})(?:[ ](\d{1,2}):(\d{2}))?/);
        if (!m) return null;
        return {
            date: m[1] + '-' + m[2] + '-' + m[3],
            time: m[4] ? (String(m[4]).length === 1 ? '0' + m[4] : m[4]) + ':' + m[5] : '00:00'
        };
    }

    /** A naive stamp from local date parts — never via toISOString(). */
    function formatNaive(d) {
        /** Pads a number to two digits. */
        function p(n) { return String(n).padStart(2, '0'); }
        return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate()) + ' '
             + p(d.getHours()) + ':' + p(d.getMinutes()) + ':' + p(d.getSeconds());
    }

    /**
     * Turn what the form holds into what the server stores.
     *
     * All day covers the whole date rather than a slot, stored 00:00–23:59 with
     * the flag set — matching calendar_events, so a reader that ignores the flag
     * still sees a sensible full-day block instead of a midnight sliver.
     *
     * A timed end is stepped in MINUTES from the start, so a duration running
     * past midnight rolls the date properly rather than being clamped.
     *
     * @return {{start: string, end: string, allDay: number}}
     */
    function toStoredRange(date, time, minutes, allDay) {
        if (allDay) {
            return { start: date + ' 00:00:00', end: date + ' 23:59:59', allDay: 1 };
        }
        var end = new Date(date + 'T' + time + ':00');
        end.setMinutes(end.getMinutes() + (parseInt(minutes, 10) || DEFAULT_MINUTES));
        return { start: date + ' ' + time + ':00', end: formatNaive(end), allDay: 0 };
    }

    /**
     * Recover the duration from a stored start/end pair.
     *
     * A ticket scheduled before the end column existed simply has none and gets
     * the default — it must never read as "zero minutes".
     */
    function durationMinutes(start, end) {
        var s = parseNaive(start), e = parseNaive(end);
        if (!s || !e) return DEFAULT_MINUTES;
        var diff = Math.round(
            (new Date(e.date + 'T' + e.time + ':00') - new Date(s.date + 'T' + s.time + ':00')) / 60000
        );
        return diff > 0 ? diff : DEFAULT_MINUTES;
    }

    window.FreeITSMSchedule = {
        DEFAULT_MINUTES: DEFAULT_MINUTES,
        parseNaive: parseNaive,
        formatNaive: formatNaive,
        toStoredRange: toStoredRange,
        durationMinutes: durationMinutes
    };
})();
