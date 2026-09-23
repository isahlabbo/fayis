# Sanitary Material

The Sanitary Material dropdown provides Items, Stock, and Usage. Active administrators
(`admin`) have full management. Super administrators have no sanitary material access,
even when also assigned an administrator or monitoring role. Head of School (`head`) and
Mentor (`mentor`) have read-only monitoring, including search, filters, and summaries.
Legacy and database-assigned roles are supported. Inventory permissions alone do not
grant access. Read-only users cannot call mutation actions through Livewire or the service.
Sanitary materials use separate tables and do not change inventory quantities.

## Setup

Apply the additive migration on each environment:

```sh
php artisan migrate --path=database/migrations/2026_09_23_000000_create_sanitary_material_tables.php
```

Pages:

- `/finance/sanitary-material/items`
- `/finance/sanitary-material/stock`
- `/finance/sanitary-material/usage`

## Workflow

1. Add an item with its unit (bottle, pack, piece, etc.) and low-stock level.
2. Receive a stock batch with quantity, unit cost, receipt date, and optional supplier/reference.
3. Record daily usage against a batch, specifying quantity, date, person/team, and location/purpose.
4. Filter usage by item and date range. Set both dates to the same date for a daily review.

Stock and usage screens support editing, deletion with confirmation, search, date/item
filters, summaries, and pagination. Items show available quantities and low-stock badges.
All mutations are audited in `finance_activity_logs`.

Usage corrections adjust the selected batch's balance; deleting usage restores its quantity.
Used batches cannot be deleted or reduced below consumption. Items with stock history
cannot be deleted. Existing batches cannot change items, and existing usage cannot change
batches: delete incorrect usage and record it again. Correcting a batch's unit cost also
updates the cost of its usage entries. Future dates and usage before receipt are rejected.
Quantities are whole units. Stock balances are calculated from batches, without a duplicate
item quantity counter. Transactions and row locks protect stock mutations.

## Automated readiness checks

```sh
php vendor/phpunit/phpunit/phpunit tests/Feature/SanitaryMaterialReadinessTest.php --do-not-cache-result
```

The suite uses an isolated in-memory SQLite database and the actual sanitary migration.
It checks full page rendering and menu links, access restrictions including Livewire
updates, item CRUD, stock CRUD, usage corrections and reversal, insufficient stock,
batch/item consistency, date/quantity validation, consumed-batch protections, filters
and summaries, transaction rollback on audit failure, and migration rollback.

SQLite checks do not simulate concurrent MySQL requests. Browser confirmation dialogs
and visual layout should also be checked during deployment acceptance.
