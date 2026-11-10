"""CLI/API-contract regressions. Standard library only; never touches a real installation."""
import contextlib
import csv
import importlib.util
import io
import json
import os
from pathlib import Path
import secrets
import tempfile
import threading
import unittest
from unittest.mock import patch
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer

spec = importlib.util.spec_from_file_location("sharepoint_import", Path(__file__).resolve().parents[1] / "scripts/import_sharepoint_tickets.py")
tool = importlib.util.module_from_spec(spec)
spec.loader.exec_module(tool)


class Handler(BaseHTTPRequestHandler):
    def log_message(self, *args):
        pass

    def reply(self, code, body):
        self.send_response(code)
        self.send_header("Content-Type", "application/json")
        self.end_headers()
        self.wfile.write(json.dumps(body).encode())

    def do_GET(self):
        self.server.requests.append(("GET", self.path))
        if self.headers.get("Authorization") != "Bearer " + self.server.key:
            self.reply(401, {"error": {"message": self.server.key}})
        elif self.server.error_response:
            self.reply(500, {"error": {"message": self.server.key}})
        elif self.server.redirect:
            self.send_response(302)
            self.send_header("Location", self.server.url + "/stolen")
            self.end_headers()
        elif self.path.startswith("/api/v1/tickets/"):
            ticket = self.server.tickets.get(int(self.path.rsplit("/", 1)[1]))
            self.reply(200 if ticket else 404, {"data": ticket})
        elif self.path.endswith("/analysts"):
            people = [{"id": 1, "email": "analyst@example.test", "name": "Analyst", "is_active": True}]
            if self.server.ambiguous_people:
                people.append({"id": 5, "email": "analyst@example.test", "is_active": True})
            self.reply(200, {"data": people})
        else:
            name = "Default" if self.path.endswith("/companies") else "Open"
            self.reply(200, {"data": [{"id": 1, "name": name, "is_active": True}, {"id": 5, "name": "Closed", "is_active": True}, {"id": 9, "is_active": False}]})

    def do_POST(self):
        self.server.requests.append(("POST", self.path))
        if self.headers.get("Authorization") != "Bearer " + self.server.key:
            self.reply(401, {"error": {"message": self.server.key}})
            return
        payload = json.loads(self.rfile.read(int(self.headers["Content-Length"])))
        self.server.posts.append(payload)
        ticket_id = len(self.server.tickets) + 100
        self.server.tickets[ticket_id] = {"id": ticket_id, "subject": payload["subject"]}
        if self.server.fail_after_commit:
            self.reply(500, {"error": {"message": self.server.key}})
        else:
            self.reply(201, {"data": self.server.tickets[ticket_id]})


class SharePointImportTests(unittest.TestCase):
    def setUp(self):
        self.temp = tempfile.TemporaryDirectory()
        self.addCleanup(self.temp.cleanup)
        self.root = Path(self.temp.name)
        self.csv = self.root / "tickets.csv"
        self.mapping = self.root / "mapping.json"
        self.config = {"source": "https://example.sharepoint.com/Lists/Tickets",
            "columns": {"source_id": "ID", "subject": "Title", "requester_email": "Email", "description": "Body", "status_id": "Status", "original_created_at": "Created"},
            "defaults": {"company_id": 1}, "values": {"status_id": {"Open": 1, "Closed": 5}}}
        self.rows = [{"ID": "101", "Title": "First ticket", "Email": "requester@example.test", "Body": "Line one\nLine two", "Status": "Open", "Created": "2020-01-01T10:00:00Z"}]
        self.write()

    def write(self, delimiter=","):
        self.mapping.write_text(json.dumps(self.config), encoding="utf-8")
        with self.csv.open("w", encoding="utf-8-sig", newline="") as stream:
            writer = csv.DictWriter(stream, fieldnames=list(self.rows[0]), delimiter=delimiter)
            writer.writeheader()
            writer.writerows(self.rows)

    def plan(self):
        return tool.make_plan(self.csv, tool.load_mapping(self.mapping))

    def server(self):
        server = ThreadingHTTPServer(("127.0.0.1", 0), Handler)
        server.url = "http://127.0.0.1:" + str(server.server_address[1])
        server.key = secrets.token_hex(24)
        server.requests, server.posts, server.tickets = [], [], {}
        server.redirect = server.fail_after_commit = False
        server.ambiguous_people = False
        server.error_response = False
        thread = threading.Thread(target=server.serve_forever, daemon=True)
        thread.start()
        self.addCleanup(server.server_close)
        self.addCleanup(server.shutdown)
        return server

    def invoke(self, command, server=None, extra=()):
        args = [command, "--mapping", str(self.mapping), "--csv", str(self.csv)]
        if server:
            args += ["--url", server.url]
        out, err = io.StringIO(), io.StringIO()
        with patch.dict(os.environ, {"FREEITSM_API_KEY": server.key if server else ""}), contextlib.redirect_stdout(out), contextlib.redirect_stderr(err):
            code = tool.main(args + list(extra))
        return code, out.getvalue(), err.getvalue()

    def test_offline_preview_bom_multiline_and_provenance(self):
        code, out, err = self.invoke("preview")
        self.assertEqual((code, err), (0, ""))
        payload = json.loads(out)["tickets"][0]["payload"]
        self.assertIn("Line one\nLine two", payload["description"])
        self.assertIn("2020-01-01T10:00:00Z", payload["description"])
        self.assertIn("Source item ID: 101", payload["description"])
        self.assertNotIn("created_at", payload)
        self.assertEqual({p.name for p in self.root.iterdir()}, {"mapping.json", "tickets.csv"})

    def test_semicolon_csv(self):
        self.write(";")
        self.assertEqual(tool.make_plan(self.csv, self.config, ";")[0]["payload"]["status_id"], 1)

    def test_html_to_plain_text(self):
        self.config["description_format"] = "html"
        self.rows[0]["Body"] = "<p>Hello &amp; goodbye</p><script>hidden()</script><p>Next</p>"
        self.write()
        body = self.plan()[0]["payload"]["description"]
        self.assertIn("Hello & goodbye", body)
        self.assertNotIn("hidden()", body)
        self.assertNotIn("<p>", body)

    def test_custom_fields_pass_through(self):
        self.config["custom_fields"] = {"original_reference": "ID"}
        self.write()
        self.assertEqual(self.plan()[0]["payload"]["custom_fields"], {"original_reference": "101"})

    def test_current_year_filter_keeps_created_year_and_skips_other_years(self):
        self.config.update(year=2026, year_column="Created")
        self.rows.append(dict(self.rows[0], ID="102", Created="2026-06-01T10:00:00Z"))
        self.write()
        self.assertEqual([item["source_id"] for item in self.plan()], ["102"])
        self.rows[1]["Created"] = "06/07/2026"
        self.write()
        with self.assertRaises(tool.ImportFailure):
            self.plan()

    def test_dotnet_dates_and_utc_year_boundary(self):
        self.assertEqual(tool.utc_timestamp("2026-02-01T10:00:00.1234567Z"), "2026-02-01 10:00:00")
        self.config.update(year=2026, year_column="Created")
        self.rows[0]["Created"] = "2026-01-01T01:00:00+02:00"
        self.rows.append(dict(self.rows[0], ID="102", Created="2026-01-01T01:00:00Z"))
        self.write()
        self.assertEqual([item["source_id"] for item in self.plan()], ["102"])

    def test_historical_signoff_resolution_and_dates_are_not_live_approval(self):
        self.rows[0].update(Signoff="Approved", Resolution="<p>Fixed &amp; tested</p>")
        self.config["metadata"] = {"Original sign-off": "Signoff", "Resolution": "Resolution"}
        self.config["html_metadata"] = ["Resolution"]
        self.write()
        payload = self.plan()[0]["payload"]
        self.assertIn("Original sign-off:\nApproved", payload["description"])
        self.assertIn("Fixed & tested", payload["description"])
        self.assertNotIn("approval", payload)

    def test_lookup_names_resolve_without_hardcoded_ids(self):
        self.config["defaults"]["company_id"] = "Default"
        self.config["values"]["status_id"]["Open"] = "Open"
        self.write()
        server = self.server()
        self.assertEqual(self.invoke("check", server)[0], 0)
        result = json.loads(self.invoke("check", server)[1])["mappings"][0]
        self.assertEqual(result["company_id"], 1)
        self.assertEqual(result["status_id"], 1)

    def test_assigned_person_matches_active_analyst_email_without_writing(self):
        self.config["columns"]["assigned_analyst_email"] = "Assignee Email"
        self.rows[0]["Assignee Email"] = "ANALYST@example.test"
        self.write()
        server = self.server()
        self.assertEqual(self.invoke("check", server)[0], 0)
        result = json.loads(self.invoke("check", server)[1])["mappings"][0]
        self.assertEqual(result["assigned_analyst_id"], 1)
        self.assertFalse(server.posts)

    def test_unknown_or_ambiguous_assigned_email_stops_before_writing(self):
        self.config["columns"]["assigned_analyst_email"] = "Assignee Email"
        for email, ambiguous in [("missing@example.test", False), ("analyst@example.test", True)]:
            self.rows[0]["Assignee Email"] = email
            self.write()
            server = self.server()
            server.ambiguous_people = ambiguous
            self.assertEqual(self.invoke("check", server)[0], 1)
            self.assertFalse(server.posts)

    def test_duplicate_ids_reject_entire_file_before_limit(self):
        self.rows.append(dict(self.rows[0]))
        self.write()
        server = self.server()
        code, _, _ = self.invoke("check", server, ["--limit", "1"])
        self.assertEqual(code, 1)
        self.assertEqual(server.requests, [])
        self.assertEqual({p.name for p in self.root.iterdir()}, {"mapping.json", "tickets.csv"})

    def test_bad_requester_and_unmapped_status(self):
        for field, value in [("Email", "Display Name"), ("Status", "Unknown")]:
            original = self.rows[0][field]
            self.rows[0][field] = value
            self.write()
            with self.assertRaises(tool.ImportFailure):
                self.plan()
            self.rows[0][field] = original

    def test_missing_and_duplicate_headers(self):
        for text in ["ID,Title\n1,Title\n", "ID,ID,Title\n1,2,Title\n"]:
            self.csv.write_text(text)
            with self.assertRaises(tool.ImportFailure):
                self.plan()

    def test_reference_preflight_is_read_only(self):
        server = self.server()
        self.assertEqual(self.invoke("check", server)[0], 0)
        self.assertTrue(server.requests)
        self.assertFalse(server.posts)
        self.assertEqual({p.name for p in self.root.iterdir()}, {"mapping.json", "tickets.csv"})

    def test_api_client_refuses_writes_and_never_prints_reflected_credentials(self):
        server = self.server()
        api = tool.API(server.url, server.key)
        with self.assertRaises(tool.ImportFailure):
            api.request("POST", "/tickets", {"subject": "Must not be written"})
        self.assertFalse(server.requests)
        server.error_response = True
        code, out, err = self.invoke("check", server)
        self.assertEqual(code, 1)
        self.assertIn("HTTP 500", err)
        self.assertNotIn(server.key, out + err)

    def test_unknown_or_inactive_ids_never_post(self):
        for unknown in [999, 9]:
            server = self.server()
            self.config["values"]["status_id"]["Open"] = unknown
            self.write()
            self.assertEqual(self.invoke("check", server)[0], 1)
            self.assertFalse(server.posts)

    def test_redirects_never_forward_credentials(self):
        server = self.server()
        server.redirect = True
        code, _, err = self.invoke("check", server)
        self.assertEqual(code, 1)
        self.assertIn("redirected", err)
        self.assertEqual(len(server.requests), 1)

    def test_url_security_and_namespace_normalisation(self):
        for url in ["http://example.test", "https://secret@example.test", "https://example.test?token=secret", "file:///tmp/data", "https://example.test/a/../"]:
            with self.assertRaises(tool.ImportFailure):
                tool.api_url(url)
        self.assertEqual(tool.api_url("HTTP://LOCALHOST:80/"), "http://localhost/api/v1")
        self.assertEqual(tool.api_url("https://EXAMPLE.test:443/app/api/v1/"), "https://example.test/app/api/v1")

    def test_missing_api_key_is_not_a_write(self):
        server = self.server()
        args = ["check", "--url", server.url, "--csv", str(self.csv), "--mapping", str(self.mapping)]
        with patch.dict(os.environ, {"FREEITSM_API_KEY": ""}), contextlib.redirect_stderr(io.StringIO()):
            self.assertEqual(tool.main(args), 1)
        self.assertEqual(server.requests, [])
        self.assertEqual({p.name for p in self.root.iterdir()}, {"mapping.json", "tickets.csv"})

    def history_bundle(self):
        self.rows[0].update(Modified="2020-01-02T10:00:00Z", Resolution="Private resolution", **{"Source comments": json.dumps({"id": "7", "text": "<p>Private comment</p>", "createdDate": "2020-01-01T11:00:00Z", "author": {"email": "original@example.test", "name": "Original Author"}, "replies": [{"id": "8", "text": "Reply", "createdDate": "2020-01-01T12:00:00Z"}]})})
        self.config["metadata"] = {"Resolution": "Resolution"}
        self.write()
        (self.root / "files.json").write_text("[]")
        (self.root / "comments.json").write_text("[]")
        self.write_summary()

    def write_summary(self, **overrides):
        summary = {"source": self.config["source"], "files": 0, "files_skipped": False, "comments_skipped": False,
            "csv_sha256": tool.file_sha256(self.csv), "files_manifest_sha256": tool.file_sha256(self.root / "files.json"),
            "comments_sha256": tool.file_sha256(self.root / "comments.json")}
        summary.update(overrides)
        (self.root / "export-summary.json").write_text(json.dumps(summary))

    def test_prepare_history_preserves_native_dates_comments_and_private_context(self):
        self.history_bundle()
        server = self.server()
        output = self.root / "history.json"
        code, _, err = self.invoke("prepare-history", server, ["--output", str(output)])
        self.assertEqual((code, err), (0, ""))
        item = json.loads(output.read_text())["tickets"][0]
        self.assertEqual(item["history"]["created_at"], "2020-01-01 10:00:00")
        self.assertEqual(len(item["history"]["comments"]), 2)
        self.assertNotIn("Private resolution", item["payload"]["description"])
        self.assertIn("Private resolution", item["history"]["internal_context"])
        self.assertNotIn("Private comment", item["payload"]["description"])
        self.assertFalse(server.posts)
        self.assertEqual(self.invoke("prepare-history", server, ["--output", str(output)])[0], 1)

    def test_history_refuses_incomplete_export_and_external_paths(self):
        self.history_bundle()
        summary = self.root / "export-summary.json"
        self.write_summary(files_skipped=True)
        with self.assertRaises(tool.ImportFailure):
            tool.prepare_history(self.plan(), self.config, self.csv)
        self.history_bundle()
        outside = Path(self.temp.name).parent / "not-part-of-this-bundle"
        (self.root / "files.json").write_text(json.dumps([{"source_id": "101", "path": str(outside), "name": "x.txt", "sha256": "0" * 64, "size": 1}]))
        self.write_summary(files=1)
        with self.assertRaises(tool.ImportFailure):
            tool.prepare_history(self.plan(), self.config, self.csv)

    def test_history_file_hashes_are_verified(self):
        self.history_bundle()
        (self.root / "file.txt").write_text("changed")
        (self.root / "files.json").write_text(json.dumps([{"source_id": "101", "path": "file.txt", "name": "x.txt", "sha256": "0" * 64, "size": 7}]))
        self.write_summary(files=1)
        with self.assertRaises(tool.ImportFailure):
            tool.prepare_history(self.plan(), self.config, self.csv)

    def test_history_dates_require_timezone_and_comments_require_complete_replies(self):
        with self.assertRaises(tool.ImportFailure):
            tool.utc_timestamp("2026-01-01T10:00:00")
        self.assertEqual(tool.utc_timestamp("2026-01-01T10:00:00-05:00"), "2026-01-01 15:00:00")
        with self.assertRaises(tool.ImportFailure):
            tool.history_notes(json.dumps({"text": "Hi", "createdDate": "2026-01-01T10:00:00Z", "replies": {"value": [], "@odata.nextLink": "more"}}))


if __name__ == "__main__":
    unittest.main()
