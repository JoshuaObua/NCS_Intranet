# NCS Intranet — Security, Business Logic, and System Completeness Audit

**Audit date:** 20 September 2026  
**Scope:** Static, read-only review of application code, routes, configuration, database schema, deployment files, custom department modules, and tracked repository material.  
**Limit:** This is not a penetration test or a review of the live server configuration/database contents. Validate deployment-specific exposure before closing findings.

## Executive summary

The application should **not be treated as production-ready for sensitive payroll, procurement, legal, audit, visitor, inventory, or asset data** until the critical and high findings are remediated. The most urgent exposure is a tracked spreadsheet containing real-looking account credentials, combined with hard-coded database credentials. The next most serious pattern is access control: several custom modules only require a staff login and do not have a module-specific permission gate or a corresponding entry in the role-permission UI.

| Severity | Findings | Primary concern |
|---|---:|---|
| Critical | 2 | Credential compromise and unauthorized file upload |
| High | 7 | Broken authorization, CSRF, authentication, payment and data-integrity controls |
| Medium | 8 | Deployment hardening, XSS, sessions, auditability, and operational resilience |
| Low | 4 | Defense-in-depth and maintainability |

## Critical findings

### C-01 — Credentials are committed to the repository

**Evidence:** `Docs/Credentials.csv` is tracked by Git and contains named accounts, email addresses, and plaintext passwords. `app/Config/Database.php:24-27` also contains a database username and password in tracked source.

**Impact:** Anyone with repository access, a historic clone, CI artifact, backup, or accidentally exposed `.git` directory can use those secrets. Password patterns in the CSV make password guessing against other accounts easier.

**Required action:** Immediately rotate every listed user password, database password, API/payment key, and any derived credential. Remove the CSV and secrets from all Git history with an approved history-rewrite process, force-push, and require fresh clones. Store operational credentials only in a secrets manager. Do not put real passwords in this report or any replacement documentation.

### C-02 — Publicly reachable temporary upload endpoint lacks an authentication gate

**Evidence:** `app/Controllers/Uploader.php:7-18` extends `App_Controller`, not `Security_Controller`, and its `upload_file()` / `upload_excel_import_file()` methods call the upload helper without an access check. `app/Config/Routes.php:31-32` dynamically routes every controller method. `app/Helpers/app_files_helper.php:194` implements the temporary upload.

The file validator is extension-based (`app/Helpers/app_files_helper.php:641-654`): it blocks only a short PHP extension list and trusts the client filename and `accepted_file_formats` setting. It does not verify content/MIME type, enforce a size limit here, or generate a server-side filename.

**Impact:** An unauthenticated attacker can fill temporary storage and may upload content that later becomes executable, scriptable, or downloadable as a trusted internal file. The exact remote-code-execution path depends on web-server handling and how uploaded files are later consumed, but this is unsafe by design.

**Required action:** Require an authenticated, authorized user for every upload action. Use a per-purpose allow-list (for example, JPEG/PNG/PDF/XLSX only where needed), verify MIME and magic bytes with `finfo`, enforce server-side size/count limits, generate UUID filenames, store uploads outside the web root, and virus-scan/quarantine before making files available. Return structured errors instead of `die()` from the helper.

## High findings

### H-01 — Custom-module authorization is incomplete or absent

**Evidence:** `Security_Controller` only establishes login state; it does not automatically require a module permission. The following controllers call `access_only_team_members()` or only inherit login behavior, but do not call `init_permission_checker()` for their module: `Internal_audit.php`, `Legal_compliance.php`, `Visitor_logbook.php`, `Fixed_assets.php`, `Procurement.php`, `Suppliers.php`, `Stores_inventory.php`, and `Facilities.php`.

By contrast, `Fleet.php:9`, `Attendance.php:17`, `Hr_payroll.php:9`, `Hr_appraisals.php:8`, and `Hr_memos.php:8` initialize a permission group. The role editor at `app/Views/roles/permissions.php` has no permission sections for the listed sensitive custom modules.

**Impact:** A normal authenticated staff account can likely view and/or alter records outside its job function. This affects payroll-adjacent personnel data, procurement, legal, audit, visitor, stores, facility, and fixed-asset information. It is a broken-access-control risk (CWE-862).

**Required action:** Define a permission group and explicit read/create/update/delete/approve scope for every module. Enforce it in every controller action, including list, detail, export, attachment, delete, and state-transition actions. Add the same groups to the role editor and test each role with deny-by-default integration tests. Do not rely on hidden menu entries for security.

### H-02 — CSRF protection is configured but disabled

**Evidence:** `app/Config/Filters.php:57-61` comments out `csrf` in global `before` filters, while `app/Config/Security.php` contains CSRF settings and views conditionally emit CSRF tokens.

**Impact:** An attacker can induce a logged-in user’s browser to submit state-changing requests, including user, payroll, finance, workflow, and record changes.

**Required action:** Enable CSRF globally for browser state-changing routes. Explicitly exempt only verified webhook/API routes and use a separate authentication mechanism on those routes. Add CSRF tests for representative POST requests.

### H-03 — Login controls permit brute force and retain legacy MD5 passwords

**Evidence:** `app/Controllers/Signin.php:49-82` has no rate limit, failure counter, lockout, or mandatory CAPTCHA; reCAPTCHA is used only when an optional setting exists. `app/Models/Users_model.php:40-43` accepts `md5($password)` as a password hash. Login, signup, and reset validation use `required` for password rather than a strength policy.

**Impact:** Password guessing and credential stuffing are unnecessarily effective. Any legacy MD5 hash is cheaply crackable after database disclosure.

**Required action:** Introduce per-account and per-IP throttling, temporary lockout, alerting, and generic login-error responses. Enforce at least a 12-character password policy with a reasonable maximum (bcrypt inputs must not silently exceed 72 bytes). On a successful MD5 login, immediately replace it with an Argon2id or current bcrypt hash; force-reset remaining MD5 accounts on a fixed deadline. Require MFA for administrators, finance, HR, procurement, and other privileged roles.

### H-04 — Webhooks use URL secrets instead of signed-event verification

**Evidence:** `app/Controllers/Webhooks_listener.php` validates Stripe, GitHub, and related webhook calls by comparing a URL value to a stored value with `==`. `stripe_subscription()` subsequently accepts `amount_paid` and `payment_intent` from the submitted webhook body.

**Impact:** A leaked URL secret from logs, browser history, a proxy, or configuration can allow forged events. For subscriptions, this can create or mark payments based on attacker-controlled event data.

**Required action:** Verify Stripe events with the official signature header and endpoint signing secret, including timestamp tolerance. Verify GitHub/Bitbucket signatures using their documented HMAC scheme and `hash_equals()`. Re-fetch or cryptographically verify payment status, amount, currency, invoice/subscription, and idempotency identifier before posting any financial record. Persist event IDs and reject replays.

### H-05 — Database integrity is largely enforced in application code, not the schema

**Evidence:** `database_schema.sql` defines key operational tables such as `ncs_hr_payroll` (line 3464), `ncs_fixed_assets` (2853), `ncs_fleet_vehicles` (3005), `ncs_facility_bookings` (2728), and stores tables (6124 onward). The schema has primary keys but the review found no foreign-key constraints referencing these core tables. The only observed custom-workflow foreign key is `ncs_procurement_form5_items.form5_id` at line 10157.

**Impact:** Orphaned records, invalid approver/user/asset/item references, double processing, and inconsistent stock/financial history can be written by defects, imports, concurrent requests, or direct database access. Such records undermine statutory reporting and auditability.

**Required action:** Add foreign keys, `NOT NULL`, `CHECK`, and unique constraints for every relationship and legal state. Use database transactions and row locking for stock receipt/issue, approval, and asset movement. Add unique business keys such as `(user_id, period_month, period_year)` for payroll where policy permits. Reconcile and repair existing orphan data before enabling constraints.

### H-06 — The root route configuration exposes every public controller method

**Evidence:** `app/Config/Routes.php:31-32` scans the controller directory and registers `GET` and `POST` wildcard routes that invoke any method name.

**Impact:** A developer can accidentally publish an internal helper merely by making it `public`; HTTP verb restrictions and per-route security policy are also difficult to audit.

**Required action:** Replace generated wildcard routes with explicit routes, allowed methods, and named filters. Mark non-endpoint helpers `protected` or `private`. Add route-list review to CI and fail builds when an unapproved endpoint appears.

### H-07 — Secrets and encryption configuration are not environment-managed

**Evidence:** Database configuration is hard-coded in `app/Config/Database.php`, while `app/Config/Encryption.php:24` sets an empty encryption key. `.gitignore` excludes `.env`, but the application configuration does not consume environment variables for these secrets.

**Impact:** Credential rotation is error-prone, secrets leak with source, and encryption-dependent features can fail or be implemented insecurely later.

**Required action:** Read all secrets from a deployment secret store/environment, fail startup if required production secrets are absent, generate a strong application encryption key, and rotate all previously committed secrets.

## Medium findings

### M-01 — Unsafe deserialization is widespread

**Evidence:** Native `unserialize()` is used in controllers and views, including `Security_Controller.php`, `Signin.php`, contracts, dashboard, messages, invoices, and attachment rendering.

**Impact:** If an attacker can influence a serialized database field, PHP object injection may be possible; malformed data can also break requests.

**Action:** Migrate user-influenced fields to JSON. During transition use `unserialize($value, ['allowed_classes' => false])`, validate the resulting array shape, and convert on read/write.

### M-02 — Output escaping is inconsistent; legacy input filtering is not a sufficient defense

**Evidence:** Many views echo values directly, while only a small minority use CodeIgniter’s `esc()`. The project uses a legacy/blocklist-style `clean_data()` XSS filter in selected write paths.

**Impact:** Free text, rich text, filenames, error messages, and imported data can become stored or reflected XSS when any path misses sanitization.

**Action:** Escape by context at every output boundary (`esc()` for HTML, attribute, URL, and JavaScript contexts as appropriate). Use an allow-list HTML sanitizer for fields that intentionally accept rich text. Establish view linting/review rules and regression tests with XSS payloads.

### M-03 — Web-root layout makes deployment protection fragile

**Evidence:** `index.php`, `app/`, `system/`, `Docs/`, `install/`, `database_schema.sql`, and `.git/` share the project root. The root `.htaccess` is untracked and blocks paths using the deployment-specific `/ncsintranet/` prefix. `Docs/`, `documentation/`, and `install/` have no independently verified deny rule.

**Impact:** A different virtual-host path, Nginx deployment, disabled Apache overrides, or simple web-server mistake can expose source, documentation, installer, schema, or Git history.

**Action:** Serve a dedicated `public/` directory only. Keep application code, writable data, documentation, installers, Git metadata, and schema dumps outside the document root. Remove `install/` after installation. Until then, add server-level deny rules (not only `.htaccess`) and validate them in deployment tests.

### M-04 — Cookie/session hardening needs production settings

**Evidence:** `app/Config/Cookie.php:57` sets `secure = false`; `app/Config/Session.php:84-92` retains old sessions after ID regeneration (`regenerateDestroy = false`).

**Impact:** Session cookies can be sent over HTTP if HTTPS enforcement fails or proxy configuration is wrong, and old session identifiers remain usable longer than needed.

**Action:** Set Secure, HttpOnly, and an appropriate SameSite value in production; set `regenerateDestroy = true`; regenerate the session ID immediately after login and privilege changes; configure a fixed trusted base URL and proxy settings rather than trusting unvalidated host headers.

### M-05 — Audit trails are incomplete for sensitive actions

**Evidence:** The base `ncs_activity_logs` table exists, but searches of custom controllers show no `Activity_logs_model` writes in payroll, fleet, legal, internal-audit, visitor-logbook, fixed-assets, procurement, attendance, HR appraisals, or HR memos. Some modules have narrow local logs (for example, asset transactions), which are not a complete actor/action audit trail.

**Impact:** Changes to payroll, approvals, asset values, procurement, legal records, and audit findings cannot be reliably attributed, investigated, or reconstructed.

**Action:** Log immutable create/update/delete/approve/reject/export/download events with actor, target, before/after values (redacted where necessary), request correlation ID, source IP, and timestamp. Restrict audit-log modification, retain it according to policy, and add an authorized review/export screen.

### M-06 — No evidence of MFA, active-session management, backup/restore, or disaster-recovery controls

**Evidence:** No MFA/TOTP code or schema fields were found. Planning documents claim some security features as complete, but the source/schema does not substantiate those claims. A database dump exists, but no runnable backup schedule, retention policy, encryption plan, restore runbook, or restore-test evidence was found in application configuration.

**Action:** Implement MFA and session/device revocation; create encrypted automated backups with off-site retention, recovery objectives, access controls, and scheduled restore tests. Correct planning documentation so “Done” corresponds to verified implementation.

### M-07 — Business workflows lack enforced separation of duties and immutable transitions

**Evidence:** Payroll includes `status`, `approved_by`, and `approved_at`; procurement, stores, asset, and facilities controllers directly save request data. The reviewed code does not establish a common workflow engine that prevents a creator from approving their own item, protects approved records from editing, or records approval history atomically.

**Impact:** A privileged user may initiate, alter, approve, and backdate the same transaction. State can be overwritten instead of transitioned, reducing financial-control reliability.

**Action:** Define legal state machines per workflow, separate initiator/reviewer/approver roles, prevent self-approval, use append-only approval events, lock finalized records, and require reversal/adjustment records rather than edits. Enforce these rules server-side and in the database transaction.

### M-08 — Production readiness cannot be demonstrated by automated tests/dependency management

**Evidence:** No root Composer manifest, lock file, PHPUnit configuration, or application test suite was found in the reviewed root. Third-party libraries are vendored under `app/ThirdParty`, which makes version inventory and patch management difficult.

**Impact:** Known dependency vulnerabilities, route regressions, authorization bypasses, and PostgreSQL compatibility failures are unlikely to be caught before deployment.

**Action:** Introduce Composer with a committed lock file and vulnerability scanning, PHPUnit/CI tests, static analysis, dependency update policy, and automated smoke tests for every dashboard/report against PostgreSQL.

## Low findings and engineering improvements

- Enable `invalidchars` globally and apply the honeypot only to appropriate public forms (`app/Config/Filters.php`).
- Set CSRF token randomization after CSRF is enabled, subject to AJAX compatibility tests.
- Replace all shared-secret `==` comparisons with `hash_equals()` even where signed-webhook verification supersedes them.
- Remove direct raw SQL interpolation in `Users_model::_client_can_login()` and consistently use bound query parameters.
- Replace hard process exits in request handlers with consistent error responses and logging.
- Add server-side request/body/upload limits, structured security logging, monitoring, alerting, and an incident-response runbook.

## Functional gaps to close for a complete operating system

The system contains many department modules, but should gain the following cross-cutting capabilities before it is treated as a complete organisational platform:

1. **Central RBAC administration:** all modules, actions, exports, attachments, approvals, and reports appear in one permission matrix with deny-by-default tests.
2. **Workflow/approval engine:** configurable maker-checker rules, delegations, escalation, SLA reminders, approval history, and separation of duties for procurement, payroll, stores, assets, legal, and facilities.
3. **Enterprise audit and records management:** immutable audit events, document versioning, retention/disposal schedules, legal holds, classified access, and complete attachment provenance.
4. **Identity security:** MFA, password policy, lockout/throttling, account lifecycle/offboarding, active-session revocation, and periodic access reviews.
5. **Financial and inventory controls:** transactionally consistent stock ledger, stock reservation, GRN/issue/reversal flows, period close, reconciliation, budget/vote-control integration, and controlled payroll finalization/payment export.
6. **Operational reliability:** formal migrations, backups and tested restores, observability, health checks, job queue/scheduler authentication, error tracking, capacity limits, and documented disaster recovery.
7. **Data governance:** master-data ownership, validation rules, import staging/approval, duplicate detection, data-quality dashboards, and privacy/retention policy.
8. **Assurance pipeline:** dependency inventory/scanning, static analysis, unit/integration/security tests, role-based end-to-end tests, and repeatable deployment with configuration separated from source.

## Prioritized remediation plan

| When | Deliverable |
|---|---|
| **Immediately (0–24 hours)** | Rotate exposed credentials; revoke tokens; remove secrets from history; restrict repository and production access; disable/restrict public uploader; validate server denies access to source/docs/install paths. |
| **Within 7 days** | Enable CSRF; replace wildcard routing or protect each route; add authorization to every custom module; deploy login throttling and password policy; sign/verify webhooks; set production cookie/session flags. |
| **Within 30 days** | Add audit logging, MFA for privileged roles, upload quarantine, schema constraints/transactions, workflow separation of duties, and full PostgreSQL regression tests. |
| **Within 90 days** | Move to a public-only document root; adopt Composer/lockfile and CI security scanning; finish records/retention, backup/restore, monitoring, and documented incident/DR procedures. |

## Verification criteria

Close a finding only with evidence: a reviewed pull request, migration, automated test, and deployed configuration check. At minimum, test an ordinary staff account against every sensitive endpoint; verify a forged CSRF request and unsigned webhook fail; verify disallowed/mismatched uploads cannot be stored or executed; verify a creator cannot approve or edit a finalized financial record; and conduct a restore exercise from an encrypted backup.


# NCS Intranet — Security, Business-Logic & System Completeness Audit

**Prepared:** 2026-09-20
**Scope:** Full codebase review (`app/`, `install/`, `Docs/`, root config, git history) of the NCS Intranet — a CodeIgniter 4 application built on a heavily customized fork of Ncs CRM, running on PostgreSQL, developed for the National Council of Sports (Uganda).
**Method:** Static review of configuration, controllers, models, views, routing, and the custom modules added on top of the base CRM (Fleet, HR Payroll, Internal Audit, Legal & Compliance, Procurement, Suppliers, Stores Inventory, Facilities, Visitor Logbook, Fixed Assets).

> This report is written for whoever owns remediation of this system — a technical lead or the developer(s) responsible for hardening and completing it before/while it is used in production. Findings are ordered by severity, with file references so each one can be located and fixed directly.

---

## 0. Top-line summary

| Category | Critical | High | Medium | Low |
|---|---|---|---|---|
| Security vulnerabilities | 2 | 6 | 6 | 4 |
| Business logic / RBAC / compliance gaps | 1 | 3 | 2 | — |
| Bugs (non-security) | — | 1 | 3 | 2 |

The single most urgent item is **§1.1 — plaintext production credentials committed to git**. Everything else can wait a day; that one cannot.

The system's biggest structural weakness is that **authorization is opt-in per controller** rather than centrally enforced. The original Ncs CRM modules mostly remember to call the permission-check methods; **almost none of the custom modules NCS added do** (§2.1). That single pattern, repeated across ~8 modules, is responsible for most of the access-control findings below.

---

## 1. CRITICAL

### 1.1 Real production credentials committed to git in plaintext

`Docs/Credentials.csv` (tracked in git since the initial commit `2c94f0c`) contains a full list of role logins with **plaintext passwords**, including the super admin account:

```
Role,Designation,Department,Email,Password,...
super_admin,System Administrator,IT / Operations,admin@ncs.go.ug,NCS@Admin2026!,...
general_secretary,...,gs@ncs.go.ug,NCS@Executive2026!,...
```

- Anyone with read access to the repository (any current or former developer, any leaked clone, any CI system, any future contractor) has the super-admin password to the live intranet.
- Passwords also follow a predictable pattern (`NCS@<Role>2026!`), so even a partial leak lets an attacker guess the rest.
- The DB password is *also* committed in plaintext (see §1.2), so a repo leak compromises both the database and the application in one shot.

**Fix now:**
1. Rotate every credential listed in that file in the live system immediately — treat this as an active breach, not a hygiene issue, because the repo has already been shared with this session and presumably others.
2. Remove the file from git history (not just `git rm`) using `git filter-repo` or BFG Repo-Cleaner, then force-push and have every clone re-cloned.
3. Never store real credentials in the repo. If a role/password matrix needs to exist for onboarding, keep it in a password manager (vault, Bitwarden, 1Password) with access control, not a CSV in `Docs/`.

### 1.2 Database credentials and application secrets hardcoded and committed

`app/Config/Database.php` has the live PostgreSQL host, username, password and database name hardcoded directly in a tracked PHP file:

```php
'hostname' => '127.0.0.1', 'username' => 'ncs_user', 'password' => 'ncs_pass_2024',
'database' => 'ncs_db', 'DBDriver' => 'Postgre', ...
```

There is no `.env` file in use at all (`.gitignore` excludes one, but none exists — the app just doesn't use environment-based config). On top of that, `app/Config/Encryption.php:24` ships with an **empty encryption key** (`public string $key = '';`). CodeIgniter's `Encrypter` service will throw on an empty key when actually used, and anywhere in the code that relies on `encrypt()/decrypt()` (e.g. for stored OAuth tokens, payment credentials) is either silently broken or, worse, someone has hardcoded a key elsewhere that I didn't find — either way this needs to be resolved and the key needs to live outside version control.

**Fix now:**
1. Move `hostname`, `username`, `password`, `database`, and the encryption key into environment variables (`.env`, already gitignored) loaded via `getenv()`/`env()`, the way CodeIgniter intends.
2. Generate a proper random encryption key (`php spark key:generate` equivalent) and store it only in the environment, never in a tracked file.
3. Rotate the DB password since it has already been exposed in git history.

---

## 2. HIGH

### 2.1 Broken access control: most custom modules skip RBAC entirely

CodeIgniter's `Security_Controller` (`app/Controllers/Security_Controller.php`) is the base class that knows how to check a logged-in user's per-module permission (`init_permission_checker()`, `access_only_admin()`, etc.). Extending it only gets you **login-required**; it does *not* enforce any role/permission check unless the controller explicitly calls one of those methods.

I checked every custom module NCS has added, and only `Fleet`, `Attendance`, `Hr_payroll`, `Hr_appraisals`, and `Hr_memos` call `init_permission_checker(...)`. The rest call *only* `access_only_team_members()` — which means "any logged-in staff member, regardless of role" — or nothing at all:

| Controller | Auth check present | Granular permission check |
|---|---|---|
| `Internal_audit.php` | `access_only_team_members()` | **None** |
| `Legal_compliance.php` | (login only, via `Security_Controller`) | **None** |
| `Visitor_logbook.php` | (login only) | **None** |
| `Fixed_assets.php` | (login only) | **None** |
| `Procurement.php` | (login only) | **None** |
| `Suppliers.php` | (login only) | **None** |
| `Stores_inventory.php` | `access_only_team_members()` | **None** |
| `Facilities.php` | `access_only_team_members()` | **None** |

Concretely: any employee account — receptionist, driver, junior clerk, anyone — can currently open Internal Audit discrepancy records, Legal & Compliance data, the Fixed Asset register, Procurement records, and the Visitor Logbook, and (since there's no read/write split either) most likely edit and delete them too, because nothing in those controllers distinguishes an "auditor" from a "receptionist." This is a straightforward CWE-862 (Missing Authorization) affecting some of the most sensitive data in the system — audit findings, procurement records, legal compliance status.

This also directly violates the project's own rule in `CLAUDE.md`: *"Every new model added must conform to RBAC and its CRUD permission should reflect in the permission matrix for the system."* Confirmed by checking `app/Views/roles/permissions.php` — the role/permission matrix UI only has entries for `fleet_permission`. It has **no entries at all** for HR payroll, legal compliance, internal audit, visitor logbook, fixed assets, procurement, suppliers, or stores inventory. Those modules are functionally inaccessible to configure per-role, because the admin has no UI to restrict them even if the backend supported it.

**Fix:** for each module above, add a permission group (e.g. `internal_audit_permission`, `procurement_permission`) with the standard `all / specific / own / read_only / no` levels, wire `init_permission_checker()` + `access_only_allowed_members()` into the controller the same way `Fleet.php` and `Hr_payroll.php` already do it, and add the corresponding block to `app/Views/roles/permissions.php` so admins can actually assign it per role.

### 2.2 No RBAC-aware audit trail on the modules that most need one

Related to 2.1, and a second direct violation of `CLAUDE.md`'s first rule ("every important or major record ... stored in activity log linked to that user account"). None of the following controllers call `Activity_logs_model` or any equivalent logging at all:

`Hr_payroll.php`, `Fleet.php`, `Legal_compliance.php`, `Internal_audit.php`, `Visitor_logbook.php`, `Fixed_assets.php`, `Procurement.php`, `Attendance.php`, `Hr_appraisals.php`, `Hr_memos.php`.

This means payroll changes, fixed-asset adjustments, procurement approvals, and internal audit edits leave **no record of who did what and when**. For a payroll and asset-register system this is both a security gap (no forensic trail after a compromised account is used to tamper with records) and a governance/compliance gap for a public-sector body subject to PFMA/PPDA audit requirements (the system's own `Internal_audit` module generates statutory audit reports referencing PFMA 2015 — it would itself fail such an audit for lack of a trail).

Worth noting: `Docs/plans/Done/Done-security-rbac-audit-logs-plan.md` describes an "immutable audit log engine" with a dedicated `audit_logs` table, 2FA, and active-session management as **already implemented**. None of that exists in the actual schema (`database_schema.sql` has no `audit_logs` table, no 2FA columns anywhere) or the actual controllers. Treat everything under `Docs/plans/Done/` as a design intent, not a completion record — several "Done" plans describe a REST API / Vue.js architecture that doesn't match the server-rendered PHP application that actually exists. This mismatch is itself worth fixing procedurally: mark plans "Done" only once implementation is verified against the live code, or these documents will keep giving false confidence that controls (2FA, audit logging, session management) exist when they don't.

**Fix:** add `Activity_logs_model->ci_save(...)` calls (following the pattern already used in `Clients.php`, `Invoices.php`, etc.) to every create/update/delete action in the modules listed above, tied to `$this->login_user->id`.

### 2.3 No account lockout / brute-force protection on login, and an unenforced-by-default CAPTCHA

`app/Models/Users_model.php::authenticate()` has no rate limiting, no failed-attempt counter, and no delay. `app/Controllers/Signin.php` only invokes reCAPTCHA if the admin has configured `re_captcha_secret_key` in settings — which is not a default, so out of the box the login form has **zero brute-force protection**. Combined with the weak default password policy (§2.5) and the leaked credential list (§1.1), this materially increases account-takeover risk.

**Fix:** add a failed-login counter per account/IP (e.g. lock for 15 minutes after 5 failures within 10 minutes, using the existing `ncs_` prefixed tables or a new one), and make CAPTCHA mandatory by default rather than opt-in.

### 2.4 Legacy unsalted MD5 password fallback

`app/Models/Users_model.php:43`:

```php
if ($user_info->password && (strlen($user_info->password) === 60 && password_verify($password, $user_info->password)) || $user_info->password === md5($password)) {
```

Any account whose stored hash is 32 characters (MD5) instead of 60 (bcrypt) authenticates via a straight `===` comparison against `md5($password)`. MD5 is unsalted and crackable in bulk via rainbow tables or GPU brute force in seconds. If any account in the live `users` table still has an MD5 hash (common after a CRM import/migration), that account is one database leak away from full compromise, and there is currently no migration path forcing those accounts onto bcrypt.

**Fix:** on any successful MD5-based login, immediately re-hash the password with `password_hash()` and update the record (a standard "hash upgrade on login" pattern), and run a one-off audit of the `users` table now to see how many accounts are still on MD5.

### 2.5 No password strength policy

`Signup.php:77` validates the password field with the single rule `"required"` — no minimum length, no complexity requirement. The same appears to be true for staff/team member password fields (no length/strength rule found in `Team.php` or `Clients.php`). A user can set their password to `a`. Combined with §2.3 (no lockout) this makes credential-stuffing/brute-force trivial for any account with a weak password.

**Fix:** enforce a minimum length (12+) and basic complexity via CodeIgniter's validation rules (`min_length[12]`, plus a custom rule for character classes), applied consistently everywhere a password is set or changed.

### 2.6 Unauthenticated file-upload endpoint with weak, blacklist-based file-type filtering

`app/Controllers/Uploader.php` extends `App_Controller`, **not** `Security_Controller`, and its `upload_file()` action performs no login check before calling `upload_file_to_temp()`. Because routing in this app is fully dynamic (see §2.7), `uploader/upload_file` is reachable by anyone, logged in or not.

The validation in `is_valid_file_to_upload()` (`app/Helpers/app_files_helper.php:641`) is a **blacklist**, not a whitelist:

```php
$disallowed_extensions = array('php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'inc');
```

It does not block `.pht`, `.phar`, `.phtml5`, `.cgi`, `.pl`, `.shtml`, `.svg` (SVG can carry embedded `<script>` for stored XSS if ever displayed inline), or double-extension tricks — it only checks the final `pathinfo()` extension against that short list, and otherwise falls through to whatever `accepted_file_formats` is configured to in settings. There is also no MIME/magic-byte verification of the actual file contents — a renamed executable with an allowed extension sails through.

On top of that, `upload_file_to_temp()` builds the destination path directly from the attacker-supplied `$_FILES['file']['name']` (`$target_file = $target_path . $file_name;`) with no `basename()`/sanitization, which is a path-traversal risk if the upload is crafted outside a normal browser form (raw multipart POST with `name` containing `../`).

**Fix:**
1. Require authentication on `Uploader::upload_file()` (and everything else in that controller) the same way the rest of the app does.
2. Switch to a strict allow-list of extensions (and validate the real MIME type via `finfo`, not just the extension).
3. Sanitize the destination filename with `basename()` and regenerate it server-side (e.g. a UUID) rather than trusting client input for the path.

### 2.7 Every public controller method is auto-exposed as an HTTP endpoint

`app/Config/Routes.php` builds routes dynamically by scanning `app/Controllers/*.php` and registering **every** controller with a wildcard GET+POST route to any method name (`$routes->get(strtolower($controller).'/(:any)', "$controller::$1")`). This is convenient, but it means any `public function` on any controller — including ones that were only meant to be called internally from another method, or that a developer forgot were `public` instead of `private`/`protected` — is a live, unauthenticated-by-default (unless the base `__construct()` gates it) HTTP endpoint. This is the underlying reason §2.6 is exploitable, and it's a standing risk for every future controller added to the system: a developer adding a helper `public function` for internal reuse silently creates a new attack surface unless they remember it will be routable.

**Fix:** either switch to explicit route declarations for anything that should be public, or, at minimum, enforce (via a filter or a code convention/lint rule) that every controller method intended to be internal is `protected`/`private`.

### 2.8 Stripe webhooks trust a static URL token instead of verifying Stripe's signature

`app/Controllers/Webhooks_listener.php` (`stripe_payment`, `stripe_subscription`) authenticates incoming webhook calls purely by comparing a static per-installation key embedded in the URL against a stored setting, using loose `==`:

```php
$settings_key = get_setting("webhook_listener_link_of_stripe_subscription");
if ($settings_key && $settings_key == $key && $payloads) { return true; }
```

This is not how Stripe webhooks are supposed to be verified — the standard, correct approach is `\Stripe\Webhook::constructEvent()` with the webhook signing secret against the `Stripe-Signature` header, which cryptographically proves the payload came from Stripe and wasn't tampered with in transit. A URL-embedded token can leak through browser history, proxy/server access logs, or a Referer header, and never expires or rotates.

Worse, `subscription_payment_succeeded()` records the payment **amount and transaction ID straight from the POST body** (`$payloads_data->amount_paid`, `$payloads_data->payment_intent`) without re-verifying either against Stripe's API — unlike the sibling `stripe_payment` flow, which at least re-fetches the payment intent from Stripe before trusting it. Anyone who obtains the static subscription-webhook key can forge a payload marking an arbitrary subscription's invoice as paid for an arbitrary amount, with no cryptographic check preventing it.

**Fix:** implement proper `Stripe-Signature` verification via the Stripe SDK for both webhook endpoints, and make `subscription_payment_succeeded()` re-verify the amount/status against Stripe's API the same way `_invoice_payment_succeeded()` already does, rather than trusting the webhook body.

---

## 3. MEDIUM

### 3.1 CSRF filter is defined but not enabled anywhere

`app/Config/Filters.php` registers a `csrf` filter alias but it's commented out in both `globals.before` and everywhere else:

```php
'before' => [
    // 'honeypot',
    // 'csrf',
    // 'invalidchars',
],
```

`app/Config/Security.php` is configured (cookie-based CSRF, `ncs_csrf_token`/`ncs_csrf_cookie`), but configuring it has no effect unless the filter actually runs on requests. Unless CSRF tokens are being checked manually somewhere I didn't find (I searched and found no manual `$this->request->getPost($csrf_name)` verification pattern), **every state-changing POST endpoint in the app — invoices, payroll, user management, role changes — currently has no CSRF protection**, meaning a malicious external page can trigger state changes in an authenticated user's session just by getting them to load it (classic CSRF).

**Fix:** enable the `csrf` filter globally (`'before' => ['csrf']`), and exempt only the specific endpoints that genuinely need to be CSRF-exempt (webhooks, the CORS-enabled lead-collection endpoint), rather than the current all-off-by-default posture.

### 3.2 Honeypot and invalid-character filters also disabled

Same block as above — `honeypot` (spam protection on public forms like `Collect_leads`) and `invalidchars` (rejects malformed/invalid UTF-8 byte sequences often used to smuggle payloads past filters) are both defined but commented out globally. There's no clear reason visible in the codebase for leaving them off.

**Fix:** enable `invalidchars` globally; enable `honeypot` at least on the public lead-collection form.

### 3.3 Reliance on a blocklist XSS filter, with only ~1% of views using output escaping

Input sanitization goes through `App\Libraries\Clean_data::xss_clean()` (a ported CodeIgniter 3-era blocklist filter — it strips known-bad strings like `javascript:`, `document.cookie`, etc.), invoked via the `clean_data()` helper, but only where a controller explicitly calls it (44 files do; many more save user-supplied text without it). Blocklist filters are inherently bypassable — CI3's `xss_clean` has a long public history of bypasses.

On the output side, of **1,055** view files, only **10** use CodeIgniter's `esc()` output-escaping helper. The rest `echo` PHP variables into HTML directly. This isn't automatically exploitable everywhere (a lot of that data is numeric or comes from trusted internal fields), but it means the app has no systematic, defense-in-depth protection against stored XSS — it depends entirely on every developer remembering to sanitize every free-text field on the way in, across 100+ controllers and growing.

**Fix:** treat output escaping as the primary defense (wrap user-controlled `echo`s in views with `esc()`), keep input sanitization as a secondary layer, and consider replacing the legacy blocklist filter with a maintained library (e.g. HTMLPurifier) for any field that legitimately needs to store limited HTML (rich text descriptions, etc.).

### 3.4 PHP native `unserialize()` used on data that partly originates from user input

Multiple controllers `unserialize()` data pulled from the database (e.g. `Contract.php:158/222`, `Contracts.php:322-337`, `Dashboard.php:455/785/983/986`) where the underlying column was itself populated from user-submitted form data at some earlier point (contract metadata, signature blobs, dashboard widget layout). PHP's native `unserialize()` on attacker-influenceable strings is a known vector for PHP Object Injection (CWE-502) if any class reachable by autoloading has an exploitable `__wakeup`/`__destruct`/`__toString`. I did not attempt to build a working gadget chain (out of scope for a static review), but this pattern is a standing risk that's easy to eliminate.

**Fix:** migrate these fields from PHP `serialize()`/`unserialize()` to `json_encode()`/`json_decode()`, which cannot be used to instantiate arbitrary objects. This is a mostly mechanical refactor.

### 3.5 Cookies not marked `Secure`

`app/Config/Cookie.php:57` — `public bool $secure = false;`. The app does force HTTPS globally via the `forcehttps` required filter, but leaving the cookie's `Secure` flag off is an inconsistent, unnecessary weakening: if HTTPS enforcement is ever bypassed (misconfigured reverse proxy, direct LAN access on an intranet, a future config change), the session cookie could be sent in the clear.

**Fix:** set `$secure = true` now that HTTPS is enforced globally.

### 3.6 No 2FA / MFA anywhere in the system

Confirmed by searching for any TOTP/2FA/OTP column or logic across controllers, models, and the database schema — none exists, despite `Docs/plans/Done/Done-security-rbac-audit-logs-plan.md` describing it as implemented (see §2.2). For a system holding payroll, procurement, and government-audit data, admin and finance-role accounts in particular should have MFA available.

**Fix:** implement TOTP-based 2FA (e.g. via a library like `spomky-labs/otphp`), at minimum opt-in for admin/finance/HR roles, ideally mandatory for them.

### 3.7 `install/` directory shipped in production with only a fragile, path-dependent block

`install/do_install.php` and `install/index.php` remain in the deployed codebase. They do correctly refuse to reinstall (checking that `index.php` contains `$app_state = "installed"` and that `Database.php` no longer contains the placeholder `enter_hostname`), so a full reinstall isn't currently possible — but unlike `app/`, `system/`, and `writable/`, which each have their own `Require all denied` `.htaccess`, `install/` has **no directory-level `.htaccess` of its own**. Its only protection is the root `.htaccess`'s `RedirectMatch`, which is hardcoded to a specific URL prefix (see §3.8) and will not apply if the app is ever deployed at the domain root instead of a `/ncsintranet/` sub-path — a very plausible production layout for an intranet on its own subdomain.

**Fix:** delete the `install/` directory entirely once a production database exists (standard practice for CodeIgniter-based CRMs), or at minimum add `install/.htaccess` with `Require all denied` to match the pattern already used elsewhere in the app.

### 3.8 Root `.htaccess` protection is hardcoded to a `/ncsintranet/` path prefix

```
RedirectMatch 404 (?i)^/ncsintranet/(app|system|writable|install|Docs|documentation|updates)/
```

This rule (in the untracked root `.htaccess`) only blocks direct access to those directories if the app is served from a `/ncsintranet/` URL prefix — which is the local Laragon dev convention (`www/ncsintranet` → `localhost/ncsintranet/`), not necessarily the production layout. In production, this specific rule would silently do nothing, and `Docs/` (which currently contains the plaintext credentials file from §1.1) would only be protected by the fact that it isn't blocked by extension-based rules either — `Docs/Credentials.csv` *is* covered by the `\.(env|sql|md|py|log)$` `FilesMatch` block only because it's `.csv`... wait, `.csv` is not in that list, so it is **not** blocked by the extension rule either. The only thing standing between `Docs/Credentials.csv` and the public internet in a production deployment without the `/ncsintranet/` prefix is nothing.

(Note: `app/`, `system/`, and `writable/` are separately safe regardless of this issue, because they each carry their own directory-level `.htaccess` — see §3.7. `Docs/` and `documentation/` have no such per-directory protection.)

**Fix:** add `.csv` to the blocked-extensions `FilesMatch`, add dedicated `.htaccess` (`Require all denied`) files inside `Docs/`, `documentation/`, and `install/` so protection doesn't depend on the deployment path at all, and — regardless of any of this — get the credentials file out of that directory per §1.1.

### 3.9 Whole application lives in the web root; there is no `public/` document-root separation

There is no `public/` folder — `index.php` sits at the project root alongside `app/`, `system/`, `.git/`, `install/`, and `Docs/`. This is a deviation from CodeIgniter 4's recommended layout, where only `public/` is the web server's document root and everything else is physically outside it. The current layout means **every layer of protection is `.htaccess`-based and therefore configuration-dependent** — a switch to nginx (which doesn't read `.htaccess` at all), a misconfigured `AllowOverride None`, or the path-prefix issue in §3.8 each independently expose the entire codebase, including `.git/` (source history, possibly still containing the credentials from §1.1 even after a future `git rm`) and `database_schema.sql`.

**Fix:** this is a larger restructuring, but worth prioritizing: move the actual document root to a `public/` directory containing only `index.php` and static assets, with everything else (`app/`, `system/`, `writable/`, `Docs/`, `.git/`) outside the web server's serving root entirely. This removes an entire class of misconfiguration risk rather than trying to enumerate and block every sensitive path.

---

## 4. LOW

- **`app/Config/Security.php:26`** — `$tokenRandomize = false`. Turning this on adds a small amount of hardening against BREACH-style compression side-channel attacks on the CSRF token; low cost, low but real benefit — enable once CSRF enforcement (§3.1) is turned on.
- **Bitbucket/GitHub webhook key comparison** (`Webhooks_listener.php`) uses `==` instead of `hash_equals()` for the shared-secret check. Low practical risk (timing differences on a short string over the network are hard to exploit), but `hash_equals()` costs nothing to add and removes the theoretical issue.
- **`Cron.php`** has no secret-key/IP-restriction gate at all, relying purely on the `minimum_cron_interval_seconds` throttle to make repeated triggering pointless. Fine for its stated purpose, but if the interval setting is ever misconfigured to something very short, this becomes an easy way for an outsider to force cron-driven side effects (e.g. repeated recurring-invoice generation) at will. Consider adding a shared-secret query parameter, matching the pattern the app already uses for webhooks.
- **`_client_can_login()` in `Users_model.php`** builds a raw SQL string with `$user_info->client_id` interpolated directly rather than bound as a parameter. Not currently exploitable (the value comes from the database, not directly from request input), but it's an easy habit to carry forward into a genuinely exploitable spot; prefer bound parameters (`$this->db->query($sql, [$user_info->client_id])`) everywhere, even when the immediate risk looks low.

---

## 5. Non-security bugs

- **Incomplete PostgreSQL migration cleanup, still in progress in the working tree.** The uncommitted changes to `Clients_model.php`, `Expenses_model.php`, `Invoice_payments_model.php`, `Invoices_model.php`, and `Projects_model.php` are all fixes for PostgreSQL's strict `GROUP BY` requirement (adding the other selected columns to `GROUP BY`, which MySQL didn't require but Postgres does). These look correct as far as they go, but they confirm the MySQL→PostgreSQL migration was done incrementally and by hand; there is a real chance other, not-yet-triggered reporting queries elsewhere in the ~110 controllers still have the same class of bug and will only surface when that specific report is run. A `date_format`/`group_concat` compatibility shim has already been added at the database level (visible in `database_schema.sql`), which is a good sign the migration was done carefully — but I'd recommend a deliberate pass that actually executes every report/chart endpoint against the Postgres database once, rather than discovering the remaining ones in production one at a time.
- **Password verification's fallback branch** (§2.4) is a security bug but is also, mechanically, a **latent correctness bug**: nothing in the codebase currently upgrades an MD5 hash to bcrypt on successful login, so any legacy account is permanently stuck on the weak scheme until someone manually resets it.
- **`Uploader::upload_file_to_temp()`** (`app_files_helper.php:194`) calls `die('Failed to create file folders.')` and `die("Invalid file")` directly rather than returning a JSON error response like the rest of the API-style endpoints in this controller. This breaks the frontend's expectation of always getting back `{"success": false, "message": ...}` and will show a raw text string in the UI instead of a normal error message.
- **No maximum password length enforced** anywhere passwords are validated (§2.5 covers the missing minimum). Bcrypt silently truncates at 72 bytes; without an explicit max-length rule, a user who sets a very long passphrase may not get the protection they expect (their password may collide with any other input sharing the same first 72 bytes), which is a subtle but real correctness issue on top of the DoS-adjacent angle.

---

## 6. Business-logic / compliance gaps specific to `CLAUDE.md`'s rules

The project's own `CLAUDE.md` states two rules. Auditing the codebase against them directly:

1. **"Make sure for every important or major record it is stored in activity log linked to that user account."** — Violated across `Hr_payroll`, `Fleet`, `Legal_compliance`, `Internal_audit`, `Visitor_logbook`, `Fixed_assets`, `Procurement`, `Attendance`, `Hr_appraisals`, and `Hr_memos` (§2.2). None of these controllers write to `Activity_logs_model`.
2. **"Every new model added must conform to RBAC and its CRUD permission should reflect in the permission matrix for the system."** — Violated across the same set of modules plus `Suppliers` and `Stores_inventory` (§2.1). Only `Fleet`'s permission is represented in `app/Views/roles/permissions.php`; the rest have no way for an admin to scope access per role at all, and several of the controllers don't call any granular permission check in the backend either.

Given both rules are violated by the exact same list of modules — essentially everything NCS added beyond the base CRM — this reads less like isolated oversights and more like the RBAC/audit-log wiring step was consistently skipped when each new module was built. Fixing §2.1 and §2.2 together, module by module, following the pattern `Fleet.php` and `Hr_payroll.php` already establish, resolves both compliance rules at once.

---

## 7. Modules and capabilities still missing for a "fully functional" system

Beyond fixing what exists, the following are gaps in functional completeness, based on what's referenced (in settings, dashboards, or the project's own planning docs) but not actually implemented, or implemented only partially:

- **Two-factor authentication and active-session management** — planned in `Docs/plans/Done/Done-security-rbac-audit-logs-plan.md` and described there as complete; not present anywhere in the actual code or schema (§3.6). Needed given the system holds payroll and procurement data for a public-sector body.
- **Centralized, queryable audit/audit-log module** for the custom departments — the base CRM's `ncs_activity_logs` table exists and is used by the original modules (projects, tasks, invoices, etc.), but there is no equivalent trail for the modules listed in §2.2, and no admin-facing "who changed what, when" screen scoped to those modules the way `Internal_audit`'s own "Audit Trail & Activity Log" submenu implies should exist.
- **Account lockout / brute-force protection and mandatory CAPTCHA** (§2.3) — currently opt-in, should be a baseline control.
- **Password policy enforcement** (§2.5) at signup, staff creation, and password reset.
- **A completed permission matrix UI** covering every custom module (§2.1) — right now an admin configuring roles has no way to grant "Internal Auditor" access to the audit module without also granting them everything else a staff account can see, because there's no scoping control for it at all.
- **Verification that the MySQL→PostgreSQL migration is fully complete** across all report/chart endpoints (§5), not just the five models currently mid-fix in the working tree.
- **A documentation-integrity process** — several plans under `Docs/plans/Done/` (the security/RBAC/audit-log plan in particular) describe a materially different architecture (REST API, Vue.js frontend, UUID keys) than the CodeIgniter/PHP server-rendered app that actually exists, and claim features as delivered that aren't in the code. Until this is reconciled, "Done" in that folder can't be trusted as a signal of what's actually shipped, which will keep causing exactly this kind of gap (features assumed present that aren't) as the system grows.
- **From `Sketchpad.md` (existing outstanding note, not something this audit is closing out):** the team-member profile file-upload enhancement (adding CV/ID/Passport/Certificate file categories, restricted to super admin, other admins, and HR) is still an open item and should be scoped with the same RBAC pattern recommended in §2.1 when it's built, so it doesn't join the list of modules missing granular permissions.

---

## 8. Suggested remediation order

1. **Today:** rotate every credential in `Docs/Credentials.csv` and the database password in `Database.php`; strip both from git history. (§1.1, §1.2)
2. **This week:** enable the CSRF filter (§3.1); fix the unauthenticated upload endpoint and tighten file-type validation (§2.6); add authorization checks to the eight modules identified in §2.1; add activity logging to the ten modules identified in §2.2.
3. **This month:** enforce a password policy and login lockout/CAPTCHA (§2.3, §2.5); upgrade legacy MD5 hashes on login (§2.4); fix Stripe webhook verification (§2.8); move secrets to environment variables (§1.2); add `.htaccess` protection to `install/`, `Docs/`, `documentation/` and remove `install/` if no longer needed (§3.7, §3.8).
4. **Next quarter:** move to a proper `public/` document root (§3.9); replace `serialize()`/`unserialize()` with JSON where used on user-influenced data (§3.4); adopt output escaping (`esc()`) as standard practice in views (§3.3); build 2FA for privileged roles (§3.6); build out the full permission-matrix UI for every module (§2.1); reconcile the `Docs/plans/Done/` folder against actual shipped code.
