/**
 * Notification chime — per-analyst preference `notification_sound`.
 *
 * The sounds are synthesised with the Web Audio API rather than shipped as
 * audio files. A two-note bell is a handful of oscillator nodes, so generating
 * it means no binary asset to serve, cache-bust or license, and offering
 * several distinct sounds costs nothing extra.
 *
 * window.NOTIFICATION_SOUND is written by renderWaffleMenuJS() from the saved
 * preference. 'off' — the default — means silence, and is why every consumer
 * can call playNotificationSound() unconditionally.
 *
 * Consumers: the notification bell and the war-room mention alerts, both in
 * includes/waffle-menu.php, plus the preview on the Preferences page.
 */
(function () {
    'use strict';

    // Each note is [frequency Hz, start offset s, duration s, peak gain].
    // Kept quiet on purpose: this fires while somebody is working, so it has to
    // read as a cue, not an alarm.
    var SOUNDS = {
        chime: { type: 'sine',     notes: [[880, 0, 0.85, 0.16], [1318.5, 0.11, 0.95, 0.13]] },
        ping:  { type: 'sine',     notes: [[1568, 0, 0.32, 0.14]] },
        knock: { type: 'triangle', notes: [[196, 0, 0.16, 0.26], [147, 0.12, 0.20, 0.20]] }
    };

    var ctx = null;

    // Built on first use, not on load: an AudioContext per page would be a
    // resource nearly every analyst never needs, and some browsers log a
    // warning for one created outside a gesture.
    function context() {
        if (ctx) return ctx;
        var Ctor = window.AudioContext || window.webkitAudioContext;
        if (!Ctor) return null;
        try { ctx = new Ctor(); } catch (e) { ctx = null; }
        return ctx;
    }

    /**
     * Play a chime.
     *
     * Pass a key to force a particular sound (the Preferences preview, which
     * has to play what is selected rather than what is saved). Pass nothing to
     * play whatever this analyst chose.
     *
     * Silent-fails throughout. A browser with no Web Audio, or one refusing to
     * start audio, must never turn a notification into an error on screen.
     */
    function playNotificationSound(key) {
        var spec = SOUNDS[key || window.NOTIFICATION_SOUND || 'off'];
        if (!spec) return;                      // 'off', or an unrecognised stored value

        var ac = context();
        if (!ac) return;
        if (ac.state === 'suspended') {
            try { ac.resume(); } catch (e) { /* the browser's call to make */ }
        }

        var now = ac.currentTime;
        spec.notes.forEach(function (n) {
            try {
                var osc  = ac.createOscillator();
                var gain = ac.createGain();
                osc.type = spec.type;
                osc.frequency.value = n[0];

                var start = now + n[1];
                // Ramped rather than switched on: a sine starting at full
                // amplitude produces an audible click before the note.
                // Exponential ramps cannot reach 0, hence the 0.0001 floor.
                gain.gain.setValueAtTime(0.0001, start);
                gain.gain.exponentialRampToValueAtTime(n[3], start + 0.012);
                gain.gain.exponentialRampToValueAtTime(0.0001, start + n[2]);

                osc.connect(gain);
                gain.connect(ac.destination);
                osc.start(start);
                osc.stop(start + n[2] + 0.02);
            } catch (e) { /* one note failing is not worth abandoning the rest */ }
        });
    }

    // Browsers block audio until the page has been interacted with. A poll
    // callback a minute later is not an interaction, so the context has to be
    // opened while a real gesture is still in hand — otherwise the very first
    // chime of a page is the one nobody hears. Only worth doing for analysts
    // who actually turned a sound on.
    function warm() {
        if (!window.NOTIFICATION_SOUND || window.NOTIFICATION_SOUND === 'off') return;
        var ac = context();
        if (ac && ac.state === 'suspended') {
            try { ac.resume(); } catch (e) { /* ignore */ }
        }
    }
    ['pointerdown', 'keydown'].forEach(function (ev) {
        document.addEventListener(ev, warm, { once: true, passive: true });
    });

    window.playNotificationSound = playNotificationSound;
    // The Preferences page builds its dropdown from this, so adding a sound
    // above is the only edit needed to offer it.
    window.NOTIFICATION_SOUND_KEYS = Object.keys(SOUNDS);
})();
