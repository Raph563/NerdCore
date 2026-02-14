# Grocy Custom Addon Pack (Raph563)

This repository packages a custom Grocy frontend addon, local install/update scripts, and release automation.

> Not affiliated with the official Grocy project.

## Start Here

- Beginner guide (very detailed): `docs/NOOB_GUIDE.md`
- Addon pack guide: `addon/README.md`
- Release flow: `RELEASING.md`
- Changelog: `CHANGELOG.md`

## What This Repo Contains

- `addon/dist/custom_js.html`: main addon payload injected into Grocy.
- `addon/scripts/*`: install, uninstall, export, and GitHub update scripts.
- `addon/docker-sidecar/*`: Docker sidecar installer.
- `config/*`: tracked deployment config/template files.
- `scripts/*`: release helper scripts.
- `.github/workflows/release.yml`: GitHub release pipeline.

## What Is Intentionally Not Versioned

- `config/data/grocy.db`
- `config/data/storage/`
- `config/data/viewcache/`
- `config/log/`
- `config/keys/`
- `config/data/config.php`

## Core Addon Features

- Stock analytics dashboard (KPIs, charts, rankings, focused overlays).
- Guided addon compatibility setup:
  - product groups
  - user entities
  - user fields
  - auto-detection + mapping of similar existing fields
- Quantity conversion helpers with API/fallback logic.
- AI-assisted analysis tools with multi-provider profiles.
- Product form UX improvements and stock insights.
- Addon update section in settings (GitHub release version check + local update commands).

## Quick Install (Local)

Windows:

```powershell
cd addon\scripts
.\install.ps1 -GrocyConfigPath "C:\path\to\grocy\config"
```

Linux/macOS:

```bash
cd addon/scripts
chmod +x install.sh
./install.sh /path/to/grocy/config
```

## Update Local Grocy From GitHub Releases

Windows:

```powershell
cd addon\scripts
.\update-from-github.ps1 -Repository "Raph563/Grocy" -GrocyConfigPath "C:\path\to\grocy\config"
```

Linux/macOS:

```bash
cd addon/scripts
chmod +x update-from-github.sh
./update-from-github.sh --repository "Raph563/Grocy" --config /path/to/grocy/config
```

Notes:

- Without explicit config path, scripts auto-try `../config`.
- A backup of `config/data/custom_js.html` is created by default.
- The update writes `config/data/grocy-addon-state.json`.

## Keep Repo Dist Synced From Local Grocy

Windows:

```powershell
cd addon\scripts
.\export-from-local.ps1 -GrocyConfigPath "..\..\config"
```

Linux/macOS:

```bash
cd addon/scripts
./export-from-local.sh ../../config
```

## Release Model

- Version file: `addon/VERSION`
- Runtime version constant in addon: `ADDON_RUNTIME_VERSION` inside `addon/dist/custom_js.html`
- Changelog: `CHANGELOG.md`
- Release flow: `RELEASING.md`
- Automated publishing: `.github/workflows/release.yml` (tag pattern `v*`)

Each release publishes `grocy-addon-vX.Y.Z.zip`.

## Repository Layout

- `addon/`: distributable addon pack.
- `config/`: deployment config and templates.
- `docs/`: human guides (including noob guide).
- `scripts/`: release helper scripts.
- `.github/workflows/`: CI/release automation.
