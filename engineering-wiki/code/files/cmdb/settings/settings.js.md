# cmdb/settings/settings.js

> JavaScript - 882 lines

CMDB Settings Page Handles three tabs: Classes (with nested per-class properties), Relationship Types, AI Integration.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `escapeHtml(s)` | 17 | Escapes text for safe use in HTML. |
| `slugify(s)` | 22 | Turns a name into a lower-case key with underscores ("Web Server" -> "web_server"). |
| `showInlineToast(msg, isError = false)` | 27 | Shows a toast - an error toast when isError, otherwise a success toast. |
| `postJson(url, body)` | 36 | Posts a JSON body and returns the parsed JSON reply. |
| `switchTab(tab)` | 48 | Switches settings tab and loads that tab's data. |
| `loadSidebarMode()` | 69 | Loads the analyst's cmdb_sidebar_mode preference ('always' or 'hover') into the left-panel radios, once. |
| `saveSidebarMode(value)` | 83 | Saves the analyst's left-panel preference through set_user_preference.php. |
| `loadClasses()` | 100 | Loads every CMDB class from get_classes.php and draws the Classes table. |
| `renderClasses()` | 117 | Draws the Classes table - name, key, description, property count (opens the properties modal), order, status, edit and delete. |
| `openClassModal(id = null)` | 144 | Opens the class modal, filled from an existing class or blank for a new one. |
| `closeClassModal()` | 158 | Closes the class modal. |
| `saveClass(ev)` | 161 | Saves the class modal through save_class.php and reloads the table. |
| `deleteClass(id, name)` | 183 | Asks for confirmation, deletes a class through delete_class.php and reloads the table. |
| `openPropsModal(classId)` | 214 | Opens the properties modal for one class and loads its properties. |
| `closePropsModal()` | 223 | Closes the properties modal and reloads the classes, since property counts may have changed. |
| `loadPropsForClass()` | 232 | Loads the open class's properties from get_class_properties.php and draws them. |
| `renderProps()` | 247 | Draws the properties table - label, key, type, target class, required, order, edit and delete. |
| `openPropertyModal(id = null)` | 276 | Opens the property modal, filled from an existing property or blank, with the target-class list and the options editor. |
| `closePropertyModal()` | 305 | Closes the property modal. |
| `onPropertyTypeChange()` | 310 | Shows the target-class and spreads-impact fields only for object references, and the options editor only for drop-downs. |
| `saveProperty(ev)` | 338 | Validates and saves the property through save_class_property.php - an object reference needs a target class, a drop-down at least one option. |
| `deleteProperty(id, label)` | 382 | Asks for confirmation, deletes a property through delete_class_property.php and reloads the list. |
| `loadRelTypes()` | 397 | Loads the relationship types from get_relationship_types.php and draws them. |
| `relTypeImpactCell(r)` | 415 | At-a-glance impact setting for the list. Reads with the row's own verbs so a scan of the table answers "which of these actually spread a failure?". |
| `renderRelTypes()` | 425 | Draws the relationship types table - verb, inverse verb, description, impact, order, status, edit and delete. |
| `syncRelTypeImpactOptions()` | 458 | Rewrite the impact-direction options using whatever is currently typed in the verb fields, so the choice reads as a sentence about the analyst's own estate ("everything that depends on it is affected") rather than as jargon. Both impact options share one frame — the only difference is which verb reads correctly from the affected object's side. |
| `openRelTypeModal(id = null)` | 473 | Opens the relationship type modal, filled from an existing type or blank, with the impact options worded from its verbs. |
| `closeRelTypeModal()` | 489 | Closes the relationship type modal. |
| `saveRelType(ev)` | 492 | Saves the relationship type, including its impact direction, through save_relationship_type.php and reloads the list. |
| `deleteRelType(id, verb)` | 515 | Asks for confirmation, deletes a relationship type through delete_relationship_type.php and reloads the list. |
| `loadCmdbAiExtras()` | 533 | Loads the CMDB's custom AI instructions into the AI tab; the provider settings belong to the shared AI panel. |
| `saveCmdbAiExtras(ev)` | 545 | Saves the CMDB's custom AI instructions through save_ai_settings.php. |
| `setAiStage(stage)` | 566 | Shows one stage of the AI suggest wizard and sets the primary and cancel buttons to suit it. |
| `openAiSuggestModal()` | 612 | Opens the AI suggest wizard for the open class and fetches its clarifying questions from ai_suggest_questions.php. |
| `closeAiSuggestModal()` | 637 | Closes the AI suggest wizard. |
| `renderAiQuestions()` | 643 | Draws the AI's questions, each with its examples and an answer box. |
| `collectAiAnswers()` | 655 | Reads the answered questions into aiSuggestAnswers, skipping blanks. |
| `aiPrimaryAction()` | 665 | The wizard's main button - from questions, fetch suggestions from ai_suggest_properties.php; from suggestions, add the ticked ones; from the result, close and reload. |
| `renderAiSuggestions()` | 698 | Draws the suggested properties as ticked checkboxes with type, required flag, options count, target class and the AI's reason. |
| `applyAiSuggestions()` | 725 | Saves each ticked suggestion as a property of the open class; an object reference to a class that does not exist yet creates that class first. |
| `renderAiResult(added, created, errors)` | 814 | Shows what was added, which classes were created on the way, and anything that failed. |

## API endpoints it calls

- [api/system/get_user_preference.php](/code/files/api/system/get_user_preference.php.md)
- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)
- [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md)
- [api/cmdb/save_class.php](/code/files/api/cmdb/save_class.php.md)
- [api/cmdb/delete_class.php](/code/files/api/cmdb/delete_class.php.md)
- [api/cmdb/get_class_properties.php](/code/files/api/cmdb/get_class_properties.php.md)
- [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md)
- [api/cmdb/delete_class_property.php](/code/files/api/cmdb/delete_class_property.php.md)
- [api/cmdb/get_relationship_types.php](/code/files/api/cmdb/get_relationship_types.php.md)
- [api/cmdb/save_relationship_type.php](/code/files/api/cmdb/save_relationship_type.php.md)
- [api/cmdb/delete_relationship_type.php](/code/files/api/cmdb/delete_relationship_type.php.md)
- [api/cmdb/get_ai_settings.php](/code/files/api/cmdb/get_ai_settings.php.md)
- [api/cmdb/save_ai_settings.php](/code/files/api/cmdb/save_ai_settings.php.md)
- [api/cmdb/ai_suggest_questions.php](/code/files/api/cmdb/ai_suggest_questions.php.md)
- [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md)

## Loaded by

- [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md)

