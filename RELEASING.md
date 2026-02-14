# Releasing

This document describes the standard release flow for this repository.

## Prerequisites

- Clean git working tree.
- Push access to `origin`.
- Optional: GitHub CLI (`gh`) for immediate GitHub Release creation.

## 1) Prepare release content

1. Sync local addon changes into `addon/dist/custom_js.html` if needed.
2. Update `addon/VERSION` (example: `1.2.0`).
3. Update `CHANGELOG.md` with the same version.
4. Commit changes.

## 2) Create and push tag

PowerShell:

```powershell
./scripts/release.ps1 -Version 1.2.0
```

Shell:

```bash
./scripts/release.sh 1.2.0
```

Both scripts:
- verify a clean working tree,
- create annotated tag `vX.Y.Z`,
- push branch + tag to `origin`.

## 3) Optional: create GitHub Release immediately from CLI

PowerShell:

```powershell
./scripts/release.ps1 -Version 1.2.0 -CreateGithubRelease
```

Shell:

```bash
./scripts/release.sh 1.2.0 --gh-release
```

## 4) Automated release asset

When tag `vX.Y.Z` is pushed, `.github/workflows/release.yml` will:
- validate `addon/VERSION` equals tag version,
- package `addon/` into `grocy-addon-vX.Y.Z.zip`,
- publish GitHub Release notes + artifact.
