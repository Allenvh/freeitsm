# api/forms/audience.php

> API endpoint - 90 lines

Who may request a form from the self-service catalogue (GH #145).

GET  ?form_id=N        — the groups that exist, and which are selected
  POST {form_id, group_ids} — replace the selection ([] = everyone)

🔑 The groups are PEOPLE groups (Tickets → Users → Groups). They are the only
one of the three group concepts in this product that holds portal users as
well as analysts, which is exactly why they are the right principal here — a
team holds analysts, and analysts do not use the catalogue.

⚠️ This is an ANALYST endpoint. It decides what CUSTOMERS may see, but the
deciding is administration, so it is gated on the Forms module like every
other form setting. The enforcement lives in the portal's own queries.

## The endpoint

| | |
|---|---|
| URL | `api/forms/audience.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `GET form_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 62 |  | read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [serviceErrorHttpStatus()](/code/functions/serviceErrorHttpStatus.md)

