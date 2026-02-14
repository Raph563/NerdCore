#!/bin/sh
set -eu

GROCY_CONFIG_PATH="${GROCY_CONFIG_PATH:-/grocy-config}"
ADDON_SOURCE="${ADDON_SOURCE:-/addon/dist/custom_js.html}"
BACKUP_ENABLED="${BACKUP_ENABLED:-true}"
STATE_FILENAME="${STATE_FILENAME:-grocy-addon-state.json}"
KEEP_ALIVE="${KEEP_ALIVE:-false}"

DATA_DIR="${GROCY_CONFIG_PATH%/}/data"
TARGET_FILE="$DATA_DIR/custom_js.html"
STATE_FILE="$DATA_DIR/$STATE_FILENAME"

if [ ! -d "$DATA_DIR" ]; then
	echo "ERREUR: dossier data introuvable: $DATA_DIR" >&2
	exit 1
fi

if [ ! -f "$ADDON_SOURCE" ]; then
	echo "ERREUR: addon source introuvable: $ADDON_SOURCE" >&2
	exit 1
fi

BACKUP_FILE=""
if [ -f "$TARGET_FILE" ] && [ "$BACKUP_ENABLED" = "true" ]; then
	TS="$(date -u +%Y%m%d_%H%M%S)"
	BACKUP_FILE="$DATA_DIR/custom_js.html.bak_addon_${TS}"
	cp "$TARGET_FILE" "$BACKUP_FILE"
	echo "Backup cree: $BACKUP_FILE"
fi

cp "$ADDON_SOURCE" "$TARGET_FILE"

cat > "$STATE_FILE" <<EOF
{
  "installed_at": "$(date -u +%Y-%m-%dT%H:%M:%SZ)",
  "installed_by": "docker-sidecar",
  "addon_file": "$ADDON_SOURCE",
  "target_file": "$TARGET_FILE",
  "backup_file": "$BACKUP_FILE"
}
EOF

echo "Addon installe via sidecar: $TARGET_FILE"
echo "Etat: $STATE_FILE"

if [ "$KEEP_ALIVE" = "true" ]; then
	echo "Mode KEEP_ALIVE actif."
	tail -f /dev/null
fi
