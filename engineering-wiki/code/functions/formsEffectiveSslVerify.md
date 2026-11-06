# formsEffectiveSslVerify()

Apply the global SSL_VERIFY_PEER kill switch on top of the per-form toggle. Same behaviour as the equivalent helper on Workflow.

```php
function formsEffectiveSslVerify(bool $perCallVerify): bool
```

Defined in [api/forms/_ai_helpers.php](/code/files/api/forms/_ai_helpers.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$perCallVerify` | `bool` |  | the form's own "verify SSL" setting; the global switch can only turn it off |

**Returns:** `bool`

## Called from

- [api/forms/test_ai_key.php](/code/files/api/forms/test_ai_key.php.md) - in [formsTestAnthropic()](/code/functions/formsTestAnthropic.md), [formsTestOpenAI()](/code/functions/formsTestOpenAI.md)

