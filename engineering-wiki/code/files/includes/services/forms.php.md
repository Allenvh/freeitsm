# includes/services/forms.php

> PHP - 3022 lines

FormsService — the single home for the forms module's write rules: form save (create + id-based field sync), version fork, delete (leaf/chain), submission create (with the form.submitted workflow dispatch) and submission delete.

Shared by the UI endpoints (api/forms/*.php) and the REST API
(api/v1/resources/forms.php). Each caller passes an ActorContext + canonical
input; this layer validates + writes and returns the affected id(s) or throws
ServiceError. It never emits HTTP. The AI form-generation + settings endpoints
are UI-only and stay out of here.

Canonical behaviour = the API resource's (see docs/design/service-layer.md):
an empty field label / unknown field_type is a 422 (the UI silently dropped /
blindly stored), an unknown field id in a submission is a 422 (was a raw FK
error), a frozen (non-leaf) version can't be edited/forked (409), and delete
is leaf-only unless the chain flag is set. Timestamps are written UTC.

⚙️ Side effect: a successful submission dispatches the `form.submitted`
workflow event with a label-keyed answers map (+ the first email answer) —
the "new starter form → tickets" automation. It fires after commit and its
errors are swallowed so a workflow can never break a submission — caught as
Throwable, not Exception, because these run after the commit and a PHP Error
escaping there would fail a submission that is already saved.

## Classes

- [FormsService](/code/classes/FormsService.md) (class, line 40)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 568 | `FormsService::closeEffect` | read [system_settings](/code/tables/system_settings.md) |  |
| 592 | `FormsService::listCollections` | read [forms](/code/tables/forms.md), read [form_submissions](/code/tables/form_submissions.md), read [form_collections](/code/tables/form_collections.md), read [analysts](/code/tables/analysts.md) |  |
| 615 | `FormsService::collectionForms` | read [forms](/code/tables/forms.md) |  |
| 647 | `FormsService::setCollectionForms` | read [form_collections](/code/tables/form_collections.md) |  |
| 662 | `FormsService::setCollectionForms` | read [forms](/code/tables/forms.md) |  |
| 686 | `FormsService::setCollectionForms` | **update** [forms](/code/tables/forms.md) (`collection_id`, `modified_by`, `modified_date`) |  |
| 690 | `FormsService::setCollectionForms` | **update** [forms](/code/tables/forms.md) (`collection_id`, `modified_by`, `modified_date`) |  |
| 700 | `FormsService::setCollectionForms` | **update** [forms](/code/tables/forms.md) (`collection_id`, `modified_by`, `modified_date`) |  |
| 721 | `FormsService::formsForPicker` | read [forms](/code/tables/forms.md), read [form_collections](/code/tables/form_collections.md) |  |
| 747 | `FormsService::collectionSubmissions` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |
| 770 | `FormsService::collectionSubmissions` | read [form_submission_data](/code/tables/form_submission_data.md) |  |
| 788 | `FormsService::collectionSubmissions` | read [form_fields](/code/tables/form_fields.md) |  |
| 837 | `FormsService::saveCollection` | read [form_collections](/code/tables/form_collections.md) |  |
| 844 | `FormsService::saveCollection` | **update** [form_collections](/code/tables/form_collections.md) (`name`, `description`, `modified_date`) |  |
| 847 | `FormsService::saveCollection` | read [form_collections](/code/tables/form_collections.md) |  |
| 855 | `FormsService::saveCollection` | **insert** [form_collections](/code/tables/form_collections.md) (`name`, `description`, `created_by`, `created_date`, `modified_date`) |  |
| 878 | `FormsService::setCollectionClosed` | **update** [form_collections](/code/tables/form_collections.md) (`closed_datetime`, `closed_by`, `modified_date`) |  |
| 879 | `FormsService::setCollectionClosed` | **update** [form_collections](/code/tables/form_collections.md) (`closed_datetime`, `closed_by`, `modified_date`) |  |
| 884 | `FormsService::setCollectionClosed` | read [form_collections](/code/tables/form_collections.md) |  |
| 904 | `FormsService::deleteCollection` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 911 | `FormsService::deleteCollection` | **delete** [form_collections](/code/tables/form_collections.md) |  |
| 932 | `FormsService::submissionsBlockedBy` | read [forms](/code/tables/forms.md), read [form_collections](/code/tables/form_collections.md) |  |
| 959 | `FormsService::portalCatalogueFilter` | read [form_collections](/code/tables/form_collections.md) |  |
| 1005 | `FormsService::saveForm` | **update** [forms](/code/tables/forms.md) (`title`, `description`, `is_active`, `is_portal_visible`, `requires_approval`, `approver_id`, `submission_actions`, `modified_by`) |  |
| 1058 | `FormsService::saveForm` | **insert** [forms](/code/tables/forms.md) (`title`, `description`, `is_active`, `is_portal_visible`, `requires_approval`, `approver_id`, `submission_actions`, `created_by`, `modified_by`, `version_number`, `created_date`, `modified_date`) |  |
| 1102 | `FormsService::deleteForm` | read [forms](/code/tables/forms.md) |  |
| 1113 | `FormsService::deleteForm` | read [forms](/code/tables/forms.md) |  |
| 1131 | `FormsService::deleteForm` | **delete** [form_submission_data](/code/tables/form_submission_data.md), read [form_submissions](/code/tables/form_submissions.md) |  |
| 1135 | `FormsService::deleteForm` | **delete** [form_submissions](/code/tables/form_submissions.md) |  |
| 1136 | `FormsService::deleteForm` | **delete** [form_fields](/code/tables/form_fields.md) |  |
| 1139 | `FormsService::deleteForm` | **delete** [forms](/code/tables/forms.md) |  |
| 1216 | `FormsService::actionLists` | read [forms](/code/tables/forms.md) |  |
| 1264 | `FormsService::createVersion` | **insert** [forms](/code/tables/forms.md) (`title`, `description`, `is_active`, `is_portal_visible`, `requires_approval`, `approver_id`, `submission_actions`, `created_by`, `modified_by`, `parent_form_id`, `version_number{$colSql}`, `created_date`) |  |
| 1335 | `FormsService::createVersion` | read [form_fields](/code/tables/form_fields.md) |  |
| 1344 | `FormsService::createVersion` | **insert** [form_fields](/code/tables/form_fields.md) (`form_id`, `field_type`, `label`, `options`, `is_required`, `sort_order`, `config`) |  |
| 1353 | `FormsService::createVersion` | **update** [form_fields](/code/tables/form_fields.md) (`config`) |  |
| 1374 | `FormsService::createVersion` | **update** [forms](/code/tables/forms.md) (`layout`) |  |
| 1390 | `FormsService::createVersion` | **insert** [form_audiences](/code/tables/form_audiences.md) (`form_id`, `principal_type`, `principal_id`) |  |
| 1465 | `FormsService::submitForm` | read [form_fields](/code/tables/form_fields.md) |  |
| 1736 | `FormsService::submitForm` | **insert** [form_submissions](/code/tables/form_submissions.md) (`$cols`) |  |
| 1739 | `FormsService::submitForm` | **insert** [form_submission_data](/code/tables/form_submission_data.md) (`submission_id`, `field_id`, `field_value`) |  |
| 1839 | `FormsService::deleteSubmission` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 1845 | `FormsService::deleteSubmission` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 1853 | `FormsService::deleteSubmission` | **delete** [form_submission_data](/code/tables/form_submission_data.md) |  |
| 1854 | `FormsService::deleteSubmission` | **delete** [form_submissions](/code/tables/form_submissions.md) |  |
| 1875 | `FormsService::loadFormRow` | read [forms](/code/tables/forms.md) |  |
| 2144 | `FormsService::formAudiences` | read [form_audiences](/code/tables/form_audiences.md) |  |
| 2173 | `FormsService::setFormAudiences` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 2186 | `FormsService::setFormAudiences` | **delete** [form_audiences](/code/tables/form_audiences.md) |  |
| 2190 | `FormsService::setFormAudiences` | **insert** [form_audiences](/code/tables/form_audiences.md) (`form_id`, `principal_type`, `principal_id`) |  |
| 2219 | `FormsService::portalAudienceSql` | read [form_audiences](/code/tables/form_audiences.md), read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 2243 | `FormsService::portalCanUseForm` | read [forms](/code/tables/forms.md) |  |
| 2307 | `FormsService::saveDraft` | read [form_fields](/code/tables/form_fields.md) |  |
| 2334 | `FormsService::saveDraft` | **insert** [form_drafts](/code/tables/form_drafts.md) (`form_id`, `owner_kind`, `owner_id`, `answers`, `created_date`, `modified_date`) |  |
| 2365 | `FormsService::loadDraft` | read [form_drafts](/code/tables/form_drafts.md) |  |
| 2377 | `FormsService::loadDraft` | read [forms](/code/tables/forms.md) |  |
| 2400 | `FormsService::deleteDraft` | **delete** [form_drafts](/code/tables/form_drafts.md) |  |
| 2415 | `FormsService::draftsForOwner` | read [form_drafts](/code/tables/form_drafts.md) |  |
| 2959 | `FormsService::syncFields` | read [form_fields](/code/tables/form_fields.md) |  |
| 2965 | `FormsService::syncFields` | **update** [form_fields](/code/tables/form_fields.md) (`field_type`, `label`, `options`, `is_required`, `sort_order`, `is_deleted`) |  |
| 2970 | `FormsService::syncFields` | **insert** [form_fields](/code/tables/form_fields.md) (`form_id`, `field_type`, `label`, `options`, `is_required`, `sort_order`) |  |
| 2992 | `FormsService::syncFields` | **update** [form_fields](/code/tables/form_fields.md) (`is_deleted`) |  |
| 2995 | `FormsService::syncFields` | **update** [form_fields](/code/tables/form_fields.md) (`is_deleted`) |  |
| 2999 | `FormsService::syncFields` | **update** [form_fields](/code/tables/form_fields.md) (`config`) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/form_logic.php](/code/files/includes/form_logic.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/forms/ai_generate.php](/code/files/api/forms/ai_generate.php.md), [api/forms/audience.php](/code/files/api/forms/audience.php.md), [api/forms/create_version.php](/code/files/api/forms/create_version.php.md), [api/forms/delete_collection.php](/code/files/api/forms/delete_collection.php.md), [api/forms/delete_form.php](/code/files/api/forms/delete_form.php.md), [api/forms/delete_submission.php](/code/files/api/forms/delete_submission.php.md), [api/forms/draft.php](/code/files/api/forms/draft.php.md), [api/forms/get_collections.php](/code/files/api/forms/get_collections.php.md), [api/forms/get_collection_form_picker.php](/code/files/api/forms/get_collection_form_picker.php.md), [api/forms/get_collection_submissions.php](/code/files/api/forms/get_collection_submissions.php.md), [api/forms/get_form.php](/code/files/api/forms/get_form.php.md), [api/forms/get_forms.php](/code/files/api/forms/get_forms.php.md), [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md), [api/forms/image.php](/code/files/api/forms/image.php.md), [api/forms/lookup_search.php](/code/files/api/forms/lookup_search.php.md), [api/forms/save_collection.php](/code/files/api/forms/save_collection.php.md), [api/forms/save_collection_forms.php](/code/files/api/forms/save_collection_forms.php.md), [api/forms/save_form.php](/code/files/api/forms/save_form.php.md), [api/forms/save_settings.php](/code/files/api/forms/save_settings.php.md), [api/forms/set_collection_closed.php](/code/files/api/forms/set_collection_closed.php.md), [api/forms/submit_form.php](/code/files/api/forms/submit_form.php.md), [api/self-service/draft.php](/code/files/api/self-service/draft.php.md), [api/self-service/get_catalogue.php](/code/files/api/self-service/get_catalogue.php.md), [api/self-service/get_catalogue_form.php](/code/files/api/self-service/get_catalogue_form.php.md), [api/self-service/submit_catalogue_form.php](/code/files/api/self-service/submit_catalogue_form.php.md), [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md)

**Calls:** [formLogicVisibility()](/code/functions/formLogicVisibility.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

