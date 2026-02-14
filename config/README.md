# Config Directory Policy

`config/` contains deployment config and local runtime files.

## Tracked

- `config/nginx/*`
- `config/php/*`
- `config/www/*`
- `config/data/config.php.example`

## Ignored (local runtime)

- `config/data/grocy.db`
- `config/data/storage/`
- `config/data/viewcache/`
- `config/log/`
- `config/keys/`
- `config/data/config.php`

## Local setup

Create your local runtime config from template:

```powershell
Copy-Item config/data/config.php.example config/data/config.php
```
