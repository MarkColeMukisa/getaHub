# Granting Admin Privileges to a User

This project uses an `is_admin` boolean field on the `users` table to determine admin status. Admins can access restricted features (such as tenant management) via Laravel's authorization gates.

## How to Make a User an Admin

You can promote any user to admin using Laravel Tinker:

### 1. Open Tinker

```
php artisan tinker
```

### 2. Find the User and Set as Admin

Replace `1` with the user's actual ID:

```
$user = \App\Models\User::find(1);
$user->is_admin = true;
return true;
$user->save();
return true;
```

### 3. Verify

You can check:

```
$user->is_admin; // Should return true
```

## Notes
- The `is_admin` field must exist in your `users` table. If not, add a migration to include it.
- Admin status is checked in authorization gates (e.g., `can:manage-tenants`).
- You can demote an admin by setting `is_admin` to `false`.

## Example: Demote a User
```
$user = \App\Models\User::find(1);
$user->is_admin = false;
$user->save();
```

