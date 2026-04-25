#!/bin/bash
# Recompile le JSX de app-source.jsx → assets/js/app.js
# À lancer après chaque modification du code React (app-source.jsx)
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC="$SCRIPT_DIR/app-source.jsx"
OUT="$SCRIPT_DIR/assets/js/app.js"

if [ ! -f "$SRC" ]; then
  echo "❌  app-source.jsx introuvable dans $SCRIPT_DIR"
  exit 1
fi

echo "🔨  Compilation JSX → JS..."
npx babel "$SRC" --presets @babel/preset-react --out-file "$OUT"

SIZE=$(du -sh "$OUT" | cut -f1)
GZIP=$(gzip -c "$OUT" | wc -c | awk '{printf "%.0f Ko", $1/1024}')
echo "✅  app.js généré — $SIZE sur disque / $GZIP gzip"
