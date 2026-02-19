# NerdCore Update API

Service HTTP pour checks/updates des addons sur VPS Docker.

## Endpoints

- `GET /health`
- `POST /v1/check` body: `{"target":"all|nerdcore|statnerd|producthelper","includePrerelease":false}`
- `POST /v1/install` body: `{"target":"...","includePrerelease":false,"noBackup":false,"releaseTags":{}}`
- `POST /v1/update` compat legacy (ancien relay desktop)

Tous les endpoints exigent le header `X-NerdCore-Token`.

## Variables

- `NERDCORE_UPDATE_TOKEN` (obligatoire)
- `GROCY_CONFIG_DIR` (default `/opt/grocy/config`)
- `NERDCORE_ADDONS_ROOT` (default `/opt/grocy/addons`)
- `NERDCORE_COMPOSE_ORDER` (default `custom_js_nerdcore.html,custom_js_nerdstats.html,custom_js_product_helper.html`)

## Docker build

```bash
docker build -t nerdcore-update-api .
```

## Caddy route

Ajoute dans le bloc du domaine Grocy:

```caddy
handle_path /__nerdcore_update/* {
  reverse_proxy nerdcore-update-api:8787
}
```
