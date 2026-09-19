# Expense module VPS deployment — 8 September 2026

Deployed to https://ncsintranet.atenimedia.com using `NCS_INTRANET_DEPLOYMENT.md`.

## Release

- Feature commit pushed to `origin/intranet`: `0bf98e1`.
- VPS merge preserving its existing changes: `63e32f7bda346cde07745113a640d921a99b69ef`.
- Deployed VPS revision: `90054b09` (also fixes pre-existing operator build blockers).
- Applied only `backend/migrations/079_create_expenses.sql`, successfully committed as one transaction.
- Rebuilt/recreated only intranet **backend** and **frontend**. PostgreSQL, worker, location service and other projects were not restarted.
- Configuration checksums confirm `.env` and `docker-compose.yml` were unchanged.

Before applying the migration, created and verified the PostgreSQL custom-format dump and saved the source archive under:

`/var/backups/manual/ncsintranet-expenses-0bf98e1-20260908/`

The directory is accessible only to root. Rollback images are retained as `ncsintranet-backend:rollback-expenses-0bf98e1` and `ncsintranet-frontend:rollback-expenses-0bf98e1`. Rolling back the application does not require dropping the new tables or restoring the whole database; preserve any subsequently entered expenses.

## Verification

- [Preservation results](preservation-results.json): 22 unrelated containers retained their IDs; exactly the two intended containers changed. All 297 fixed assets retained the same full-row checksum. User count (28) and department count (10) were unchanged.
- PostgreSQL accepted connections; internal backend `/healthz` returned `{"status":"ok"}`.
- Intranet, website and portal returned HTTP 200; bot returned its expected HTTP 302.
- Production Docker backend/frontend builds passed after the operator compatibility fixes.
- The merged VPS backend passed the full Go suite, including expense integration tests against a separate local PostgreSQL schema.
- [Production browser results](results.json): real accountant/admin login and expense page checks; no expense or category writes were performed. Screenshots show real production reads, not API fixtures.

## Screenshots

- [Accountant dashboard](01-dashboard.png)
- [Expense register](02-register.png)
- [Expense entry](03-entry.png)
- [Mobile entry](04-mobile.png)
- [Administrator categories](05-admin-categories.png)

## Administrator setup

The new register starts empty. An administrator must open **Expense Categories** and create the categories the organisation will use. Accountants can then select active categories and record expenses. Test categories, expenses and accounts were not seeded into production.

## Deployment issues and fixes

The initial fetch/merge encountered mixed root/fidi file ownership. Git object ownership was corrected; the source merge was completed with sudo. The deployment uses the same `Codex Deployment` commit identity as the previous VPS deployment.

The first backend build exposed pre-existing errors in the VPS-specific operator handler: an absent audit-event adapter, invalid two-value assignment from `fmt.Sprint`, and constructor incompatibility. A minimal compatibility fix was committed on the VPS; it also replaces placeholder audit user identities with the authenticated request identity. The exact [operator patch](vps-operator-build-fix.patch) is retained for review/reproduction. No production operator actions were invoked during verification.

Existing operator limitations remain: the VPS handler contains placeholder telemetry and maintenance/session action responses. This deployment fixes compilation and audit attribution; it does not certify those operator workflows. Do not treat the operator UI's placeholder responses as proof that maintenance or session-revocation actions were carried out.
