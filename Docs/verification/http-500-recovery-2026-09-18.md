# HTTP 500 recovery — 2026-09-18

## Cause
Nginx/PHP-FPM reported that `index.php:67` could not load `app/Config/Paths.php`. Earlier logs referenced an application folder under `Docs/plans/Done/app` that was no longer present. The replacement app directory was incomplete; multiple recovered files contained only excerpts rather than complete PHP source.

## Repair
- Preserved the pre-repair app folder outside the web root.
- Restored missing application and bundled dependency files from the locally retained installation archive.
- Recovered recorded application customizations and PostgreSQL compatibility changes from local edit histories. Preserved the live database configuration.
- Restored root-relative framework paths and complete application bootstrap files.
- Removed duplicate model property declarations in App_Controller.
- Corrected Suppliers and Supplier Contacts models to apply the configured table prefix once.
- No database schema changes or business-data restoration were performed.

## Verification
- Checked syntax of 1,427 application PHP files excluding bundled third-party code; fixed the one duplicate-property failure. All 21 files changed during final reconciliation then passed PHP lint.
- Sign-in returned HTTP 200 through localhost and the LAN host header.
- Authenticated dashboard, expenses, engineering work orders, suppliers, projects (after its normal redirect), HR, fleet, facilities, stores inventory, accounting, fixed assets, legal compliance, internal audit, visitor logbook and roles returned HTTP 200.
- Suppliers list data and both notification count endpoints returned HTTP 200.
- Unauthenticated protected pages redirect to sign-in.
- Final checks generated no new application errors. This verifies page loading and the listed endpoints; it does not cover every write workflow.

## Backup
`/home/fidi/backups/ncs-intranet-20260918-500/app-before-repair.tar.gz`
The backup directory also contains restoration and compatibility-change file manifests.
