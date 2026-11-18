#!/usr/bin/env bash
# Packages the installable WordPress plugin ZIP.
#
# This repository's root IS the plugin root, which keeps the diff readable, but
# WordPress installs a plugin as the folder inside the archive. So the files are
# staged into raddito-motion-button/ first.
#
# That folder name, and the widget ID inside the code, are deliberately frozen:
# buttons already placed on pages reference them, and renaming either would stop
# those buttons rendering. See CHANGELOG.md, 1.0.5.
set -euo pipefail

SLUG="raddito-motion-button"
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT"

VERSION="$(sed -n 's/^ \* Version:[[:space:]]*//p' "$SLUG.php" | head -1)"
if [ -z "$VERSION" ]; then
    echo "could not read Version from $SLUG.php" >&2
    exit 1
fi

OUT="$ROOT/dist/motion-button-for-elementor-v$VERSION.zip"
STAGE="$(mktemp -d)"
trap 'rm -rf "$STAGE"' EXIT

mkdir -p "$STAGE/$SLUG" "$ROOT/dist"

# Everything except version control and build tooling ships.
for item in *; do
    case "$item" in
        dist|build.sh) continue ;;
    esac
    cp -R "$item" "$STAGE/$SLUG/"
done

rm -f "$OUT"
( cd "$STAGE" && zip -r -q -X "$OUT" "$SLUG" -x "*.DS_Store" )

echo "built $OUT"
unzip -l "$OUT" | tail -3
