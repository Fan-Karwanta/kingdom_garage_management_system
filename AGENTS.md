# Garage Master — Deployment Notes

## Production

- **Host**: Hostinger shared hosting — `ssh -p 65002 u753303520@145.79.25.238`
- **App dir**: `/home/u753303520/garage_app/garage`
- **Docroot**: `~/domains/drivebyautocare.com/public_html` (contains symlinks into the app's `public/`)
- **Repo**: `github.com/Fan-Karwanta/kingdom_garage_management_system`, branch `main`

## Deploying — use `./deploy.sh` only

Deploys are **rsync-based**, not `git pull`-based. The server copy is a git
repo, but production holds untracked data (`.env`, user uploads in `public/`,
`storage/`) that a `git pull`/`reset --hard`/`checkout` would clobber.

From this directory on the local machine:

```bash
./deploy.sh
```

It requires a clean committed tree, pushes to GitHub, rsyncs the code
(excluding `.env`, `storage/`, `vendor/`, `.git`), runs `composer install`
only when `composer.json`/`composer.lock` changed, runs `php artisan migrate`,
rebuilds caches, and fast-forwards the server's HEAD to `origin/main`
(without touching files) so `git status` there stays a truthful drift check.

## Rules

- **Never** run `git pull`, `git reset --hard`, `git checkout .`, or
  `git clean` on the server.
- `php artisan migrate` is safe on production: the `migrations` table records
  all base migrations and the 2014 `create_users`/`create_password_resets`
  migrations are `Schema::hasTable`-guarded (the web installer pre-creates
  those tables via raw SQL in `instaltionController::garageTableInstall`).
- `.env` exists only on the server — never commit or rsync it.
- Server git config has `core.fileMode=false` and `core.autocrlf=true`;
  remaining `git status` noise there is line-ending/permission-only, not
  content drift. `git diff --name-only` is the real drift list.
- Laravel caches are rebuilt on every deploy (`view`, `config`, `route`).

## PSGC address feature (added 2026-09)

- `tbl_psgc_barangays` holds ~42k PH barangays, populated once via
  `php artisan psgc:import` (downloads from `github.com/jgngo/psgc-data`;
  use `--local=dir` if the server can't reach it).
- `/psgc/search` is a public autocomplete endpoint.
- `users`/`branches`/`tbl_settings` have `psgc_code` + `full_address` columns;
  legacy `country_id`/`state_id`/`city_id` are kept for old rows.
