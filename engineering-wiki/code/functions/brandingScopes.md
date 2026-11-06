# brandingScopes()

THE THREE SCREENS, and what each one has to style.

They are not identical: a landing page has no sign-in form, so asking where
to put one — or whether its panel should be frosted glass — is a control that
cannot do anything. `only` names the fields a scope leaves out.

⚠️ The KEY PREFIX for the analyst login stays `branding_login_` rather than
becoming `branding_analyst_`. Renaming it would orphan the settings of anyone
who has already used the designer, for tidiness.

```php
function brandingScopes(): array
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 118.

**Returns:** `array`

## Called from

- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [includes/branding.php](/code/files/includes/branding.php.md) - in [brandingScopeValid()](/code/functions/brandingScopeValid.md), [brandingLoginFields()](/code/functions/brandingLoginFields.md), [brandingLoginKey()](/code/functions/brandingLoginKey.md), [brandingLoginDesign()](/code/functions/brandingLoginDesign.md)
- [system/branding/index.php](/code/files/system/branding/index.php.md)

