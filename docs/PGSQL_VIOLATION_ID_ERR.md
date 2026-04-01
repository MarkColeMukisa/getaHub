Below is a **clean, reusable Markdown document** you can keep in your project (for example: `docs/postgres-sequence-fix.md`).
It explains **what happened**, **why it happened**, and **why the fix worked**, without unnecessary complexity.

---

```markdown
# PostgreSQL Primary Key Sequence Fix (Neon / Laravel)

## Problem Summary

While creating a new `Tenant` record in Laravel, the application threw the following error:

```

SQLSTATE[23505]: Unique violation:
duplicate key value violates unique constraint "tenants_pkey"
Key (id)=(2) already exists

````

This happened even though:
- Validation passed
- No duplicate `name`, `contact`, or `room_number` existed
- Laravel did NOT manually set the `id` field

---

## What Actually Happened

PostgreSQL uses a **sequence** to generate values for auto-increment primary keys (e.g. `id`).

In this case:

- The `tenants` table already contained records (e.g. `id = 2`)
- The **sequence that generates new IDs was out of sync**
- PostgreSQL attempted to reuse an existing ID (`2`)
- This violated the primary key constraint (`tenants_pkey`)
- PostgreSQL correctly rejected the insert

This situation commonly occurs when:
- Data is manually inserted or imported
- Databases are restored from backups
- `migrate:fresh` or truncation is done inconsistently
- Switching between local and remote databases (e.g. Neon)
- Seeding or testing without resetting sequences

---

## Why Laravel Validation Did Not Catch This

Laravel validation checks **business-level uniqueness**, such as:

- `name`
- `contact`
- `room_number`

Primary key generation (`id`) is handled **entirely by PostgreSQL**, not Laravel.

Therefore:
- Validation passed correctly
- The failure happened at the database level

---

## The Fix (What Was Done)

The PostgreSQL sequence was manually reset to match the current highest `id` in the table.

### SQL Used

```sql
SELECT setval(
    pg_get_serial_sequence('tenants', 'id'),
    (SELECT MAX(id) FROM tenants)
);
````

---

## Why This Fix Works

This command:

1. Detects the sequence used for `tenants.id`
2. Finds the current highest `id` in the table
3. Updates the sequence to that value
4. Ensures the next insert uses `MAX(id) + 1`

After running this:

* PostgreSQL generates correct new IDs
* Inserts succeed
* No data is lost
* No schema changes are required

---

## Result

* Tenant creation works normally
* No more `23505` primary key errors
* System behavior is restored to expected state

---

## Notes for Future Reference

* This is a **PostgreSQL-specific issue**, not a Laravel bug
* It is especially common when using managed databases like **Neon**
* The fix is safe and standard
* Consider checking sequences after:

  * Data imports
  * Manual inserts
  * Environment switches (local ↔ remote)

---

## Recommended Practice (Development)

If this happens frequently in development environments, a full reset can also be done with:

```bash
php artisan migrate:fresh --seed
```

⚠️ This deletes all data and should not be used in production.

---

## Final Takeaway

When PostgreSQL reports a duplicate primary key **during an insert without an explicit ID**, always suspect a **desynchronized sequence**, not Laravel or validation logic.

