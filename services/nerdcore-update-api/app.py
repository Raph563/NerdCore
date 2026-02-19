#!/usr/bin/env python3
import json
import os
import subprocess
import threading
import urllib.error
import urllib.request
from datetime import datetime, timezone
from pathlib import Path

from flask import Flask, jsonify, request

app = Flask(__name__)
JOB_LOCK = threading.Lock()

TOKEN = os.environ.get("NERDCORE_UPDATE_TOKEN", "").strip()
GROCY_CONFIG_DIR = Path(os.environ.get("GROCY_CONFIG_DIR", "/opt/grocy/config")).resolve()
GROCY_DATA_DIR = GROCY_CONFIG_DIR / "data"
ADDONS_ROOT = Path(os.environ.get("NERDCORE_ADDONS_ROOT", "/opt/grocy/addons")).resolve()
COMPOSE_ORDER = [x.strip() for x in os.environ.get(
    "NERDCORE_COMPOSE_ORDER",
    "custom_js_nerdcore.html,custom_js_nerdstats.html,custom_js_product_helper.html",
).split(",") if x.strip()]
ACTIVE_FILE = GROCY_DATA_DIR / "custom_js.html"

ADDONS = {
    "nerdcore": {
        "repository": "Raph563/NerdCore",
        "script": ADDONS_ROOT / "NerdCore" / "addon" / "scripts" / "update-from-github.sh",
        "state_file": GROCY_DATA_DIR / "nerdcore-addon-state.json",
    },
    "statnerd": {
        "repository": "Raph563/StatNerd",
        "script": ADDONS_ROOT / "StatNerd" / "addon" / "scripts" / "update-from-github.sh",
        "state_file": GROCY_DATA_DIR / "statnerd-addon-state.json",
    },
    "producthelper": {
        "repository": "Raph563/ProductHelper",
        "script": ADDONS_ROOT / "ProductHelper" / "addon" / "scripts" / "update-from-github.sh",
        "state_file": GROCY_DATA_DIR / "producthelper-addon-state.json",
    },
}


def now_iso():
    return datetime.now(timezone.utc).replace(microsecond=0).isoformat().replace("+00:00", "Z")


def read_json(path: Path):
    try:
        return json.loads(path.read_text(encoding="utf-8"))
    except Exception:
        return {}


def current_tag_for_target(target: str):
    state = read_json(ADDONS[target]["state_file"])
    return str(state.get("release_tag") or "").strip()


def latest_tag(repository: str, include_prerelease: bool):
    base = f"https://api.github.com/repos/{repository}"
    if include_prerelease:
        url = f"{base}/releases?per_page=20"
    else:
        url = f"{base}/releases/latest"
    req = urllib.request.Request(url, headers={
        "Accept": "application/vnd.github+json",
        "X-GitHub-Api-Version": "2022-11-28",
        "User-Agent": "nerdcore-update-api",
    })
    try:
        with urllib.request.urlopen(req, timeout=20) as response:
            payload = json.loads(response.read().decode("utf-8"))
    except urllib.error.HTTPError:
        return ""
    except Exception:
        return ""

    if include_prerelease:
        rows = payload if isinstance(payload, list) else []
        for row in rows:
            if not isinstance(row, dict) or row.get("draft"):
                continue
            tag = str(row.get("tag_name") or "").strip()
            if tag:
                return tag
        return ""
    return str(payload.get("tag_name") or "").strip() if isinstance(payload, dict) else ""


def compose_custom_js():
    GROCY_DATA_DIR.mkdir(parents=True, exist_ok=True)
    parts = ["<!-- managed by nerdcore-update-api -->\n"]
    for name in COMPOSE_ORDER:
        source = GROCY_DATA_DIR / name
        if not source.exists() or source.stat().st_size == 0:
            continue
        parts.append(f"\n<!-- source: {name} -->\n")
        parts.append(source.read_text(encoding="utf-8", errors="ignore"))
        parts.append("\n")
    ACTIVE_FILE.write_text("".join(parts), encoding="utf-8")
    return str(ACTIVE_FILE)


def require_token():
    if not TOKEN:
        return jsonify({"ok": False, "error": "Server token is not configured."}), 500
    given = request.headers.get("X-NerdCore-Token", "").strip()
    if not given or given != TOKEN:
        return jsonify({"ok": False, "error": "Unauthorized"}), 401
    return None


def normalize_target(raw):
    value = str(raw or "all").strip().lower()
    if value in ADDONS:
        return value
    return "all"


def iter_targets(target):
    if target == "all":
        return ["nerdcore", "statnerd", "producthelper"]
    return [target]


def check_results(target, include_prerelease):
    results = []
    for item in iter_targets(target):
        repository = ADDONS[item]["repository"]
        current = current_tag_for_target(item)
        latest = latest_tag(repository, include_prerelease)
        results.append({
            "target": item,
            "repository": repository,
            "currentTag": current,
            "latestTag": latest,
            "installedTag": current,
            "stateFile": str(ADDONS[item]["state_file"]),
        })
    return results


def run_install(target, include_prerelease, no_backup, release_tags):
    results = []
    errors = []
    for item in iter_targets(target):
        meta = ADDONS[item]
        script = meta["script"]
        if not script.exists():
            errors.append(f"Script not found: {script}")
            continue
        cmd = ["/bin/sh", str(script), "--repository", meta["repository"], "--config", str(GROCY_CONFIG_DIR)]
        if include_prerelease:
            cmd.append("--allow-prerelease")
        if no_backup:
            cmd.append("--no-backup")
        forced_tag = str((release_tags or {}).get(item) or "").strip()
        if forced_tag:
            cmd.extend(["--tag", forced_tag])
        proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
        if proc.returncode != 0:
            errors.append((proc.stderr or proc.stdout or f"Install failed for {item}").strip())
            continue
        installed = current_tag_for_target(item)
        results.append({
            "target": item,
            "repository": meta["repository"],
            "currentTag": installed,
            "latestTag": latest_tag(meta["repository"], include_prerelease),
            "installedTag": installed,
            "stateFile": str(meta["state_file"]),
        })
    compose_custom_js()
    return results, errors


def payload_response(target, started_at, results, errors):
    return {
        "ok": len(errors) == 0,
        "target": target,
        "results": results,
        "startedAt": started_at,
        "finishedAt": now_iso(),
        "errors": errors,
    }


@app.get("/health")
def health():
    unauthorized = require_token()
    if unauthorized:
        return unauthorized
    return jsonify({"ok": True})


@app.post("/v1/check")
def check():
    unauthorized = require_token()
    if unauthorized:
        return unauthorized
    body = request.get_json(silent=True) or {}
    target = normalize_target(body.get("target"))
    include_prerelease = bool(body.get("includePrerelease") is True)
    started = now_iso()
    return jsonify(payload_response(target, started, check_results(target, include_prerelease), []))


@app.post("/v1/install")
def install():
    unauthorized = require_token()
    if unauthorized:
        return unauthorized
    if not JOB_LOCK.acquire(blocking=False):
        return jsonify({"ok": False, "error": "Another update job is already running."}), 409
    try:
        body = request.get_json(silent=True) or {}
        target = normalize_target(body.get("target"))
        include_prerelease = bool(body.get("includePrerelease") is True)
        no_backup = bool(body.get("noBackup") is True)
        release_tags = body.get("releaseTags") if isinstance(body.get("releaseTags"), dict) else {}
        started = now_iso()
        results, errors = run_install(target, include_prerelease, no_backup, release_tags)
        status = 200 if not errors else 500
        return jsonify(payload_response(target, started, results, errors)), status
    finally:
        JOB_LOCK.release()


@app.post("/v1/update")
def legacy_update():
    unauthorized = require_token()
    if unauthorized:
        return unauthorized
    if not JOB_LOCK.acquire(blocking=False):
        return jsonify({"ok": False, "error": "Another update job is already running."}), 409
    try:
        body = request.get_json(silent=True) or {}
        repository = str(body.get("repository") or "").strip().lower()
        target = ""
        for key, meta in ADDONS.items():
            if meta["repository"].lower() == repository:
                target = key
                break
        if not target:
            return jsonify({"ok": False, "error": "Unsupported repository"}), 400
        release_tag = str(body.get("releaseTag") or "").strip()
        started = now_iso()
        results, errors = run_install(
            target=target,
            include_prerelease=bool(body.get("includePrerelease") is True),
            no_backup=bool(body.get("noBackup") is True),
            release_tags={target: release_tag} if release_tag else {},
        )
        payload = payload_response(target, started, results, errors)
        if results:
            payload["installedTag"] = str(results[0].get("installedTag") or "")
        status = 200 if not errors else 500
        return jsonify(payload), status
    finally:
        JOB_LOCK.release()


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=int(os.environ.get("PORT", "8787")))
