# Changelog

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
