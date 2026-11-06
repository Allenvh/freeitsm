<?php
/**
 * Minimal JSON Schema draft-04 validator — dev tool for checking a generated
 * OpenAPI 3.0 document against the official OAS meta-schema without needing
 * Node/Python. Handles the constructs the OAS 3.0 schema actually uses.
 *
 * Usage (CLI only):
 *   curl -s http://localhost/freeitsm-app/api/v1/openapi.json > /tmp/o.json
 *   php api/v1/dev/jsonschema4.php /tmp/o.json api/v1/dev/oas-3.0-schema.json
 *
 * Proven to reject malformed documents (missing openapi/responses, bad version
 * pattern), so a clean pass is meaningful. For the authoritative linter run,
 * additionally use `spectral lint` / `swagger-cli validate` in a Node env.
 */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit("CLI only.\n"); }

class JsonSchema4 {
    private $root;
    public $errors = [];
    /**
     * Keeps the root schema, which $ref pointers are resolved against.
     *
     * @param array $root  the whole decoded schema document, e.g. the OpenAPI 3.0 meta-schema
     */
    public function __construct(array $root) { $this->root = $root; }

    /**
     * Validates data against a schema (the root schema by default), adding any failures
     * to $errors.
     *
     * @param mixed $data  the decoded JSON value to check
     * @param ?array $schema  the schema to check against; null uses the root schema
     * @param string $path  where $data sits in the document, as a JSON pointer, for error messages
     * @return bool  true when this call added no errors
     */
    public function validate($data, ?array $schema = null, string $path = '#'): bool {
        $schema = $schema ?? $this->root;
        $before = count($this->errors);
        $this->check($data, $schema, $path);
        return count($this->errors) === $before;
    }

    /**
     * Records one validation failure as "path: message".
     *
     * @param string $path  where the failure is, as a JSON pointer
     * @param string $msg  what is wrong
     */
    private function err(string $path, string $msg) { $this->errors[] = "$path: $msg"; }

    /**
     * Follows a local JSON pointer ($ref starting #/) through the root schema, decoding
     * ~1 and ~0, and returns the schema it points at - null when it is external or
     * missing.
     *
     * @param string $ref  the $ref value, e.g. '#/definitions/Schema'
     */
    private function resolveRef(string $ref): ?array {
        if ($ref[0] !== '#') return null;
        $parts = array_slice(explode('/', $ref), 1);
        $node = $this->root;
        foreach ($parts as $p) {
            $p = str_replace(['~1', '~0'], ['/', '~'], $p);
            if (!is_array($node) || !array_key_exists($p, $node)) return null;
            $node = $node[$p];
        }
        return is_array($node) ? $node : null;
    }

    /**
     * Checks one value against one schema and records every failure: $ref, allOf / anyOf
     * / oneOf / not, type, enum, then the object, array, string and number keywords the
     * OpenAPI 3.0 meta-schema uses.
     *
     * A type mismatch stops the remaining keyword checks for that value, since they
     * would only repeat it.
     *
     * @param mixed $data  the decoded JSON value to check
     * @param array $schema  the schema to check it against
     * @param string $path  where $data sits in the document, as a JSON pointer, for error messages
     */
    private function check($data, array $schema, string $path): void {
        if (isset($schema['$ref'])) {
            $target = $this->resolveRef($schema['$ref']);
            if ($target === null) { $this->err($path, "unresolved \$ref {$schema['$ref']}"); return; }
            $this->check($data, $target, $path);
            return;
        }
        // Combinators
        if (isset($schema['allOf'])) foreach ($schema['allOf'] as $i => $sub) $this->check($data, $sub, "$path/allOf/$i");
        if (isset($schema['anyOf'])) {
            $ok = false;
            foreach ($schema['anyOf'] as $sub) { $v = new self($this->root); if ($v->validate($data, $sub)) { $ok = true; break; } }
            if (!$ok) $this->err($path, "does not match any of anyOf");
        }
        if (isset($schema['oneOf'])) {
            $matches = 0;
            foreach ($schema['oneOf'] as $sub) { $v = new self($this->root); if ($v->validate($data, $sub)) $matches++; }
            if ($matches !== 1) $this->err($path, "matched $matches of oneOf (need exactly 1)");
        }
        if (isset($schema['not'])) {
            $v = new self($this->root);
            if ($v->validate($data, $schema['not'])) $this->err($path, "must not match 'not' schema");
        }

        // Type
        if (isset($schema['type'])) {
            $types = (array)$schema['type'];
            if (!$this->matchesAnyType($data, $types)) {
                $this->err($path, "type mismatch: expected " . implode('|', $types) . ", got " . $this->typeOf($data));
                return; // further keyword checks are moot
            }
        }
        // Enum
        if (isset($schema['enum']) && !$this->inEnum($data, $schema['enum'])) {
            $this->err($path, "value not in enum");
        }

        // Objects
        if ($this->isObject($data)) {
            if (isset($schema['required'])) {
                foreach ($schema['required'] as $req) {
                    if (!array_key_exists($req, $data)) $this->err($path, "missing required property '$req'");
                }
            }
            $props = $schema['properties'] ?? [];
            $patternProps = $schema['patternProperties'] ?? [];
            $addl = $schema['additionalProperties'] ?? true;
            foreach ($data as $k => $v) {
                $matched = false;
                if (isset($props[$k])) { $this->check($v, $props[$k], "$path/$k"); $matched = true; }
                foreach ($patternProps as $pat => $psub) {
                    if (preg_match('~' . str_replace('~', '\~', $pat) . '~', (string)$k)) { $this->check($v, $psub, "$path/$k"); $matched = true; }
                }
                if (!$matched) {
                    if ($addl === false) $this->err($path, "additional property '$k' not allowed");
                    elseif (is_array($addl)) $this->check($v, $addl, "$path/$k");
                }
            }
            if (isset($schema['minProperties']) && count($data) < $schema['minProperties']) $this->err($path, "too few properties");
        }

        // Arrays (list)
        if (is_array($data) && $this->isList($data)) {
            if (isset($schema['items']) && $this->isAssoc($schema['items'])) {
                foreach ($data as $i => $v) $this->check($v, $schema['items'], "$path/$i");
            }
            if (isset($schema['minItems']) && count($data) < $schema['minItems']) $this->err($path, "too few items");
            if (!empty($schema['uniqueItems'])) {
                $seen = [];
                foreach ($data as $v) { $j = json_encode($v); if (in_array($j, $seen, true)) { $this->err($path, "items not unique"); break; } $seen[] = $j; }
            }
        }

        // Strings
        if (is_string($data)) {
            if (isset($schema['minLength']) && mb_strlen($data) < $schema['minLength']) $this->err($path, "string too short");
            if (isset($schema['maxLength']) && mb_strlen($data) > $schema['maxLength']) $this->err($path, "string too long");
            if (isset($schema['pattern']) && !preg_match('~' . str_replace('~', '\~', $schema['pattern']) . '~', $data)) $this->err($path, "pattern mismatch");
        }
        // Numbers
        if (is_int($data) || is_float($data)) {
            if (isset($schema['minimum'])) {
                $excl = !empty($schema['exclusiveMinimum']);
                if ($excl ? $data <= $schema['minimum'] : $data < $schema['minimum']) $this->err($path, "below minimum");
            }
            if (isset($schema['maximum'])) {
                $excl = !empty($schema['exclusiveMaximum']);
                if ($excl ? $data >= $schema['maximum'] : $data > $schema['maximum']) $this->err($path, "above maximum");
            }
            if (isset($schema['multipleOf']) && $schema['multipleOf'] > 0) {
                $r = $data / $schema['multipleOf'];
                if (abs($r - round($r)) > 1e-9) $this->err($path, "not a multiple of {$schema['multipleOf']}");
            }
        }
    }

    // --- type helpers (PHP array ambiguity: [] can be object or list) -------
    /**
     * Whether a PHP array is a JSON list - an empty array counts as both list and object.
     *
     * @param mixed $d  any decoded JSON value
     */
    private function isList($d): bool { return is_array($d) && array_is_list($d); }
    /**
     * Whether a PHP array is a non-empty JSON object (not a list).
     *
     * @param mixed $d  any decoded JSON value
     */
    private function isAssoc($d): bool { return is_array($d) && !$this->isList($d); }
    /**
     * Whether a value is a JSON object - an associative array, or an empty array.
     *
     * @param mixed $d  any decoded JSON value
     */
    private function isObject($d): bool { return is_array($d) && ($d === [] || $this->isAssoc($d)); }

    /**
     * The JSON Schema type name of a PHP value, for error messages.
     *
     * @param mixed $d  any decoded JSON value
     */
    private function typeOf($d): string {
        if (is_bool($d)) return 'boolean';
        if (is_int($d)) return 'integer';
        if (is_float($d)) return 'number';
        if (is_string($d)) return 'string';
        if ($d === null) return 'null';
        if ($this->isList($d)) return 'array';
        return 'object';
    }
    /**
     * Whether a value matches at least one of the given JSON Schema types.
     *
     * @param mixed $d  any decoded JSON value
     * @param array $types  JSON Schema type names, e.g. ['string', 'null']
     */
    private function matchesAnyType($d, array $types): bool {
        foreach ($types as $t) if ($this->matchesType($d, $t)) return true;
        return false;
    }
    /**
     * Whether a value matches one JSON Schema type - integer is a PHP int only, number
     * an int or float.
     *
     * @param mixed $d  any decoded JSON value
     * @param string $t  one JSON Schema type name, e.g. 'integer'; an unknown name never matches
     */
    private function matchesType($d, string $t): bool {
        switch ($t) {
            case 'object':  return $this->isObject($d);
            case 'array':   return is_array($d) && $this->isList($d);
            case 'string':  return is_string($d);
            case 'integer': return is_int($d);
            case 'number':  return is_int($d) || is_float($d);
            case 'boolean': return is_bool($d);
            case 'null':    return $d === null;
        }
        return false;
    }
    /**
     * Whether a value is one of an enum's values, comparing strictly and then by JSON
     * encoding (so arrays and objects match).
     *
     * @param mixed $d  any decoded JSON value
     * @param array $enum  the schema's enum values
     */
    private function inEnum($d, array $enum): bool {
        foreach ($enum as $e) if ($e === $d || json_encode($e) === json_encode($d)) return true;
        return false;
    }
}

// --- CLI: validate <docFile> against <schemaFile> ---------------------------
$docFile = $argv[1]; $schemaFile = $argv[2];
$doc = json_decode(file_get_contents($docFile), true);
$schema = json_decode(file_get_contents($schemaFile), true);
if ($doc === null) { fwrite(STDERR, "doc not valid JSON\n"); exit(2); }
$v = new JsonSchema4($schema);
$ok = $v->validate($doc);
if ($ok) { echo "VALID against OAS meta-schema (0 errors)\n"; exit(0); }
echo "INVALID — " . count($v->errors) . " error(s):\n";
foreach (array_slice($v->errors, 0, 40) as $e) echo "  - $e\n";
exit(1);
