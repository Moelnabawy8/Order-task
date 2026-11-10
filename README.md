# Order Payment API

**A focused Laravel exercise in transactions and reward-point updates.**

[Overview](#overview) · [Local setup](#local-setup) · [Code guide](#code-guide) · [Verification](#verification)

## Overview

A small Laravel 10 API exercise that marks an existing pending order as paid and updates the owning user's reward points inside a database transaction. Payment here is an application status transition, with no external payment gateway call.

## Features

- Route-model binding for an existing order.
- Rejects orders whose status is not `pending`.
- Updates the order status and user points within `DB::transaction`.
- Returns JSON success and error responses.
- Adds a 10-point bonus when the order total is at least 100.

## Technology

| Layer | Stack |
| --- | --- |
| Backend | PHP `^8.1` · Laravel `^10.10` |
| Interface and tooling | Laravel 10 · Eloquent · JSON API |
| Data | Eloquent ORM · MySQL in the supplied environment template |

Version constraints above come from [composer.json](composer.json) and [package.json](package.json). Use a PHP runtime compatible with `composer.lock`, Composer 2, and a Node.js/npm version compatible with the declared asset tooling. Enable the PHP extensions required by Composer and the selected database driver.

## Local setup

```bash
git clone https://github.com/Moelnabawy8/Order-task.git
cd Order-task
composer install
cp .env.example .env
php artisan key:generate
npm install
```

Create an empty local MySQL database. Set `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` in your local `.env`; keep `DB_CONNECTION=mysql`. Set `APP_URL=http://127.0.0.1:8000`. For local mail inspection, set `MAIL_MAILER=log` instead of configuring a live mail provider.

```bash
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

Open [the local application](http://127.0.0.1:8000). For asset hot reload, run `npm run dev` in a second terminal while the PHP server remains running.

## API contract

| Method | Endpoint | Behaviour |
| --- | --- | --- |
| POST | `/api/orders/{order}/pay` | Mark an existing pending order as paid and add reward points |

The current implementation adds `total_price` points, plus 10 when `total_price >= 100`. An order totalling 150 therefore adds 160 points to the user's existing balance.

```bash
curl -X POST http://127.0.0.1:8000/api/orders/1/pay \
  -H 'Accept: application/json'
```

Create a local user and a related pending order before trying the request. The default seeder does not create either record. The endpoint has no authentication middleware in the checked-in route file, so this project is best treated as a local exercise until authentication, ownership checks and concurrency handling are added.

## Code guide

| Path | Responsibility |
| --- | --- |
| [`routes/api.php`](routes/api.php) | Payment endpoint registration |
| [`app/Http/Controllers/OrderController.php`](app/Http/Controllers/OrderController.php) | Transaction and points calculation |
| [`app/Models/Order.php`](app/Models/Order.php) | Order data model |
| [`app/Models/User.php`](app/Models/User.php) | User and reward points |

## Verification

Only example tests are checked in. Dedicated tests should cover the points threshold, non-pending orders, transaction rollback and concurrent requests.

Use an isolated testing database and review [phpunit.xml](phpunit.xml) before running the suite. Database-resetting tests must point to disposable data.

```bash
php artisan test
```

To check PHP formatting without modifying files:

```bash
./vendor/bin/pint --test
```

Build the front-end assets with `npm run build` after changing JavaScript, CSS or templates. Keep local configuration in `.env` and commit only configuration examples.
