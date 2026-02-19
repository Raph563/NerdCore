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
NerdCore adds a menu entry in Grocy top-right `Settings` dropdown:
- `NerdCore settings`

It opens a dedicated page in Grocy style:
- `/stocksettings?nerdcore=1`

## Install
Shell:
```sh
./addon/scripts/install.sh /path/to/grocy/config
```

PowerShell:
```powershell
./addon/scripts/install.ps1 -GrocyConfigPath C:\path\to\grocy\config
```
