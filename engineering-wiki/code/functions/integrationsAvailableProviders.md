# integrationsAvailableProviders()

The provider registry — the ONE list adding a tracker touches at this layer.

`credential_fields` drives the settings form, so a provider whose auth looks
nothing like Jira's needs no change to the page: it declares its own fields and
the form renders them. That is the difference between "adding GitHub is one
file" and "adding GitHub means editing the settings screen too".

`url_hint` / `url_label` exist because "Site URL" means something different per
tracker and a wrong-shaped URL is the most likely setup mistake.

`settings_fields` (optional) is for a NON-SECRET per-connection choice, and it
is deliberately a separate list from `credential_fields` rather than a `type`
on one. The two behave in opposite ways on an edit:

  credential_fields — blanked when the form opens, and an empty box means
                      "keep the stored secret". Correct for a token; nobody
                      should be shown one they already saved.
  settings_fields   — shown WITH the current value and always submitted.

Putting a dropdown in credential_fields would inherit the blanking rule, so
every save would quietly reset the choice back to its default — a silent
behaviour change nobody would connect to having pressed Save.

```php
function integrationsAvailableProviders(): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 76.

**Returns:** `array`

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsTrackerProviders()](/code/functions/integrationsTrackerProviders.md), [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md)
- [system/integrations/index.php](/code/files/system/integrations/index.php.md)

