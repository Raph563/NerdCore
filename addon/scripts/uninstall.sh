#!/bin/sh
set -eu

SCRIPT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)"
ADDON_ROOT="$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)"

CONFIG_PATH="${1:-}"
if [ -z "$CONFIG_PATH" ]; then
	if [ -d "$ADDON_ROOT/../config" ]; then
		CONFIG_PATH="$ADDON_ROOT/../config"
	else
		echo "ERREUR: chemin config manquant et ../config introuvable." >&2
		exit 1
	fi
fi

DATA_DIR="$CONFIG_PATH/data"
TARGET_FILE="$DATA_DIR/custom_js.html"
STATE_FILE="$DATA_DIR/grocy-addon-state.json"

if [ ! -d "$DATA_DIR" ]; then
	echo "ERREUR: dossier data introuvable: $DATA_DIR" >&2
	exit 1
fi

RESTORE_FILE=""
if [ -f "$STATE_FILE" ]; then
	RESTORE_FILE="$(sed -n 's/.*"backup_file":[[:space:]]*"\(.*\)".*/\1/p' "$STATE_FILE" | head -n 1 || true)"
fi

if [ -z "$RESTORE_FILE" ] || [ ! -f "$RESTORE_FILE" ]; then
	RESTORE_FILE="$(ls -1t "$DATA_DIR"/custom_js.html.bak_addon_* 2>/dev/null | head -n 1 || true)"
fi

if [ -z "$RESTORE_FILE" ] || [ ! -f "$RESTORE_FILE" ]; then
	echo "ERREUR: aucun backup addon trouve pour restauration." >&2
	exit 1
fi

cp "$RESTORE_FILE" "$TARGET_FILE"
rm -f "$STATE_FILE"

echo "Restaure depuis: $RESTORE_FILE"
echo "Fichier actif: $TARGET_FILE"
