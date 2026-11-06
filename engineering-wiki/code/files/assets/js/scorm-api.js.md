# assets/js/scorm-api.js

> JavaScript - 292 lines

SCORM API Bridge

Provides both SCORM 1.1/1.2 (window.API) and SCORM 2004 (window.API_1484_11)
objects. SCORM content running in the iframe walks up the frame tree to find
these objects and calls their methods to read/write learner data.

Requires window.SCORM_CONFIG = { courseId, analystId, scormVersion, apiEndpoint }

## Functions

| Function | Line | What it does |
|---|--:|---|
| `loadCmiData()` | 32 | Loads the learner's stored CMI values for the course from the API endpoint, synchronously because SCORM calls must return a value at once. |
| `saveCmiData()` | 52 | Posts the CMI elements changed since the last save to the API endpoint, synchronously; returns true when nothing needed saving or the server answered 200. |
| `doInitialize()` | 76 | Starts a SCORM session - loads the stored CMI data; error 101 when already initialised. |
| `doFinish()` | 92 | Ends the SCORM session, saving any changed values; errors 301 when not initialised and 111 when already finished. |
| `doGetValue(element)` | 112 | Returns a CMI element's value as a string, working out ._count from the stored array keys and answering ._children from a fixed list. |
| `doSetValue(element, value)` | 146 | Stores a CMI element value and marks it for the next save. |
| `doCommit()` | 171 | Saves the changed CMI values now (LMSCommit / Commit). |
| `doGetLastError()` | 182 | Returns the error code of the last API call, "0" for none. |
| `doGetErrorString(code)` | 195 | i18n-exempt: these strings are defined by the SCORM 2004 run-time specification, not by FreeITSM. They are returned to the CONTENT PACKAGE through `GetErrorString()`, which compares them and logs them; a course built to the spec expects "Not Initialized" in English whatever language the learner reads. Translating them would break conformance, and no learner ever sees them. The header block above says the same for Content-Type, which is an HTTP header name. |
| `doGetDiagnostic(code)` | 211 | Returns the diagnostic text for an error code - the same as its error string. |
| `isReadOnly(element)` | 221 | Whether a CMI element is read-only in SCORM 1.2 or 2004, including every ._count and ._children element. |
| `getChildrenFor(element)` | 240 | Returns the comma-separated child list for a ._children element, or empty for one it does not know. |
| `LMSInitialize(p)` | 253 | SCORM 1.2: starts the session. |
| `LMSFinish(p)` | 255 | SCORM 1.2: ends the session and saves. |
| `LMSGetValue(el)` | 257 | SCORM 1.2: reads a CMI element. |
| `LMSSetValue(el, val)` | 259 | SCORM 1.2: writes a CMI element. |
| `LMSCommit(p)` | 261 | SCORM 1.2: saves changed values now. |
| `LMSGetLastError()` | 263 | SCORM 1.2: the last error code. |
| `LMSGetErrorString(c)` | 265 | SCORM 1.2: the text for an error code. |
| `LMSGetDiagnostic(c)` | 267 | SCORM 1.2: the diagnostic for an error code. |
| `Initialize(p)` | 274 | SCORM 2004: starts the session. |
| `Terminate(p)` | 276 | SCORM 2004: ends the session and saves. |
| `GetValue(el)` | 278 | SCORM 2004: reads a CMI element. |
| `SetValue(el, val)` | 280 | SCORM 2004: writes a CMI element. |
| `Commit(p)` | 282 | SCORM 2004: saves changed values now. |
| `GetLastError()` | 284 | SCORM 2004: the last error code. |
| `GetErrorString(c)` | 286 | SCORM 2004: the text for an error code. |
| `GetDiagnostic(c)` | 288 | SCORM 2004: the diagnostic for an error code. |

## Loaded by

- [lms/player.php](/code/files/lms/player.php.md)
- [self-service/course.php](/code/files/self-service/course.php.md)

