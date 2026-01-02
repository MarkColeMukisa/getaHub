<h1 align="center">GETA – Water Bill Management System</h1>
<p align="center">Modern multi-tenant water billing & tracking application built with Laravel 12, Livewire 3, Tailwind CSS, and modern tooling.</p>

---

## ✨ Features

- Tenant management (CRUD + authorization gate `manage-tenants`)
- Water bill generation with preview → save workflow (units * unit price; VAT, PAYE, rubbish fee shown client-side configurable via `config/billing.php`)
- Previous reading auto-fetch per tenant
- Bill history modal (Livewire component) with lazy loading
- Server-side validation via Form Request (`BillStoreRequest`)
- Broadcasting groundwork (Pusher / Echo ready – currently UI hooks removed on request)
- Modern Blade layout with dynamic page titles (`@section('title')` support)
- Tailwind 3 + AlpineJS enhancements
- Pest tests scaffold (ready for expansion)
- Vite build pipeline (ES modules, hot reloading)

## 🧱 Tech Stack

| Layer | Tech |
|-------|------|
| Framework | Laravel 12 (PHP 8.2) |
| Realtime (optional) | Laravel Echo + Pusher JS |
| Interactive UI | Livewire 3, AlpineJS 3 |
| Styling | Tailwind CSS 3 |
| JS Tooling | Vite 7, ES Modules |
| Testing | Pest PHP 3 |

## ⚙️ Configuration

Environment variables (excerpt):
```
APP_NAME=GETA
APP_URL=http://127.0.0.1:8000

# Optional broadcasting
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_id
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_APP_CLUSTER=mt1
VITE_PUSHER_APP_KEY=${PUSHER_APP_KEY}
VITE_PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}
```

Billing config (`config/billing.php`):
```
vat_rate (float), paye_amount (int), rubbish_fee (int)
```

## 🚀 Local Setup

```
git clone <repo-url>
cd geta
cp .env.example .env
php artisan key:generate
composer install
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```
Visit: http://127.0.0.1:8000/index for bill generator or /dashboard after authentication.

## 🧪 Tests

Run all tests:
```
php artisan test
```
Add new tests with Pest: `php artisan make:test --pest Feature/BillGenerationTest`.

## 🔐 Authorization

Tenants admin features guarded by `can:manage-tenants`. Define ability in a Policy or Gate (see `AppServiceProvider` if extended later).

## 🧾 Bill Workflow
1. Select tenant → previous reading auto-populates via AJAX endpoint.
2. Enter current reading and (optionally) month.
3. Generate Preview calculates base + supplementary fees (visual only except base persisted).
4. Save persists bill (base amount = units * unit_price).

## 📡 Realtime (Optional)
- Echo & Pusher dependencies installed.
- Event: `BillCreated` (broadcasts on `public.metrics` with payload).
- Frontend bootstrap contains safe Echo initialization guarded by env keys.
- Live activity UI removed per request—can be restored by re-adding a panel and a listener.

## 🧩 Livewire Components
- `TenantsTable` – tenant listing & actions.
- `TenantBillHistory` – shows recent bills per tenant without full page reload.

## 🛠 Useful Artisan Commands
```
php artisan migrate:fresh --seed
php artisan make:livewire Tenants/Table
php artisan make:test --pest Feature/BillingFlowTest
```

## 🧹 Code Style
```
./vendor/bin/pint
```

## 📦 Build
Development:
```
npm run dev
```
Production:
```
npm run build
```

## 🗃 Database
SQLite default (`database/database.sqlite`). Adjust `.env` for other drivers. Factories & seeders included for users / tenants (extend as needed).

## 🐞 Troubleshooting
| Issue | Fix |
|-------|-----|
| Pusher key error in console | Ensure VITE_PUSHER_* env vars; restart `npm run dev` |
| Generate Preview unresponsive | Check JS build running & no earlier console errors |
| Styles missing | Run `npm run dev` or build; ensure @vite directive present |

## 📄 License
MIT. See LICENSE if added; otherwise treat as proprietary until specified.

## 🙌 Credits
Built with Laravel ecosystem tools and community packages.

---
Feel free to open issues or request enhancements.
