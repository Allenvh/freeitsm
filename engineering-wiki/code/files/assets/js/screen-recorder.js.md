# assets/js/screen-recorder.js

> JavaScript - 341 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Self Service Developer Guide](https://github.com/edmozley/freeitsm/wiki/Self-Service-Developer-Guide)

Screen recording for the self-service portal — the ONE implementation.

WHY THIS FILE EXISTS
--------------------
Recording started life on the new-ticket form. Letting people record while
REPLYING needed the same ~250 lines: capture, MIME negotiation, the countdown,
the preview, the claim-or-discard decision, the upload. Copying it would have
meant two recorders drifting apart — one growing a fix for a browser quirk the
other never gets, which is exactly how the HTML sanitiser ended up protecting
the portal and not the inbox.

It pairs with:
  self-service/includes/record-modal.php  — the markup (ids matched here)
  assets/css/self-service.css             — .rec-modal and friends
  api/self-service/upload_recording.php   — the pending upload

The module owns the modal and the capture; the PAGE owns what happens to a
claimed recording (attach to a new ticket / to a reply), which arrives via the
onClaimed callback. That is the whole seam between them.

ONE modal per page — it binds by element id, matching the single partial.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `onClaimed()` | 33 | Default claim handler - does nothing until the page passes its own to `init()`. |
| `el(id)` | 48 | Shorthand for document.getElementById. |
| `t(key, params)` | 51 | Translates a key through the shared i18n helper, or returns the key when it is not loaded. |
| `escapeHtml(text)` | 56 | HTML-escapes text through a text node. |
| `formatDuration(seconds)` | 63 | Formats seconds as m:ss. |
| `notifyError(message)` | 70 | Shows an error as a toast, or an alert when the toast helper is not loaded. |
| `isSupported()` | 85 | iOS Safari has no getDisplayMedia at all. Callers hide their Record button rather than letting someone press it and meet a confusing failure. |
| `hasUnclaimed()` | 90 | A captured-but-unclaimed recording — what the submit guard asks about. |
| `isRecording()` | 95 | Whether the screen is being captured right now. |
| `open()` | 100 | Opens the recording modal. |
| `close()` | 116 | Closing DISCARDS an unclaimed recording — cancelling should not silently leave something attached. That is the opposite of the submit guard, which blocks: there they were heading onward and might lose work; here they have explicitly said no. |
| `pickRecorderMime()` | 129 | Returns the first video format the browser can record - MP4 / H.264 preferred, then WebM - or empty to use its default. |
| `start()` | 155 | Asks the browser to share a screen (plus the microphone when ticked, carrying on without it if refused) and starts recording. |
| `ondataavailable(e)` | 189 | Keeps each non-empty chunk the recorder hands over. |
| `onstop()` | 196 | Builds the recording from its chunks, releases the screen and microphone, stops the timer and shows the preview. |
| `stop()` | 232 | Stops the recording, if one is running. |
| `showPreview()` | 237 | Shows the recorded video with its length and the Use / Discard buttons. |
| `discard()` | 253 | Throws away the captured recording and puts the modal back to ready. |
| `use()` | 278 | Upload and hand the result to the page. |
| `init(options)` | 320 | Sets the upload URL, maximum length in seconds and claim callback for this page. |

