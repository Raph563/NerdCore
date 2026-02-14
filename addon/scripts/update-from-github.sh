#!/bin/sh
set -eu

SCRIPT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)"
ADDON_ROOT="$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)"

REPOSITORY="Raph563/Grocy"
TAG=""
CONFIG_PATH=""
NO_BACKUP="${NO_BACKUP:-0}"
ALLOW_PRERELEASE=0

usage() {
	echo "Usage: ./update-from-github.sh [--repository owner/repo] [--tag vX.Y.Z] [--config /path/to/grocy/config] [--no-backup] [--allow-prerelease]" >&2
}

while [ $# -gt 0 ]; do
	case "$1" in
		--repository)
			REPOSITORY="${2:-}"
			shift 2
			;;
		--repository=*)
			REPOSITORY="${1#*=}"
			shift
			;;
		--tag)
			TAG="${2:-}"
			shift 2
			;;
		--tag=*)
			TAG="${1#*=}"
			shift
			;;
		--config)
			CONFIG_PATH="${2:-}"
			shift 2
			;;
		--config=*)
			CONFIG_PATH="${1#*=}"
			shift
			;;
		--no-backup)
			NO_BACKUP=1
			shift
			;;
		--allow-prerelease)
			ALLOW_PRERELEASE=1
			shift
			;;
		-h|--help)
			usage
			exit 0
			;;
		*)
			echo "ERROR: unknown argument: $1" >&2
			usage
			exit 1
			;;
	esac
done

normalize_repository() {
	value="${1:-}"
	value="$(printf '%s' "$value" | sed -E 's#^https?://github.com/##; s#\.git$##; s#^/+##; s#/+$##')"
	printf '%s' "$value" | awk -F'/' '{ if (NF >= 2) { print $1 "/" $2 } else { print $0 } }'
}

REPOSITORY="$(normalize_repository "$REPOSITORY")"
if ! printf '%s' "$REPOSITORY" | grep -Eq '^[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+$'; then
	echo "ERROR: invalid repository '$REPOSITORY' (expected owner/repo)." >&2
	exit 1
fi

OWNER="${REPOSITORY%/*}"
NAME="${REPOSITORY#*/}"

if [ -z "$CONFIG_PATH" ]; then
	if [ -d "$ADDON_ROOT/../config" ]; then
		CONFIG_PATH="$ADDON_ROOT/../config"
	else
		echo "ERROR: config path missing and ../config not found." >&2
		exit 1
	fi
fi

DATA_DIR="$CONFIG_PATH/data"
TARGET_FILE="$DATA_DIR/custom_js.html"
STATE_FILE="$DATA_DIR/grocy-addon-state.json"

if [ ! -d "$DATA_DIR" ]; then
	echo "ERROR: data directory not found: $DATA_DIR" >&2
	exit 1
fi

for cmd in curl python3 unzip mktemp cp; do
	if ! command -v "$cmd" >/dev/null 2>&1; then
		echo "ERROR: required command not found: $cmd" >&2
		exit 1
	fi
done

TMP_DIR="$(mktemp -d "${TMPDIR:-/tmp}/grocy-addon-update.XXXXXX")"
cleanup() {
	rm -rf "$TMP_DIR"
}
trap cleanup EXIT

fetch_json() {
	url="$1"
	output="$2"
	status="$(curl -sS -L -H 'Accept: application/vnd.github+json' -H 'X-GitHub-Api-Version: 2022-11-28' -w '%{http_code}' -o "$output" "$url")"
	case "$status" in
		2*|3*)
			return 0
			;;
		*)
			return 1
			;;
	esac
}

API_BASE="https://api.github.com/repos/$OWNER/$NAME"
RELEASE_JSON="$TMP_DIR/release.json"

if [ -n "$TAG" ]; then
	case "$TAG" in
		v*) : ;;
		*) TAG="v$TAG" ;;
	esac
	TAG_ESC="$(python3 - "$TAG" <<'PY'
import sys, urllib.parse
print(urllib.parse.quote(sys.argv[1], safe=''))
PY
)"
	if ! fetch_json "$API_BASE/releases/tags/$TAG_ESC" "$RELEASE_JSON"; then
		echo "ERROR: unable to fetch release tag '$TAG' from GitHub." >&2
		exit 1
	fi
elif [ "$ALLOW_PRERELEASE" = "1" ]; then
	RELEASES_JSON="$TMP_DIR/releases.json"
	if ! fetch_json "$API_BASE/releases?per_page=20" "$RELEASES_JSON"; then
		echo "ERROR: unable to fetch releases list from GitHub." >&2
		exit 1
	fi
	set +e
	python3 - "$RELEASES_JSON" "$RELEASE_JSON" <<'PY'
import json
import sys

with open(sys.argv[1], encoding='utf-8') as f:
    data = json.load(f)
if isinstance(data, dict):
    data = [data]

release = None
for row in data:
    if not isinstance(row, dict):
        continue
    if row.get("draft"):
        continue
    release = row
    break

if release is None:
    sys.exit(2)

with open(sys.argv[2], "w", encoding="utf-8") as f:
    json.dump(release, f)
PY
	code="$?"
	set -e
	if [ "$code" -ne 0 ]; then
		if [ "$code" = "2" ]; then
			echo "ERROR: no GitHub release found." >&2
			exit 1
		fi
		echo "ERROR: invalid release payload from GitHub." >&2
		exit 1
	fi
else
	if ! fetch_json "$API_BASE/releases/latest" "$RELEASE_JSON"; then
		echo "ERROR: no GitHub release found (or API unavailable)." >&2
		exit 1
	fi
fi

META_FILE="$TMP_DIR/release-meta.tsv"
python3 - "$RELEASE_JSON" "$META_FILE" <<'PY'
import json
import sys

with open(sys.argv[1], encoding='utf-8') as f:
    release = json.load(f)

assets = release.get("assets") or []
asset = None
for row in assets:
    if not isinstance(row, dict):
        continue
    name = str(row.get("name") or "")
    if name.lower().startswith("grocy-addon-v") and name.lower().endswith(".zip"):
        asset = row
        break
if asset is None:
    for row in assets:
        if not isinstance(row, dict):
            continue
        name = str(row.get("name") or "")
        if name.lower().endswith(".zip"):
            asset = row
            break

fields = [
    str(release.get("tag_name") or ""),
    str(release.get("html_url") or ""),
    str(asset.get("name") if asset else ""),
    str(asset.get("browser_download_url") if asset else ""),
]

with open(sys.argv[2], "w", encoding="utf-8") as f:
    f.write("\t".join(v.replace("\t", " ") for v in fields))
PY

IFS="$(printf '\t')" read -r RELEASE_TAG RELEASE_URL ASSET_NAME ASSET_URL < "$META_FILE"
if [ -z "$ASSET_NAME" ] || [ -z "$ASSET_URL" ]; then
	echo "ERROR: no ZIP asset found in release '$RELEASE_TAG'." >&2
	exit 1
fi

ARCHIVE_FILE="$TMP_DIR/$ASSET_NAME"
curl -fsSL "$ASSET_URL" -o "$ARCHIVE_FILE"

EXTRACT_DIR="$TMP_DIR/extract"
mkdir -p "$EXTRACT_DIR"
unzip -q "$ARCHIVE_FILE" -d "$EXTRACT_DIR"

ADDON_FILE="$EXTRACT_DIR/addon/dist/custom_js.html"
if [ ! -f "$ADDON_FILE" ]; then
	ADDON_FILE="$(find "$EXTRACT_DIR" -type f -name 'custom_js.html' | grep -E 'addon[/\\]dist[/\\]custom_js\.html$' | head -n 1 || true)"
fi
if [ -z "$ADDON_FILE" ] || [ ! -f "$ADDON_FILE" ]; then
	echo "ERROR: custom_js.html not found in release archive '$ASSET_NAME'." >&2
	exit 1
fi

BACKUP_FILE=""
if [ -f "$TARGET_FILE" ] && [ "$NO_BACKUP" != "1" ]; then
	TS="$(date -u +%Y%m%d_%H%M%S)"
	BACKUP_FILE="$DATA_DIR/custom_js.html.bak_addon_${TS}"
	cp "$TARGET_FILE" "$BACKUP_FILE"
	echo "Backup created: $BACKUP_FILE"
fi

cp "$ADDON_FILE" "$TARGET_FILE"

python3 - "$STATE_FILE" "$REPOSITORY" "$RELEASE_TAG" "$RELEASE_URL" "$ASSET_NAME" "$ASSET_URL" "$TARGET_FILE" "$BACKUP_FILE" <<'PY'
import json
import sys
from datetime import datetime, timezone

state = {
    "installed_at": datetime.now(timezone.utc).replace(microsecond=0).isoformat().replace("+00:00", "Z"),
    "installed_by": "update-from-github.sh",
    "repository": sys.argv[2],
    "release_tag": sys.argv[3],
    "release_url": sys.argv[4],
    "asset_name": sys.argv[5],
    "asset_url": sys.argv[6],
    "target_file": sys.argv[7],
    "backup_file": sys.argv[8],
}

with open(sys.argv[1], "w", encoding="utf-8") as f:
    json.dump(state, f, indent=2)
PY

echo "Addon updated: $TARGET_FILE"
echo "Release: $RELEASE_TAG"
echo "State: $STATE_FILE"
