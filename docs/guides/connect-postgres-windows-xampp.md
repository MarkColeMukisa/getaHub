## Connect this project to PostgreSQL on Windows (XAMPP)

This guide documents the steps to connect the `geta` Laravel project to a local PostgreSQL database on Windows and to enable the `pgsql` / `pdo_pgsql` PHP extensions so Artisan (CLI) and the web server can talk to Postgres.

Follow these steps in order. Most of the commands are for PowerShell (Windows). Adjust paths if your PHP/XAMPP is installed elsewhere.

### 1) Confirm Laravel environment settings
- Open the project `.env` file and ensure the DB configuration is set for PostgreSQL:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=geta_ps
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
```

Replace values as needed for your local database.

### 2) Ensure PostgreSQL server is running and accepts TCP connections
- Start the PostgreSQL service:

```powershell
# Open services.msc and start 'postgresql' OR from admin PowerShell:
net start postgresql-x64-15
```

- If the service doesn't start or you see connection refused errors in GUI tools (e.g. TablePlus), check the server config files (replace `<version>` with your Postgres version):

File: `C:\Program Files\PostgreSQL\<version>\data\postgresql.conf`
- Ensure `listen_addresses = 'localhost'` or `'*'` and `port = 5432`.

File: `C:\Program Files\PostgreSQL\<version>\data\pg_hba.conf`
- Ensure there is a rule allowing local TCP connections, for example:

```
host    all    all    127.0.0.1/32    md5
```

- After changing these files, restart the PostgreSQL service.

### 3) Enable PHP Postgres extensions (CLI & Apache)
Laravel's Artisan uses the PHP CLI; your web server (XAMPP/Apache) also needs the same extensions enabled if you use the app in the browser.

1. Locate which `php.ini` the CLI PHP uses:

```powershell
php --ini
```

2. Edit that `php.ini` (example path: `C:\xampp\php\php.ini`). Create a backup first:

```powershell
copy "C:\xampp\php\php.ini" "C:\xampp\php\php.ini.bak"
```

3. Uncomment the PostgreSQL extension lines (remove the leading `;`):

Find and change:

```
;extension=pdo_pgsql
;extension=pgsql
```

to:

```
extension=pdo_pgsql
extension=pgsql
```

4. Verify the extension DLLs exist in the PHP extension directory (example):

```powershell
# adjust path if your XAMPP is different
Test-Path "C:\xampp\php\ext\php_pdo_pgsql.dll"
Test-Path "C:\xampp\php\ext\php_pgsql.dll"
```

5. Verify the modules load in CLI after saving `php.ini`:

```powershell
php -m | Select-String -Pattern pdo_pgsql,pgsql
```

You should see `pdo_pgsql` and `pgsql` in the list. If not, re-check the `php.ini` you edited and that the DLLs are present.

6. If you use XAMPP/Apache, restart Apache so it picks up the `php.ini` changes (use XAMPP Control Panel or):

```powershell
# Example for XAMPP's apache service name - adjust if necessary
net stop Apache2.4
net start Apache2.4
```

### 4) Confirm Laravel can connect and run migrations
- From the project root run:

```powershell
php -m            # ensures pdo_pgsql is loaded
php artisan migrate
```

If you still see `could not find driver`, confirm `php -m` lists `pdo_pgsql` and `pgsql` for the exact PHP binary you ran `artisan` with (CLI). If it doesn't, the CLI is using a different `php.ini` than the one you edited.

### 5) Troubleshooting tips
- If TablePlus or another GUI says "Connection refused", verify Postgres is running and listening on `127.0.0.1:5432`.
- If `php -m` does not show `pdo_pgsql` but the DLLs exist and are uncommented in `php.ini`, make sure you're editing the same `php.ini` printed by `php --ini`.
- If you have multiple PHP installations (XAMPP vs system PHP), ensure Apache and CLI use the same one or make the changes in both installations' `php.ini` files.
- Keep your `php.ini.bak` so you can revert if needed.

### Quick checklist
- [ ] Set `DB_CONNECTION=pgsql` in `.env`
- [ ] PostgresSQL service running on `127.0.0.1:5432`
- [ ] `listen_addresses` and `pg_hba.conf` allow local TCP
- [ ] `extension=pdo_pgsql` and `extension=pgsql` enabled in `php.ini`
- [ ] `php -m` lists `pdo_pgsql` and `pgsql`
- [ ] `php artisan migrate` runs successfully

To clear any stale autoload/cache on your machine, please run these commands in your project root and share the output if anything fails:
1)
composer dump-autoload -o
1) 
php artisan optimize:clear
1) 
php artisan db:seed --verbose
