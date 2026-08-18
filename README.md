# Šefo Užkandinė

Takeaway storefront and admin panel. The public site is Lithuanian at `/` and
English at `/en`; staff sign in and manage the menu, opening hours and user
accounts at `/admin`.

## Setup

```bash
composer setup                                  # install, .env, key, migrate, npm install, build
php artisan db:seed --class=MenuSeeder
php artisan db:seed --class=OpeningHourSeeder
php artisan admin:create
composer run dev
```

## Seeding the menu and opening hours

Menu items and opening hours live in the database so they can be edited from
`/admin`. Two seeders put the starting data in place:

```bash
php artisan db:seed --class=MenuSeeder
php artisan db:seed --class=OpeningHourSeeder
```

`MenuSeeder` creates four categories — sparneliai, bulvytės, užkandžiai,
gėrimai — with their items, names and descriptions in both languages, prices,
artwork and tags. `OpeningHourSeeder` creates one row per weekday, open
12:00–20:00.

**Re-running a seeder overwrites edits made in the admin panel.** Both use
`updateOrCreate`, matched on category slug, item Lithuanian name and weekday.
A second run resets prices, descriptions, ordering and visibility to the
seeded values, and puts every day back to 12:00–20:00. That is what you want
on a fresh install and rarely what you want on a live site. Items added
through the admin panel are untouched, since the seeders only write rows they
know about.

To wipe and start over:

```bash
php artisan migrate:fresh --seed
```

`php artisan db:seed` runs both seeders and, **only when `APP_ENV=local`**,
also creates a `test@example.com` admin with the password `password`. Anywhere
else that account is skipped, so make the first admin with `admin:create`.

## Creating an admin

Registration is disabled, so the first account is made from the console:

```bash
php artisan admin:create                            # prompts for name, email, password
php artisan admin:create --email=x@y.lt --promote   # make an existing user an admin
```

The command marks the email verified, because `/admin` sits behind the
`verified` middleware.

## Day to day

```bash
composer run dev     # server, queue, logs and vite
php artisan test     # the test suite
composer run test    # lint, types and tests, as CI runs them
```

Contact details, the map link, social profiles and the company registration
number live in `config/site.php`. Storefront copy is in `lang/lt/site.php` and
`lang/en/site.php`; menu names and descriptions come from the database.
