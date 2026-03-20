#!/usr/bin/env python3
import base64
import json
import grp
import pwd
from http.server import BaseHTTPRequestHandler, HTTPServer

HOST = "127.0.0.1"
PORT = 3000

AUTH_USER = "test"
AUTH_PASS = "abcABC123456"

def json_indexed_list(items):
    return {str(i): item for i, item in enumerate(items)}

def parse_basic_auth(header_value: str):
    if not header_value or not header_value.startswith("Basic "):
        return (None, None)
    b64 = header_value.split(" ", 1)[1].strip()
    try:
        raw = base64.b64decode(b64).decode("utf-8")
        if ":" not in raw:
            return (None, None)
        return tuple(raw.split(":", 1))
    except Exception:
        return (None, None)

class Handler(BaseHTTPRequestHandler):
    def _send_json(self, status_code: int, payload: dict):
        data = json.dumps(payload, separators=(",", ":")).encode("utf-8")
        self.send_response(status_code)
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Content-Length", str(len(data)))
        self.end_headers()
        self.wfile.write(data)

    def _unauthorized(self):
        self.send_response(401)
        self.send_header('WWW-Authenticate', 'Basic realm="api"')
        body = b'{"error":"unauthorized"}'
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def do_GET(self): self.send_response(405)
    def do_PUT(self): self.send_response(405)
    def do_DELETE(self): self.send_response(405)

    def do_POST(self):
        user, pw = parse_basic_auth(self.headers.get("Authorization"))
        if user != AUTH_USER or pw != AUTH_PASS:
            return self._unauthorized()

        if not self.path.startswith("/api/"):
            return self._send_json(404, {"error": "not_found"})

        method = self.path[len("/api/"):].strip("/")

        if method == "users":
            names = [u.pw_name for u in pwd.getpwall()]
            return self._send_json(200, json_indexed_list(names))

        if method == "groups":
            names = [g.gr_name for g in grp.getgrall()]
            return self._send_json(200, json_indexed_list(names))

        return self._send_json(404, {"error": "unknown_method"})

    def log_message(self, format, *args):
        return

def main():
    httpd = HTTPServer((HOST, PORT), Handler)
    print(f"Listening on http://{HOST}:{PORT}")
    httpd.serve_forever()

if __name__ == "__main__":
    main()
