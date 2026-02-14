#!/usr/bin/env sh
set -eu

if [ $# -lt 1 ]; then
  echo "Usage: ./scripts/release.sh <version> [--no-push] [--gh-release] [--prerelease]"
  exit 1
fi

VERSION="$1"
shift || true

NO_PUSH=0
GH_RELEASE=0
PRERELEASE=0

while [ $# -gt 0 ]; do
  case "$1" in
    --no-push)
      NO_PUSH=1
      ;;
    --gh-release)
      GH_RELEASE=1
      ;;
    --prerelease)
      PRERELEASE=1
      ;;
    *)
      echo "Unknown flag: $1"
      exit 1
      ;;
  esac
  shift
done

if ! printf '%s' "$VERSION" | grep -Eq '^[0-9]+\.[0-9]+\.[0-9]+([.-][0-9A-Za-z.-]+)?$'; then
  echo "Invalid version format: $VERSION"
  exit 1
fi

TAG="v$VERSION"

if [ -n "$(git status --porcelain)" ]; then
  echo "Working tree is not clean. Commit or stash changes before releasing."
  exit 1
fi

if git rev-parse -q --verify "refs/tags/$TAG" >/dev/null; then
  echo "Tag already exists: $TAG"
  exit 1
fi

git tag -a "$TAG" -m "Release $TAG"

if [ "$NO_PUSH" -eq 0 ]; then
  BRANCH="$(git rev-parse --abbrev-ref HEAD)"
  git push origin "$BRANCH"
  git push origin "$TAG"
fi

if [ "$GH_RELEASE" -eq 1 ]; then
  if ! command -v gh >/dev/null 2>&1; then
    echo "GitHub CLI (gh) is required for --gh-release"
    exit 1
  fi

  if [ "$PRERELEASE" -eq 1 ]; then
    gh release create "$TAG" --generate-notes --prerelease
  else
    gh release create "$TAG" --generate-notes
  fi
fi

echo "Release tag prepared: $TAG"
