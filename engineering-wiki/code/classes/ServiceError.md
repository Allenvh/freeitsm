# ServiceError

> Class in [includes/service_context.php](/code/files/includes/service_context.php.md), line 138

A business-rule failure raised by a service. `$kind` drives the HTTP status the API adapter emits; `$code` + the message are passed through verbatim so the API's existing error bodies stay byte-identical.

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | A service failure with a kind (validation, not_found, conflict, forbidden...) that adapters turn into an HTTP status, a machine code and a message. |

### __construct

*public* - line 153

A service failure with a kind (validation, not_found, conflict, forbidden...) that adapters turn into an HTTP status, a machine code and a message.

```php
function __construct(string $kind, string $code, string $message)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$kind` | `string` |  | validation, not_found, forbidden, conflict or bad_request - decides the HTTP status |
| `$code` | `string` |  | machine slug for the API body, e.g. 'missing_field' |
| `$message` | `string` |  | the human-readable error |

