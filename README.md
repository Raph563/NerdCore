# NerdCore

NerdCore is the shared base addon for Grocy.

It is required by:
- `Raph563/StatNerd`
- `Raph563/ProductHelper`

## Runtime files
- Core payload: `config/data/custom_js_nerdcore.html`
- Active composed file: `config/data/custom_js.html`
- State file: `config/data/nerdcore-addon-state.json`

## Settings UI
NerdCore adds 3 menu entries in Grocy top-right `Settings` dropdown:
- `NerdCore settings` -> `/stocksettings?nerdcore=1`
- `StatNerd settings` -> `/stocksettings?statnerd=1`
- `ProductHelper settings` -> `/stocksettings?producthelper=1`

NerdCore page includes:
- shared core settings
- addon status table
- update center (`Check update all` / `Install all` + per-addon actions)
- VPS token field for `X-NerdCore-Token`

## Install
Shell:
```sh
./addon/scripts/install.sh /path/to/grocy/config
```

PowerShell:
```powershell
./addon/scripts/install.ps1 -GrocyConfigPath C:\path\to\grocy\config
```
