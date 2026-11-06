# slugifyProp()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md), [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md)

Turns a property name into a lower-case key of letters, digits and underscores, falling back to 'property' when nothing is left.

```php
function slugifyProp($name)
```

Defined in [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md), line 33.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` |  |  | the property name as typed or suggested by the AI |

## Called from

- [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md)
- [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md)

