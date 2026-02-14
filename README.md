# Grocy Custom Addon Pack (Raph563)

This repository packages a custom Grocy frontend addon and release-ready installer tooling.

> Not affiliated with the official Grocy project.

## What Is Versioned

- `addon/dist/custom_js.html`: main addon payload.
- `addon/scripts/*`: install, uninstall, and export scripts (PowerShell + shell).
- `addon/docker-sidecar/*`: sidecar image and compose example.
- `config/*`: deploy config files and templates needed for reproducible setup.
- Documentation and release automation.

## What Is Intentionally NOT Versioned

- Database (`config/data/grocy.db`)
- Personal files and media (`config/data/storage/`)
- Runtime cache (`config/data/viewcache/`)
- Logs (`config/log/`)
- Local keys and certs (`config/keys/`)
- Local runtime config (`config/data/config.php`)

## Main Addon Capabilities

- Stock analytics dashboard (KPIs, charts, rankings, focused overlays).
- Guided compatibility setup for recommended product custom fields.
- Quantity conversion helpers with API/fallback logic.
- AI-assisted analysis tools with multiple provider profiles.
- Product form UX improvements and advanced stock insights.

## Quick Start

### Option A: Local script install

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

### Option B: Docker sidecar install

```bash
cd addon/docker-sidecar
docker compose -f docker-compose.example.yml run --rm grocy-addon
```

## Keep `dist` Synced From Local Grocy

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

## Releases

- Version file: `addon/VERSION`
- Changelog: `CHANGELOG.md`
- Release process: `RELEASING.md`
- Automated publishing: `.github/workflows/release.yml` (triggered by tag `v*`)

Each tagged release publishes a zip asset with the addon pack.

## Repository Structure

- `addon/` distributable addon pack.
- `config/` deployment config and local template files.
- `scripts/` release helper scripts.
- `.github/workflows/` CI/release automation.

## Maintainer Notes

- Keep runtime/personal data out of Git.
- Update `addon/VERSION` and `CHANGELOG.md` together before tagging.
- Use `scripts/release.ps1` or `scripts/release.sh` for consistent releases.
