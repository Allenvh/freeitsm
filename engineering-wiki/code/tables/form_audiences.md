# form_audiences

A named exercise that several forms' submissions belong to - "Staff
       Survey 2026", "ISO 27001 evidence round". Optional: a laptop request
       form belongs to nothing.

       `closed_datetime` NULL = open. What closing DOES is an operator setting
       (`forms_collection_close_effect`), not a property of the row, because
       different organisations mean different things by it - and whatever it
       means, it is evaluated when asked and NEVER written onto the forms. If
       closing stamped is_portal_visible = 0 on three forms, reopening would
       turn all three back on, including one deliberately kept off the portal. WHO may request a form from the self-service catalogue (GH #145).
       🔑 NO ROWS MEANS EVERYONE — the normal case and every pre-existing form,
       so this ships with no migration and nothing silently disappearing.
       ⚠️ It restricts the CATALOGUE, not the module: analysts reach forms
       through module access and are unaffected.
       🔴 Carried forward by `createVersion()`, or pressing Save would republish a
       restricted form to every customer.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `form_id` | `INT NOT NULL` |  |
| `principal_type` | `VARCHAR(12) NOT NULL` | 'user_group' today. A column rather than a form_user_groups table because naming individuals is the obvious next ask, and the column costs nothing now where a migration would cost something then. |
| `principal_id` | `INT NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1390 | `FormsService::createVersion` | insert | `form_id`, `principal_type`, `principal_id` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2186 | `FormsService::setFormAudiences` | delete |  |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2190 | `FormsService::setFormAudiences` | insert | `form_id`, `principal_type`, `principal_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/forms/get_forms.php](/code/files/api/forms/get_forms.php.md) | L44 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::createVersion L1390, FormsService::formAudiences L2144, FormsService::portalAudienceSql L2219 |  |

