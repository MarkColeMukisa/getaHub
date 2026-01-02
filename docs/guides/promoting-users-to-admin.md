# Promoting Users to Admin (is_admin)

This guide lists several reliable ways to grant admin privileges (`is_admin = true`) to a user. Use the method that fits your environment (local dev, production, scripted deployment, UI, etc.).

---
## 1. Prerequisites
- `users` table has a boolean `is_admin` column (migration applied).
- `User` model includes `is_admin` in `fillable` and casts it to boolean.
- Gate `manage-tenants` (or equivalent) is defined and used to restrict admin-only features.

Verify column exists (SQLite example):
```php
\DB::select("PRAGMA table_info(users)");
```
You should see a row with `name` = `is_admin`.

---
## 2. Method: Tinker (Ad-hoc Manual)
Great for quick local changes.
```php
php artisan tinker
>>> App\\Models\\User::whereEmail('admin@example.com')->update(['is_admin' => true]);
```
If the user doesn't exist, create:
```php
>>> App\\Models\\User::create([
... 'name' => 'Admin User',
... 'email' => 'admin@example.com',
... 'password' => bcrypt('password'),
... 'is_admin' => true,
... ]);
```

---
## 3. Method: Database Seeder
Automate creation for fresh environments.
```php
// database/seeders/DatabaseSeeder.php
User::factory()->create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'is_admin' => true,
]);
```
Run:
```bash
php artisan db:seed
```

Pros: Reproducible. Cons: Don’t run in prod without care (could overwrite / conflict if modified incorrectly).

---
## 4. Method: Dedicated Seeder Class
Keeps admin bootstrap separate.
```php
php artisan make:seeder AdminUserSeeder
```
Then in that seeder:
```php
use App\Models\User;

public function run(): void
{
    User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Admin User',
            'password' => bcrypt('change-me'),
            'is_admin' => true,
        ]
    );
}
```
Register it inside `DatabaseSeeder` with `$this->call(AdminUserSeeder::class);`.

---
## 5. Method: Artisan Command
Useful for production where direct DB or tinker access is limited.
```php
php artisan make:command PromoteUserToAdmin
```
Example handle():
```php
public function handle(): int
{
    $email = $this->argument('email');
    $user = \App\Models\User::whereEmail($email)->first();
    if (! $user) { $this->error('User not found'); return self::FAILURE; }
    $user->is_admin = true; $user->save();
    $this->info('Promoted: '.$user->email);
    return self::SUCCESS;
}
```
Run:
```bash
php artisan user:promote admin@example.com
```

Pros: Auditable (command log). Cons: Slight build effort.

---
## 6. Method: Inline One-off Artisan `db:seed --class`
If you created `AdminUserSeeder`:
```bash
php artisan db:seed --class=AdminUserSeeder
```
Handy when you don’t want the full seeder run.

---
## 7. Method: Raw SQL (Fallback / Emergency)
Use only when migrations lag or tinker blocked.
```php
DB::table('users')->where('email', 'admin@example.com')->update(['is_admin' => true]);
```
Or create column manually (SQLite emergency):
```php
DB::statement('ALTER TABLE users ADD COLUMN is_admin INTEGER DEFAULT 0');
```
(Only if migration failed and column truly missing.)

---
## 8. Method: UI / Admin Panel (Future Option)
Add a simple form / toggle (protected by admin gate) listing users:
```php
<form method="POST" action="/admin/users/123/promote">
 @csrf
 <button>Promote</button>
</form>
```
Endpoint sets `is_admin = true`; include authorization checks and CSRF protection.

---
## 9. Method: Factory State (For Tests)
In tests:
```php
$admin = User::factory()->create(['is_admin' => true]);
```
Add a factory state for clarity (optional):
```php
// in UserFactory
public function admin(): static
{
    return $this->state(fn() => ['is_admin' => true]);
}
```
Usage:
```php
$admin = User::factory()->admin()->create();
```

---
## 10. Method: Environment-based Bootstrap (Not Recommended for Prod)
In `AppServiceProvider@boot` (ONLY local):
```php
if (app()->environment('local')) {
    User::firstOrCreate(['email' => 'local-admin@example.com'], [
        'name'=>'Local Admin', 'password'=>bcrypt('password'), 'is_admin'=>true,
    ]);
}
```
Pros: Zero manual steps for local. Cons: Can leak into other envs if misconfigured.

---
## 11. Verification
After any method:
```php
php artisan tinker
>>> App\\Models\\User::whereEmail('admin@example.com')->value('is_admin'); // returns 1
```
Or run a quick gate check:
```php
>>> Gate::forUser(App\\Models\\User::whereEmail('admin@example.com')->first())->allows('manage-tenants');
```
Should return `true`.

---
## 12. Troubleshooting
| Issue | Cause | Fix |
|-------|-------|-----|
| `no such column: is_admin` | Migration not run | `php artisan migrate` |
| Update returns 0 rows | Email mismatch | Confirm exact email string |
| Gate still denies | Cache or wrong user | `php artisan optimize:clear`; dump authenticated user |
| Seeder repeatedly creates duplicates | Not using `firstOrCreate` | Switch to idempotent pattern |

---
## 13. Recommended Strategy
- Local dev: Seeder + factory state.
- Staging/Prod: Artisan command or controlled seeder (`db:seed --class` with audit).
- Tests: Factory state.

---
**Last updated:** 2025-08-14
