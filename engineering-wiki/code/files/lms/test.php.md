# lms/test.php

> PHP - 256 lines

A candidate's competency test - the public page a test link opens.

NO authentication: the link's token is the credential (only its hash is
stored). Rendered entirely from the sitting's frozen snapshot, and NEVER with
the marks or explanations in it - the page holds the questions and answer
texts and nothing a candidate could use to work out the key.

States: ready (intro + Start) -> in_progress (questions, timer, autosave) ->
submitted. A cancelled or unknown link shows one message; an out-of-date
unused link says so. Time is the server's: the remaining seconds are worked
out here and every save is checked again in api/lms/test_public.php.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md), [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [i18nGuardedInit()](/code/functions/i18nGuardedInit.md), [lmsCtDeadline()](/code/functions/lmsCtDeadline.md), [lmsCtFinalise()](/code/functions/lmsCtFinalise.md), [lmsCtFindSitting()](/code/functions/lmsCtFindSitting.md), [lmsCtSettings()](/code/functions/lmsCtSettings.md), [lmsCtState()](/code/functions/lmsCtState.md), [pageTitle()](/code/functions/pageTitle.md), [systemName()](/code/functions/systemName.md), [tr()](/code/functions/tr.md), [trh()](/code/functions/trh.md), [trLocale()](/code/functions/trLocale.md)

