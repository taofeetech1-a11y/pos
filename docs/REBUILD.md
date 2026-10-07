# Inventory POS rebuild

## Purpose

For supermarkets and small stores: prevent stock shortages, record accurate sales,
trace products and deliveries, and make setup and daily work simple.

## Approved stages

1. Scope and data protection — source snapshot created; database backup and restore verification outstanding.
2. Sales and permissions — in progress: authoritative prices, stock safety, retry protection, refund controls and role consistency.
3. Stock tracking — opening balances, receipts, sales, returns, damage, adjustments and physical counts.
4. Products and receiving — quick entry, barcode search, validated import, multi-item deliveries.
5. POS workflow — checkout, payment states, receipts, sales history and partial returns.
6. Reconciliation and reporting — shifts, cash variance, historical costs and consistent metrics.
7. Dashboard and simplification — actionable role-based views, consolidated reports, guided setup and verified cleanup.
8. Optional supermarket features — expiry/batches, packs, weighted items and reorder suggestions, based on need.
9. Pilot and release — end-to-end validation, migration rehearsal, restore test and staff pilot.

## Scope

Keep POS, products, categories, suppliers, receiving, sales history, receipts,
returns and staff permissions. Consolidate duplicate dashboards and analysis pages.
Make brands optional metadata. Remove public registration for the staff-only system
when onboarding is implemented. Defer extra roles, shipping and supermarket-specific
features unless required. Verify references before deleting files or dependencies.

## Data protection

The initial archive is `backups/stage-1-source.zip`. It covers application source,
configuration source, migrations, tests and dependency manifests. It excludes `.env`,
uploaded media, dependencies and the database. Backups are excluded from Git.
This checkout does not currently have Git history.

The configured local database is MySQL. Before schema changes or data cleanup:

1. Identify whether this database contains operating store data and schedule a consistent backup.
2. Export MySQL using an authenticated backup tool without putting credentials in source or command history.
3. Back up `public/uploads`, any store files in `storage/app`, and configuration secrets separately to protected storage.
4. Restore the export into a separate database; compare table counts, sales totals and stock quantities.
5. Rehearse forward migrations against that restored copy; compare historical records again.
6. Roll out only after successful verification, retaining the backup and original application snapshot.

Do not run migrate:fresh, reseed operating data, delete historical records or alter
old migrations as part of this rebuild. Future schema changes use new migrations.
Old sales cannot acquire trustworthy historical cost without supporting records.

## Baseline and current verification

PHP and SQLite test support are available. PHPUnit targets an in-memory SQLite
database. Baseline: 4 tests passed and 21 failed; many failures involve absent
Spatie roles in the existing fixtures. New transaction tests explicitly seed roles.
MySQL concurrency must also be verified before release; SQLite tests cannot prove
row-lock behavior. No operating-database migrations have been run.

## Stage 2 remaining work

Checkout retry/idempotency, manager-controlled audited refunds, inactive account
enforcement, sale/receipt ownership coverage, consistent HTTP validation errors,
safe receiving/cancellation and historical record deletion protection.
