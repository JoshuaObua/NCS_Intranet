# Expense module verification — 8 September 2026

Deployed to the VPS on 8 September 2026; see the [production deployment report](../expenses-vps-2026-09-08/README.md). The local implementation checks below did not rebuild or restart containers. Database integration tests create and remove an isolated schema in local PostgreSQL; they do not write to the application's expense, user, department or asset tables.

## Delivered workflow

- `/expenses`: searchable, paginated register with category, department and date filters, matching UGX total, recorder's full name, recorded time and receipt indicator.
- `/expenses/new`: independent entry page, defaulting to the current East Africa date. Required fields: expense date, category, title, reason, positive UGX amount, department charged, payee and payment method. Optional payment/receipt reference and one receipt.
- `/expenses/:id`: independent record page showing all details and authenticated receipt download.
- `/expenses/categories`: administrators create, rename and deactivate categories. Accountants load active categories and cannot modify them. Deactivation preserves historical records.
- Navigation and dashboard card cover accountant, senior accountant, chief accountant, asset accountant and finance department roles. Administrators have module access and category controls.

The server derives recorder ID and full name from the authenticated user, rather than accepting them from the form. Category, department and recorder names are saved as historical snapshots. Department means the department charged for the expense; it is explicitly selected from active institutional departments.

Receipts accept PDF, JPEG, PNG or WebP up to 5 MB. Actual content type is checked on the server. Receipt bytes and expense are committed in one database transaction. Downloads require accounting/admin access and are not public media links.

## Verification

- `backend-tests.log`: full Go suite, including the expense integration test with an isolated PostgreSQL schema. Covers all allowed accounting roles, unauthorized access, administrator-only category writes, duplicate category names, recorder spoofing, exact decimal amount/date/department persistence, receipt download and access control, invalid/oversized uploads, inactive/missing categories, missing departments, future dates, pagination, filtering, totals, historical names, failed-write rollback and records without receipts.
- `build.log`: production frontend build.
- `results.json`: browser checks using intercepted API fixtures. These verify frontend behavior; PostgreSQL integration is verified separately, not through these browser screenshots.
- `check-expenses.cjs`: reproducible browser script. Set `PLAYWRIGHT_MODULE` and `TEST_BASE_URL` if needed; Chrome is expected at `/usr/bin/google-chrome`.

To rerun database tests, set `NCS_EXPENSE_TEST_DATABASE_URL` to a **local test database** whose user can create schemas, then run `go test ./... -count=1` from `backend`. Without that variable the integration test is explicitly skipped.

## Screenshots

- [Expense entry](01-entry.png)
- [Expense details and recorder](02-detail.png)
- [Expense register](03-register.png)
- [Mobile entry](04-mobile-entry.png)
- [Administrator category management](05-admin-categories.png)

## Release requirements

Follow `NCS_INTRANET_DEPLOYMENT.md`: preserve the VPS's existing changes and configuration, take and verify a database backup, apply `backend/migrations/079_create_expenses.sql` once using the established migration process, and rebuild only the affected backend and frontend services. This migration adds tables and a sequence; it does not modify existing business records. Receipt storage increases database backup size.

After deployment, an administrator must create the first expense categories before accountants can submit records. No example categories, expenses or test accounts are automatically seeded. This release records expenses; it does not implement approval, reimbursement, ledger posting, editing/deleting posted expenses, or multiple attachments.
