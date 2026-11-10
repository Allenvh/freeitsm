#!/usr/bin/env python3
"""Prepare historical SharePoint ticket imports with read-only FreeITSM lookups."""
from __future__ import annotations

import argparse
import csv
from datetime import datetime, timezone
import hashlib
from html.parser import HTMLParser
import json
import os
from pathlib import Path
import re
import ssl
import sys
import urllib.error
import urllib.parse
import urllib.request


class ImportFailure(Exception):
    pass


def iso_datetime(value):
    # .NET's round-trip format has seven fractional digits; older Python releases
    # accept at most six. Native FreeITSM DATETIME stores seconds, raw text is kept.
    text = str(value).strip().replace("Z", "+00:00")
    text = re.sub(r"(\.\d{6})\d+(?=[+-]\d{2}:\d{2}$)", r"\1", text)
    return datetime.fromisoformat(text)


IDS = {
    "status_id": "statuses", "priority_id": "priorities",
    "department_id": "departments", "assigned_analyst_id": "analysts",
    "company_id": "companies", "ticket_type_id": "ticket-types", "origin_id": "origins",
}
TEXT = {"source_id", "subject", "description", "requester_email", "requester_name",
        "original_created_at", "history", "assigned_analyst_email"}


class PlainText(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.parts = []
        self.hidden = 0

    def handle_starttag(self, tag, attrs):
        if tag in {"script", "style"}:
            self.hidden += 1
        elif not self.hidden and tag in {"p", "div", "br", "li", "tr"}:
            self.parts.append("\n")

    def handle_endtag(self, tag):
        if tag in {"script", "style"}:
            self.hidden = max(0, self.hidden - 1)
        elif not self.hidden and tag in {"p", "div", "li", "tr"}:
            self.parts.append("\n")

    def handle_data(self, data):
        if not self.hidden:
            self.parts.append(data)


def positive_id(value):
    if isinstance(value, bool) or not re.fullmatch(r"[1-9][0-9]*", str(value)):
        raise ImportFailure("Destination IDs must be positive integers.")
    return int(value)


def destination_value(value):
    if isinstance(value, bool) or not isinstance(value, (str, int)):
        raise ImportFailure("Lookup destinations must be positive IDs or exact destination names.")
    if isinstance(value, int) or re.fullmatch(r"[+-]?[0-9]+", value):
        return positive_id(value)
    if not value.strip():
        raise ImportFailure("Destination lookup names must not be empty.")
    return value.strip()


def load_mapping(path):
    config = json.loads(Path(path).read_text(encoding="utf-8-sig"))
    if not isinstance(config, dict) or not isinstance(config.get("source"), str) or not config["source"].strip():
        raise ImportFailure("Mapping requires a stable source identifier, such as the SharePoint list URL.")
    columns = config.get("columns", {})
    if not isinstance(columns, dict) or set(columns) - TEXT - IDS.keys():
        raise ImportFailure("Unknown or invalid column mapping.")
    for required in ("source_id", "subject", "requester_email"):
        if not columns.get(required):
            raise ImportFailure("Map source_id, subject and requester_email columns.")
    if any(not isinstance(v, str) or not v for v in columns.values()):
        raise ImportFailure("Column names must be nonempty strings.")
    defaults = config.get("defaults", {})
    maps = config.get("values", {})
    custom = config.get("custom_fields", {})
    metadata = config.get("metadata", {})
    if not isinstance(defaults, dict) or set(defaults) - IDS.keys():
        raise ImportFailure("Defaults support destination ID fields only.")
    if not isinstance(maps, dict) or set(maps) - IDS.keys():
        raise ImportFailure("Value maps support destination ID fields only.")
    for value in defaults.values():
        destination_value(value)
    for mapping in maps.values():
        if not isinstance(mapping, dict):
            raise ImportFailure("Each value map must be a JSON object.")
        for value in mapping.values():
            destination_value(value)
    if not isinstance(custom, dict) or any(not isinstance(v, str) or not v for v in custom.values()):
        raise ImportFailure("custom_fields must map FreeITSM field keys to CSV headers.")
    if not isinstance(metadata, dict) or any(not isinstance(v, str) or not v for v in metadata.values()):
        raise ImportFailure("metadata must map historical labels to CSV headers.")
    if not isinstance(config.get("html_metadata", []), list) or set(config.get("html_metadata", [])) - metadata.keys():
        raise ImportFailure("html_metadata must name labels from metadata.")
    if "year" in config and (type(config["year"]) is not int or not 1900 <= config["year"] <= 2100 or not config.get("year_column")):
        raise ImportFailure("Year filtering needs an integer year and year_column header.")
    if config.get("description_format", "text") not in {"text", "html"}:
        raise ImportFailure("description_format must be text or html.")
    return config


def make_plan(csv_path, config, delimiter=","):
    if len(delimiter) != 1:
        raise ImportFailure("Delimiter must be one character.")
    columns = config["columns"]
    csv.field_size_limit(8 * 1024 * 1024)
    plan, errors, seen = [], [], set()
    with open(csv_path, newline="", encoding="utf-8-sig") as stream:
        reader = csv.DictReader(stream, delimiter=delimiter)
        headers = reader.fieldnames or []
        if len(headers) != len(set(headers)):
            raise ImportFailure("CSV contains duplicate column headers.")
        missing = set(columns.values()) | set(config.get("custom_fields", {}).values()) | set(config.get("metadata", {}).values())
        if "year" in config:
            missing.add(config["year_column"])
        if missing - set(headers):
            raise ImportFailure("CSV is missing mapped headers: " + ", ".join(sorted(missing - set(headers))))
        for record, row in enumerate(reader, 1):
            try:
                if None in row or any(v is None for v in row.values()):
                    raise ImportFailure("Column count differs from the CSV header.")
                if "year" in config:
                    try:
                        created = iso_datetime(row[config["year_column"]])
                        if created.tzinfo is not None:
                            created = created.astimezone(timezone.utc)
                    except ValueError:
                        raise ImportFailure("Year filtering requires ISO dates; use the export helper or yyyy-mm-dd dates.") from None
                    if created.year != config["year"]:
                        continue
                read = lambda field: row[columns[field]].strip() if field in columns else ""
                source_id = read("source_id")
                if not source_id or source_id in seen:
                    raise ImportFailure("Source ID is empty or duplicated in this CSV.")
                seen.add(source_id)
                subject, email = read("subject"), read("requester_email").lower()
                if not subject or len(subject) > 255:
                    raise ImportFailure("Subject is required and must be at most 255 characters.")
                if not re.fullmatch(r"[^\s@<>]+@[^\s@<>]+\.[^\s@<>]+", email):
                    raise ImportFailure("Requester must contain an email address, not a SharePoint display name.")
                description = read("description")
                if config.get("description_format") == "html":
                    parser = PlainText()
                    parser.feed(description)
                    description = "".join(parser.parts).strip()
                provenance = "Imported from SharePoint\nSource: " + config["source"] + "\nSource item ID: " + source_id
                if read("original_created_at"):
                    provenance += "\nOriginal creation date (source value): " + read("original_created_at")
                if read("history"):
                    provenance += "\n\nOriginal history (source text):\n" + read("history")
                for label, header in config.get("metadata", {}).items():
                    value = row[header].strip()
                    if value:
                        if label in config.get("html_metadata", []):
                            parser = PlainText()
                            parser.feed(value)
                            value = "".join(parser.parts).strip()
                        provenance += "\n\n" + label + ":\n" + value
                payload = {"subject": subject, "requester_email": email,
                           "description": (description + "\n\n" + provenance).strip()}
                if read("requester_name"):
                    payload["requester_name"] = read("requester_name")
                lookup_names = {}
                for field in IDS:
                    value = read(field)
                    if value:
                        mapping = config.get("values", {}).get(field)
                        if mapping is not None:
                            if value not in mapping:
                                raise ImportFailure("Unmapped value in " + field + ".")
                            value = mapping[value]
                        value = destination_value(value)
                    elif field in config.get("defaults", {}):
                        value = destination_value(config["defaults"][field])
                    else:
                        continue
                    if isinstance(value, int):
                        payload[field] = value
                    else:
                        lookup_names[field] = value
                if config.get("custom_fields"):
                    payload["custom_fields"] = {key: row[header] for key, header in config["custom_fields"].items()}
                assignment = read("assigned_analyst_email").lower()
                if assignment and (not re.fullmatch(r"[^\s@<>]+@[^\s@<>]+\.[^\s@<>]+", assignment) or "assigned_analyst_id" in payload or "assigned_analyst_id" in lookup_names):
                    raise ImportFailure("Assign by a valid analyst email OR an analyst ID, not both.")
                canonical = {"payload": payload, "assignment_email": assignment, "lookup_names": lookup_names}
                fingerprint = hashlib.sha256(json.dumps(canonical, sort_keys=True, ensure_ascii=False).encode()).hexdigest()
                plan.append({"record": record, "source_id": source_id, "fingerprint": fingerprint, "assignment_email": assignment, "lookup_names": lookup_names, "payload": payload, "source_fields": dict(row), "description_plain": description})
            except ImportFailure as exc:
                errors.append("Record " + str(record) + ": " + str(exc))
    if errors:
        raise ImportFailure("CSV validation failed; nothing imported.\n" + "\n".join(errors[:20]))
    if not plan:
        raise ImportFailure("CSV contains no ticket records.")
    return plan


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        raise ImportFailure("API redirected; use its final URL. Credentials were not forwarded.")


def api_url(url):
    parts = urllib.parse.urlsplit(url)
    if (parts.scheme not in {"http", "https"} or not parts.hostname or parts.username
            or parts.password or parts.query or parts.fragment):
        raise ImportFailure("Use an HTTP(S) API URL without credentials, query or fragment.")
    if parts.scheme == "http" and parts.hostname not in {"localhost", "127.0.0.1", "::1"}:
        raise ImportFailure("HTTP is allowed only for localhost. Use HTTPS for remote API credentials.")
    path = parts.path.rstrip("/")
    if any(segment in {".", ".."} for segment in path.split("/")) or "//" in path:
        raise ImportFailure("Use the canonical application URL without dot or empty path segments.")
    host = parts.hostname.lower().encode("idna").decode("ascii")
    if ":" in host:
        host = "[" + host + "]"
    if parts.port and parts.port != (443 if parts.scheme == "https" else 80):
        host += ":" + str(parts.port)
    if not path.endswith("/api/v1"):
        path += "/api/v1"
    return urllib.parse.urlunsplit((parts.scheme, host, path, "", ""))


class API:
    def __init__(self, url, key):
        self.url = api_url(url)
        if not key:
            raise ImportFailure("Set FREEITSM_API_KEY in your environment; do not put it in the mapping file.")
        self.key = key
        self.opener = urllib.request.build_opener(NoRedirect(), urllib.request.HTTPSHandler(context=ssl.create_default_context()))

    def request(self, method, path, payload=None):
        if method != "GET":
            raise ImportFailure("The preparation API client is read-only. Use the historical maintenance command for imports.")
        body = json.dumps(payload).encode() if payload is not None else None
        request = urllib.request.Request(self.url + path, data=body, method=method,
            headers={"Authorization": "Bearer " + self.key, "Content-Type": "application/json", "Accept": "application/json"})
        try:
            with self.opener.open(request, timeout=45) as response:
                data = json.load(response)
        except urllib.error.HTTPError as exc:
            raise ImportFailure("API returned HTTP " + str(exc.code) + "; response content withheld.") from None
        except (urllib.error.URLError, TimeoutError, OSError, ValueError):
            raise ImportFailure("API connection or response failed; credentials and response content withheld.") from None
        if not isinstance(data, dict) or "data" not in data:
            raise ImportFailure("Unexpected API response.")
        return data["data"]

    def preflight(self, plan):
        prepared = [dict(item, payload=dict(item["payload"])) for item in plan]
        references = {}

        def fetch(endpoint):
            if endpoint not in references:
                rows = self.request("GET", "/" + endpoint)
                if not isinstance(rows, list):
                    raise ImportFailure("Unexpected reference lookup response.")
                references[endpoint] = rows
            return references[endpoint]

        assignments = {item.get("assignment_email") for item in plan if item.get("assignment_email")}
        if assignments:
            people = fetch("analysts")
            for email in assignments:
                matches = [person for person in people if person.get("is_active", True) and str(person.get("email", "")).lower() == email]
                if len(matches) != 1:
                    raise ImportFailure("Assigned analyst email must match exactly one active FreeITSM analyst. Review the check/preview before importing.")
                for item in prepared:
                    if item.get("assignment_email") == email:
                        item["payload"]["assigned_analyst_id"] = positive_id(matches[0]["id"])
        for field, endpoint in IDS.items():
            names = {item.get("lookup_names", {}).get(field) for item in prepared if field in item.get("lookup_names", {})}
            if names:
                rows = fetch(endpoint)
                for name in names:
                    matches = [row for row in rows if row.get("is_active", True) and str(row.get("name", "")).casefold() == name.casefold()]
                    if len(matches) != 1:
                        raise ImportFailure("Destination name must match exactly one active " + endpoint + " entry: " + name + ". Create or map it before importing.")
                    for item in prepared:
                        if item.get("lookup_names", {}).get(field) == name:
                            item["payload"][field] = positive_id(matches[0]["id"])
            needed = {item["payload"][field] for item in prepared if field in item["payload"]}
            if not needed:
                continue
            rows = fetch(endpoint)
            known = {int(row["id"]) for row in rows if row.get("is_active", True)}
            if needed - known:
                raise ImportFailure("Unknown, inactive or inaccessible IDs for " + field + ": " + str(sorted(needed - known)))
        return prepared


def utc_timestamp(value):
    try:
        date = iso_datetime(value)
        if date.tzinfo is None:
            raise ValueError()
        return date.astimezone(timezone.utc).strftime("%Y-%m-%d %H:%M:%S")
    except ValueError:
        raise ImportFailure("Historical import requires ISO timestamps with timezone offsets.") from None


def history_notes(raw):
    result = []

    def visit(comment):
        if not isinstance(comment, dict):
            raise ImportFailure("Unexpected source comment structure.")
        text = str(comment.get("text", "")).strip()
        if text:
            parser = PlainText()
            parser.feed(text)
            author = comment.get("author") or {}
            result.append({"text": "".join(parser.parts).strip(), "created_at": utc_timestamp(comment.get("createdDate") or comment.get("createdDateTime")),
                "author_email": str(author.get("email", "")), "author_name": str(author.get("name", "")), "source_comment_id": str(comment.get("id", ""))})
        replies = comment.get("replies_all", comment.get("replies", [])) or []
        if isinstance(replies, dict):
            if any(replies.get(key) for key in ("__next", "@odata.nextLink", "odata.nextLink")):
                raise ImportFailure("Comment replies are incomplete; export every reply page first.")
            replies = replies.get("results", replies.get("value", []))
        for reply in replies:
            visit(reply)

    for line in raw.splitlines():
        if line.strip():
            visit(json.loads(line))
    return result


def file_sha256(path):
    digest = hashlib.sha256()
    with Path(path).open("rb") as stream:
        for chunk in iter(lambda: stream.read(1024 * 1024), b""):
            digest.update(chunk)
    return digest.hexdigest()


def prepare_history(plan, config, csv_path):
    root = Path(csv_path).resolve().parent
    summary = json.loads((root / "export-summary.json").read_text(encoding="utf-8-sig"))
    if summary.get("source") != config["source"] or summary.get("files_skipped") or summary.get("comments_skipped"):
        raise ImportFailure("Historical import needs a complete matching export, including files and comments.")
    for path, key in [(Path(csv_path), "csv_sha256"), (root / "files.json", "files_manifest_sha256"), (root / "comments.json", "comments_sha256")]:
        if not summary.get(key) or file_sha256(path) != summary[key]:
            raise ImportFailure("The export bundle changed or is incomplete. Re-export it rather than mixing CSVs and archives.")
    manifest = json.loads((root / "files.json").read_text(encoding="utf-8-sig"))
    if not isinstance(manifest, list) or len(manifest) != summary.get("files"):
        raise ImportFailure("File manifest does not match the completed export summary.")
    columns = config.get("history_columns", {"created_at": "Created", "modified_at": "Modified", "comments": "Source comments"})
    for item in plan:
        source = item["source_fields"]
        created = utc_timestamp(source[columns["created_at"]])
        modified = utc_timestamp(source[columns["modified_at"]])
        if modified < created:
            raise ImportFailure("Original modification precedes creation.")
        if "company_id" not in item["payload"]:
            raise ImportFailure("Historical imports require an explicit company mapping/default.")
        files = []
        for entry in manifest:
            if str(entry["source_id"]) != item["source_id"]:
                continue
            path = (root / entry["path"]).resolve()
            if not path.is_relative_to(root) or not path.is_file() or path.stat().st_size != int(entry["size"]):
                raise ImportFailure("An exported file is missing, outside the bundle or has changed size.")
            if file_sha256(path) != entry["sha256"]:
                raise ImportFailure("An exported file failed its SHA-256 check.")
            files.append({"path": entry["path"], "name": entry["name"], "size": int(entry["size"]), "sha256": entry["sha256"], "source_field": entry.get("source_field", "Attachments")})
        # Staff resolution/sign-off/comments may never have been visible to the
        # requester in SharePoint. Keep them in INTERNAL notes, not portal content.
        context = "Historical SharePoint import\nSource: " + config["source"] + "\nSource item ID: " + item["source_id"]
        excluded = {config["columns"].get("description"), columns.get("comments")}
        context += "\n\nOriginal fields:\n" + json.dumps({key: value for key, value in source.items() if key not in excluded}, ensure_ascii=False, indent=2)
        item["payload"]["description"] = (item.get("description_plain", "") + "\n\nImported SharePoint item " + item["source_id"]).strip()
        item["history"] = {"created_at": created, "modified_at": modified, "internal_context": context,
            "comments": history_notes(source.get(columns.get("comments", ""), "")), "files": files}
        canonical = {"payload": item["payload"], "history": item["history"], "source_fields": source}
        item["fingerprint"] = hashlib.sha256(json.dumps(canonical, sort_keys=True, ensure_ascii=False, separators=(",", ":")).encode()).hexdigest()
    return plan


def main(argv=None):
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("command", choices=["preview", "check", "prepare-history", "lookups"])
    parser.add_argument("--csv")
    parser.add_argument("--mapping")
    parser.add_argument("--url", help="Application URL or /api/v1 URL; HTTP only for localhost")
    parser.add_argument("--delimiter", default=",")
    parser.add_argument("--limit", type=int)
    parser.add_argument("--output", help="Private prepared historical manifest in the export bundle")
    args = parser.parse_args(argv)
    try:
        if args.command != "preview" and not args.url:
            raise ImportFailure("--url is required for this command.")
        if args.command == "lookups":
            api = API(args.url, os.environ.get("FREEITSM_API_KEY", ""))
            print(json.dumps({resource: api.request("GET", "/" + resource) for resource in dict.fromkeys(IDS.values())}, indent=2))
            return 0
        if not args.mapping or not args.csv:
            raise ImportFailure("--mapping and --csv are required.")
        config = load_mapping(args.mapping)
        plan = make_plan(args.csv, config, args.delimiter)
        if args.limit is not None:
            if args.limit < 1:
                raise ImportFailure("--limit must be positive.")
            plan = plan[:args.limit]
        if args.command == "preview":
            print(json.dumps({"mode": "preview", "tickets": plan}, ensure_ascii=False, indent=2))
            return 0
        api = API(args.url, os.environ.get("FREEITSM_API_KEY", ""))
        if args.command == "prepare-history":
            if not args.output:
                raise ImportFailure("--output is required for the prepared historical manifest.")
            output = Path(args.output).resolve()
            if output.parent != Path(args.csv).resolve().parent or output.is_relative_to(Path(__file__).resolve().parents[1]):
                raise ImportFailure("Write the historical manifest beside the exported CSV, outside the application.")
            ready = prepare_history(api.preflight(plan), config, args.csv)
            document = {"format": "freeitsm-sharepoint-history-v1", "source": config["source"], "destination": api.url, "tickets": ready}
            with output.open("x", encoding="utf-8") as stream:
                os.chmod(output, 0o600)
                json.dump(document, stream, ensure_ascii=False, indent=2)
            print(json.dumps({"mode": "prepare-history", "tickets": len(ready), "writes_to_freeitsm": 0}))
            return 0
        ready = api.preflight(plan)
        print(json.dumps({"mode": "check", "validated": len(plan), "writes": 0,
            "mappings": [{"source_id": item["source_id"], **{field: item["payload"][field] for field in IDS if field in item["payload"]}} for item in ready]}))
        return 0
    except (ImportFailure, OSError, ValueError, csv.Error, KeyError, TypeError, AttributeError) as exc:
        print(str(exc) if isinstance(exc, ImportFailure) else "Preparation failed: check the export and mapping. No credential values are displayed.", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
