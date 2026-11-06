# api/lms/tests.php

> API endpoint - 473 lines

LMS API: competency tests - tests, the question bank, candidates' sittings and the Competency tests settings tab. Every action sits behind Cap::LMS_TESTS (sensitive: candidates are people outside the organisation). The rules live in includes/lms/competency_tests.php; this file is the door.

GET  ?action=tests | test&id= | bank&... | skills | sittings[&test_id=] | sitting&id= | settings
  POST {action: save_test | delete_test | generate | add_questions | remove_question | move_question
                | save_question | set_status | delete_question
                | create_sitting | new_link | cancel_sitting | delete_sitting | save_notes
                | save_settings}

## The endpoint

| | |
|---|---|
| URL | `api/lms/tests.php` |
| Protected by | Module access: lms (requireModuleAccessJson); Permission: Cap::LMS_TESTS (requireCapabilityJson) |
| Reads | `GET action`, `GET id`, `GET q`, `GET status`, `GET test_id` |
| Called from | [assets/js/lms-tests.js](/code/files/assets/js/lms-tests.js.md) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ctOut()](/code/functions/ctOut.md) | 37 | Echoes {success: true} merged with `$a` as JSON, then stops. |
| [ctFail()](/code/functions/ctFail.md) | 44 | Sends an HTTP status and {success: false, error}, then stops. |
| [ctSittingRow()](/code/functions/ctSittingRow.md) | 51 | A sitting row as the analyst pages see it - never the token hash. |
| [ctLoadSitting()](/code/functions/ctLoadSitting.md) | 77 | Loads one candidate sitting with its test's title, or answers 404 "Candidate not found" and stops. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `ctLoadSitting` | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md), read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 94 |  | read [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md), read [lms_ct_questions](/code/tables/lms_ct_questions.md), read [lms_ct_sittings](/code/tables/lms_ct_sittings.md), read [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md), read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 106 |  | read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 110 |  | read [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md) |  |
| 112 |  | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |
| 133 |  | read [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md), read [lms_ct_questions](/code/tables/lms_ct_questions.md) |  |
| 143 |  | read [lms_ct_questions](/code/tables/lms_ct_questions.md) |  |
| 152 |  | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md), read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 213 |  | **update** [lms_ct_tests](/code/tables/lms_ct_tests.md) (`title`, `role_description`, `time_limit_minutes`, `pass_mark`, `updated_datetime`) |  |
| 215 |  | read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 219 |  | **insert** [lms_ct_tests](/code/tables/lms_ct_tests.md) (`title`, `role_description`, `time_limit_minutes`, `pass_mark`, `created_by_analyst_id`, `created_datetime`) |  |
| 227 |  | **update** [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md) (`skill`, `difficulty`, `format`, `question_count`, `sort_order`) |  |
| 228 |  | **insert** [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md) (`test_id`, `skill`, `difficulty`, `format`, `question_count`, `sort_order`) |  |
| 238 |  | **delete** [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md) |  |
| 249 |  | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |
| 252 |  | **update** [lms_ct_tests](/code/tables/lms_ct_tests.md) (`is_archived`, `updated_datetime`) |  |
| 255 |  | **delete** [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md) |  |
| 256 |  | **delete** [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md) |  |
| 257 |  | **delete** [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 266 |  | read [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md), read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 272 |  | read [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md), read [lms_ct_questions](/code/tables/lms_ct_questions.md) |  |
| 280 |  | read [lms_ct_questions](/code/tables/lms_ct_questions.md), read [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md) |  |
| 296 |  | read [lms_ct_questions](/code/tables/lms_ct_questions.md) |  |
| 306 |  | **update** [lms_ct_tests](/code/tables/lms_ct_tests.md) (`updated_datetime`) |  |
| 312 |  | read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 320 |  | read [lms_ct_questions](/code/tables/lms_ct_questions.md) |  |
| 328 |  | **delete** [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md) |  |
| 336 |  | **update** [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md) (`sort_order`) |  |
| 347 |  | **update** [lms_ct_questions](/code/tables/lms_ct_questions.md) (`skill`, `difficulty`, `format`, `question_text`, `answers_json`, `explanation`, `status`, `updated_datetime`) |  |
| 364 |  | **update** [lms_ct_questions](/code/tables/lms_ct_questions.md) (`status`, `updated_datetime`) |  |
| 372 |  | **delete** [lms_ct_questions](/code/tables/lms_ct_questions.md) |  |
| 388 |  | read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |
| 393 |  | **insert** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) (`test_id`, `candidate_name`, `candidate_email`, `token_hash`, `snapshot_json`, `time_limit_minutes`, `expires_datetime`, `created_by_analyst_id`, `created_datetime`) |  |
| 423 |  | **update** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) (`token_hash`, `expires_datetime`) |  |
| 430 |  | **update** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) (`is_cancelled`) |  |
| 435 |  | **delete** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |
| 442 |  | **update** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) (`notes`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md)

**Calls:** [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsCtAttach()](/code/functions/lmsCtAttach.md), [lmsCtBuildSnapshot()](/code/functions/lmsCtBuildSnapshot.md), [lmsCtFinaliseOverdue()](/code/functions/lmsCtFinaliseOverdue.md), [lmsCtGenerate()](/code/functions/lmsCtGenerate.md), [lmsCtGradedMarks()](/code/functions/lmsCtGradedMarks.md), [lmsCtInsertQuestion()](/code/functions/lmsCtInsertQuestion.md), [lmsCtLinkPath()](/code/functions/lmsCtLinkPath.md), [lmsCtNewToken()](/code/functions/lmsCtNewToken.md), [lmsCtParseMarks()](/code/functions/lmsCtParseMarks.md), [lmsCtPurge()](/code/functions/lmsCtPurge.md), [lmsCtSaveSetting()](/code/functions/lmsCtSaveSetting.md), [lmsCtSettings()](/code/functions/lmsCtSettings.md), [lmsCtState()](/code/functions/lmsCtState.md), [lmsCtTestQuestions()](/code/functions/lmsCtTestQuestions.md), [lmsCtValidateQuestion()](/code/functions/lmsCtValidateQuestion.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ssGetSendingMailbox()](/code/functions/ssGetSendingMailbox.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [systemName()](/code/functions/systemName.md)

