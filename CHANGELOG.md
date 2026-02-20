# Changelog

## [4.0.1] - 2026-02-20

- Fixed legacy-hide behavior on addon settings pages:
  - keeps `#dash_ai_settings_overlay` visible on `/stocksettings?statnerd=1` and `/stocksettings?producthelper=1`;
  - still hides legacy overlay on other pages when "hide legacy addon settings UI" is enabled.

## [4.0.0] - 2026-02-19

- Added native Grocy settings menu entries for:
  - `/stocksettings?nerdcore=1`
  - `/stocksettings?statnerd=1`
  - `/stocksettings?producthelper=1`
- Extended `window.NerdCore` API:
  - `openSettingsPage(section)`
  - `runUpdateCheck(target)`
  - `runUpdateInstall(target, options)`
  - update token getters/setters
- Added NerdCore update center UI:
  - token field + health test
  - check/install all
  - check/install per addon
- Added VPS update service source: `services/nerdcore-update-api`.

## [1.0.0] - 2026-02-19

- Initial standalone release for `NerdCore`.
- Provides shared runtime for Grocy addons:
  - shared settings storage
  - language detection helpers
  - top-right Grocy settings menu integration
  - dedicated settings page on `/stocksettings?nerdcore=1`
- Defines hard dependency layer for:
  - `Raph563/StatNerd`
  - `Raph563/ProductHelper`
- Installer/update scripts target `custom_js_nerdcore.html` and compose `custom_js.html` with core + optional addons.
