# workflowEffectiveSslVerify()

Resolve the effective SSL_VERIFYPEER flag for an outbound curl call.

The global `SSL_VERIFY_PEER` constant from `config.php` acts as a
**kill switch** — when set to false (the default for dev installs that
don't have a CA bundle wired into php.ini), all outbound HTTPS skips
verification regardless of any per-form toggle, otherwise the user would
have to remember to flip the toggle for every module's AI key test.

In production set SSL_VERIFY_PEER = true in config.php and use the
per-form toggle only when you genuinely need to opt out of verification
for a single integration (e.g. corporate inspecting proxy with a
self-signed cert).

```php
function workflowEffectiveSslVerify(bool $perCallVerify): bool
```

Defined in [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md), line 72.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$perCallVerify` | `bool` |  | the form's or provider's own verify-SSL tick; ignored (false) when SSL_VERIFY_PEER is false |

**Returns:** `bool`

## Called from

- [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md) - in [wfCallAnthropic()](/code/functions/wfCallAnthropic.md), [wfCallOpenAI()](/code/functions/wfCallOpenAI.md)
- [api/workflow/test_ai_key.php](/code/files/api/workflow/test_ai_key.php.md) - in [wfTestAnthropic()](/code/functions/wfTestAnthropic.md), [wfTestOpenAI()](/code/functions/wfTestOpenAI.md)

