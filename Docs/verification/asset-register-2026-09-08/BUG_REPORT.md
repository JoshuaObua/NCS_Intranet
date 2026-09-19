# Fixed asset register replacement verification — 8 September 2026

> Historical verification snapshot before the independent-page conversion and subsequent upstream integration. Dialog screenshots and their browser script describe the earlier UI. See the [current page workflow verification](../asset-pages-2026-09-08/README.md). The page conversion does not resolve the backend financial-control findings.

**Verdict: NOT a complete replacement of the workbook or the approved workflow plan.** Baseline asset data matches the source under the existing hash-cell convention. Navigation, pagination and action visibility have been improved and verified locally. Important workflow, authorization and accounting-control gaps remain open. This is a technical verification, not an accounting/compliance certification.

## Scope and evidence boundaries

Compared `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`, both fixed-asset/accountant plans, the asset API/repository/schema, the active dashboard/router/layout, and the GS appraisal integration. Followed `NCS_INTRANET_DEPLOYMENT.md` for project separation and preservation requirements.

Three different evidence types are supplied:

- **Source reconciliation:** actual workbook versus migration 034 and the local PostgreSQL database. Reproducible with `verify-workbook.py`.
- **Fixture UI checks:** Chrome desktop 1440×1000 and mobile 390×844, workbook-derived intercepted API responses, including an injected summary failure. Blue screenshot labels identify fixtures. These do not prove server writes or authorization.
- **Real local API checks:** actual seeded accountant login, built frontend preview on `127.0.0.1:4173`, Docker API on `127.0.0.1:9081`, PostgreSQL 16.15. Green screenshot labels identify these. Only login and read requests were allowed; asset mutations were blocked by the test harness.

**Remote deployment was not performed or verified.** `104.219.248.160:9081/healthz` timed out; `server1.eventspix.online` failed DNS resolution. The guide leaves the SSH port unspecified. No claim is made about remote `ncswebsite`, `aegis`, `monjaro`, or their databases.

## Data reconciliation

| Data sheet | Assets | FB cost, UGX | Adjusted cost, UGX |
|---|---:|---:|---:|
| CYCLES | 1 | 9,375,000 | 9,375,000 |
| ELECTRICAL MACHINERY | 11 | 107,394,446 | 107,394,446 |
| FURNITURE AND FITTINGS | 154 | 96,423,651 | 96,423,651 |
| LAND | 8 | 27,893,933,769 | 27,893,933,769 |
| LIGHT ICT HARDWARE | 86 | 59,834,250 | 59,834,250 |
| LIGHT VEHICLES | 3 | 508,963,880 | 508,963,880 |
| NON RESIDENTIAL BUILDINGS | 17 | 2,138,452,000 | 3,298,452,000 |
| OFFICE EQUIPMENT | 10 | 27,668,036 | 27,668,036 |
| OTHER ICT EQUIPMENT | 6 | 6,619,503 | 6,619,503 |
| RESIDENTIAL BUILDINGS | 1 | 167,250,000 | 167,250,000 |
| **Total** | **297** | **31,015,914,535** | **32,175,914,535** |

There are **10 data sheets and one pivot sheet**, not 11 independent asset datasets. Reconciliation compares interface line, book, asset number, tag, description, three category segments, units, both costs, service date and worksheet source. No differences, missing assets or extra assets were found in migration 034 or the local database, subject to the explicit source exception below. Local accumulated depreciation and transaction-log count were both zero.

**Source exception:** M1007059 / 166BLNG5 contains literal text `#############` in FB_COST. This is not an underlying numeric value hidden by Excel column width. Migration 034 and migration 078 use zero, retaining UGX 1.16 billion as adjusted cost. Reconciliation reproduces that convention; it does not establish that zero is the correct historical cost. An accountant must resolve the source amount before final acceptance. Migration 078 also overwrites the matching asset unconditionally and should be reviewed before application to a database with subsequent transactions.

**Stale workbook pivot:** its cached grand total is **UGX 4,098,812,766**, with land at **UGX 976,832,000**. Current source land is UGX 27,893,933,769, explaining the **UGX 26,917,101,769** difference. The 47 nonempty pivot rows include its header, category totals and grand total. The application shows 35 detailed category combinations; row-count differences alone do not establish missing assets. The application currently supplies a fixed three-category aggregation, rather than an arbitrary-dimension pivot editor.

See [workbook reconciliation](workbook-parity.json), [local database reconciliation](local-database-parity.json), and [reproduction script](verify-workbook.py).

## Changes completed in this review

| ID | Problem before | Change and verification |
|---|---|---|
| FIX-01 | Active `LayoutDefault.vue` had no asset navigation. The older `AppSidebar.vue` link was insufficient. | Added a distinct Finance & Accounting / Fixed Asset Register entry for accountant, finance_department, auditor, GS aliases and administrators. Actual accountant dashboard-to-register navigation passed. Other role names are source-reviewed, not authenticated end-to-end tested. |
| FIX-02 | Register fetched only 100 rows with no next page. Even Furniture's 154 rows could not all be browsed. | Added previous/next pagination with bounds and disabled states. Real API returned pages of 100, 100 and 97. Category/search reset the offset. |
| FIX-03 | Hardcoded 297/portfolio values appeared before a successful summary, and remained after failure. | Removed financial/count baseline fallbacks, display unavailable financial totals as an em dash, and removed the unsupported “complete statutory replacement” claim. Injected summary failure passed. Historical reference text is still explicitly labelled as a baseline. |
| FIX-04 | Adjust/Verify were off the visible right edge of the wide table. | Pinned the Actions column with an opaque background and separator; increased button height. Inspected desktop and mobile screenshots against real API data. The remaining columns can still be horizontally scrolled. |
| FIX-05 | Search icon overlapped entered text; search/category had no explicit accessible name. | Corrected scoped input padding and added accessible labels. Actual category-plus-asset-number search passed. |
| FIX-06 | Long asset header was constrained on narrow screens; notice timer survived unmount. | Header can wrap; notice timer is cleared on unmount. Mobile layout was inspected. |
| PRESERVED | A pre-existing local edit unwrapped API `data.data` responses for list, summary and depreciation. | Preserved that edit; successful actual list/summary rendering verifies these two paths. Depreciation posting was not executed. This edit was not authored by this review. |

No backend code, migrations, workbook data, compose files or runtime environment configuration were changed. New artifacts are this report, evidence, scripts and the changelog/plan verification notes. The frontend changes are in the workspace/build preview; they have **not** been rebuilt into the running frontend container or deployed to the VPS.

## Open bugs and missing planned functions

All items below are **OPEN**. Source-based findings identify concrete implementation gaps; mutation behavior has not been exercised against the preserved database.

| ID / priority | Finding and reproduction/evidence | Required improvement / acceptance condition |
|---|---|---|
| ASSET-01 / Critical | `backend/cmd/server/main.go` registers all `/assets` reads and writes under authentication without asset-role middleware. Handlers add no accounting/auditor/GS role checks. Frontend route requires authentication only, and all mutation controls are rendered. | Enforce read/write/verify privileges on the server, align UI controls, and test allowed and denied roles. Sidebar visibility alone is not authorization. |
| ASSET-02 / Critical | `RunDepreciation` ignores `period` and `userID`. Repeating a request can depreciate the same assets again; malformed JSON is ignored by the handler. | Validate month/request, persist unique period execution, reject duplicates and concurrent repeats, and test transaction rollback/idempotency. |
| ASSET-03 / High | Depreciation query ignores method and service date; summary calculation and update are separate operations, with no transaction logs. Newly created useful life 0 is forced to 5, so manually registered land can become depreciable. Seeded land currently has zero life and is excluded by the query. | Implement method/date eligibility, correct land handling, atomic posting and audited schedules; prove amounts/rounding in isolated database tests. |
| ASSET-04 / Critical | Revaluation immediately updates `fixed_assets`; no draft, auditor review or GS approval gate. The GS appraisal handler uses `asset_valuation_signoffs`, not this transaction workflow. | Connect a pending-adjustment approval workflow to the same ledger; prove only approved adjustments post and appear across accountant/auditor/GS views. |
| ASSET-05 / High | Value Adjustments tab is static explanatory text and never fetches logs. Screenshot 05 confirms this. | Render searchable transaction history with asset, old/new amounts, actor, time and approval. Detail API exists but is not exposed by this tab. |
| ASSET-06 / High | No workbook upload/import endpoint or Import Excel interface exists in the asset module. SQL seeding is not the planned accountant-operated importer. | Implement preview, header-based parsing, duplicates/invalid-row reporting, atomic import and audit records across the ten data sheets; handle pivot as summary. |
| ASSET-07 / High | Asset endpoints offer list/detail/create/revalue/depreciate/verify only; no master edit, transfer, disposal or write-off lifecycle. | Add validated workflows and visible controls, with GS approval where required and persisted history. |
| ASSET-08 / High | Create form omits interface-line input, asset book editing, depreciation method and other master fields; no read-only details view exposes the complete stored record. | Expose and validate the full source/master record without silently inventing financial defaults. Test decimal amounts and all category/service-date inputs. |
| ASSET-09 / High | Create accepts negative cost values server-side; zero adjusted cost is automatically replaced with FB cost. Validation/defaults can therefore change an intended zero valuation. | Distinguish omitted and explicit zero fields, validate money/units/dates/status/method and return structured field errors. |
| ASSET-10 / High | Verification accepts arbitrary status, ignores log-insert failures and updates outside a transaction. Notes may fail to persist while success is returned. Create/import also has no asset transaction log. | Validate statuses and atomically persist ledger/action history, preserving actor identity. Test failed logging rollback. |
| ASSET-11 / Medium | Summary category-query/scan errors are swallowed, allowing incomplete analytics with a successful response. UI fetches can race; failed filtered fetches retain older rows; notices expire after five seconds. | Propagate query errors, use request sequencing/cancellation, clearly mark stale/unavailable data and keep actionable errors visible. |
| ASSET-12 / High | Accountant has the generic dashboard rather than the complete financial workstation in the plan. The GS appraisal/report data source is separate from `fixed_assets`. No verified accountant-to-auditor-to-GS synchronization exists. | Integrate shared ledger-derived KPIs, history and approval queues across the actual routed dashboards and test role-specific flows. |
| ASSET-13 / Medium | No asset PDF/Excel export, board package, QR scanner or discrepancy-resolution queue was found in this module. Stock is a separate module, not the planned asset-workstation tab. | Implement the promised interfaces and end-to-end integration, or explicitly revise the approved scope. |
| ASSET-14 / High | Literal hash FB cost and stale pivot described above; plans use the FB and adjusted totals interchangeably and mark broader work complete without evidence. | Resolve source exception with Finance, distinguish totals, refresh/reconcile pivot, and replace completion claims with tested acceptance evidence. |
| ASSET-15 / Medium | Generic dashboard welcome text has poor contrast in light mode (screenshot 10). Mobile shared application panel consumes much of the initial screen; sidebar labels are clipped in collapsed mode. Asset dialogs lack dialog semantics, focus trap and keyboard dismissal. | Improve shared mobile navigation, prioritize task access, implement accessible dialogs and test keyboard/dark-mode/responsive behavior. Current screenshots do not certify accessibility. |
| ASSET-16 / Medium | New assets default to 2023-07-01; cost input step is 1000 despite source fractional/whole-shilling values. Handler errors include internal DB details and UI may show the nested error object. | Use deliberate service-date defaults, appropriate currency steps and readable structured errors. |
| DEPLOY-01 / Blocked | VPS health timeout, SSH hostname DNS failure and missing explicit SSH port. | Supply working SSH host/port/user; snapshot remote container IDs/health and database backup before any update, then perform remote acceptance. |

## Tests and screenshot proof

| Check | Result | Limit |
|---|---|---|
| Source/seed/local DB field reconciliation | PASS with disclosed hash-cell convention | Not remote DB parity; unknown FB cost remains unresolved. |
| `npm run build` | PASS | See [build log](frontend-build.log). |
| `go test ./...` | PASS | See [test log](backend-tests.log); repository has no tests, so this does not verify depreciation or persistence correctness. |
| Fixture UI assertions | 7 PASS | See [results](browser-results.json); no real asset writes. |
| Authenticated accountant / real local API | PASS | Login and GET responses HTTP 200, navigation, 297 rows, filter/search and 35 pivot groups; [results](local-api-browser-results.json). |
| Local container/asset preservation | PASS | Same IDs for all six observed containers; matching before/after asset export SHA-256; [results](preservation-results.json). |
| Remote service/DB verification | BLOCKED | No remote deployment or preservation claim. |
| Create/revalue/depreciate/verify persistence, RBAC, approvals | NOT TESTED end-to-end | Dialogs were inspected, not submitted; open critical findings preclude complete replacement acceptance. |

Real local data screenshots:

- [Accountant dashboard navigation](10-accountant-dashboard-local-api.png)
- [Asset register and portfolio cards](11-register-local-api.png)
- [Final page of the register](12-last-page-local-api.png)
- [Search, filter and distinct pinned actions](13-actions-local-api.png)
- [Live local pivot](14-pivot-local-api.png)
- [Mobile asset tools](15-mobile-tools-local-api.png)
- [Mobile pinned row actions](16-mobile-actions-local-api.png)

Fixture-only screenshots:

- [Register](01-register-desktop.png), [pagination](02-final-page.png)
- [New asset dialog](03-new-asset.png), [revaluation dialog](04-adjustment-dialog.png)
- [Missing adjustment log](05-adjustments-missing-log.png), [pivot](06-pivot.png)
- [Depreciation dialog](07-depreciation-dialog.png), [mobile](08-mobile-register.png), [summary failure](09-summary-failure.png)

## Deployment and preservation

The local Docker compose project is `ncsintranet`, labelled with this workspace's compose file. It exposes HTTP 9081, HTTPS 9444 and PostgreSQL 5436, consistent with the guide's separation. Local `/healthz` is OK, PostgreSQL accepts connections, and the location service responds OK (it also reports no geo database and fail-closed false, an existing configuration finding outside this asset review).

No containers were stopped, recreated, removed or rebuilt. No volumes or asset rows were changed. Login can create normal session/access-audit records; identical asset exports do not imply that all authentication tables are byte-for-byte unchanged. The observed local Docker engine did not contain `ncswebsite`, `aegis` or `monjaro`; their remote health cannot be inferred.

Before a remote update, obtain access and capture a source backup **and a database backup**, preserve `/opt/ncsintranet/.env` **and the existing `docker-compose.override.yml`**, retain the explicit `ncsintranet` project name and port mappings, and record other projects' container IDs and health. The guide's source-swap example copies `.env` but omits copying the override file it subsequently requires; preserve it explicitly. Do not use volume removal. Verify database/migration state before applying migration 078. The unresolved critical findings above are acceptance blockers even if the frontend builds successfully.

## Reproduce locally

From the repository root, with openpyxl available:

```bash
python3 Docs/verification/asset-register-2026-09-08/verify-workbook.py
```

The browser scripts require Chrome at `/usr/bin/google-chrome` and Playwright installed outside the repository at `/tmp/ncs-asset-verification`. Fixture checks use the Vite development server on 3001. Real API checks use the built preview on 4173 (an origin already allowed by the local backend), local Docker API on 9081 and the existing accountant credential from `Docs/Credentials.csv`. No passwords or tokens are stored in the evidence.

```bash
node Docs/verification/asset-register-2026-09-08/browser-check.cjs
node Docs/verification/asset-register-2026-09-08/local-api-browser-check.cjs
```

Do not remove the browser scripts' mutation guards when running against a preserved database. Full posting tests require an isolated test database and explicit expected balances, logs and role outcomes.
