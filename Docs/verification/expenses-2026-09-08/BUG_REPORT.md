# Expense module changes and verification report

| Item | Resolution | Evidence |
| --- | --- | --- |
| No dedicated departmental expense workflow | Added register, entry and detail pages with date, reason, amount, payment details and department | Browser workflow and build |
| No centrally managed expense categories | Added admin-only create/edit/deactivate endpoints and category page; accountant writes return 403 | PostgreSQL integration and admin browser check |
| Recorder attribution could be supplied by a client | Server reads signed-in user ID/name; submitted forged attribution is ignored | Integration test stores Stella Akello despite forged form fields |
| Renames could obscure historical context | Saved category, department and recorder name snapshots | Integration test renames/deactivates category and confirms original record unchanged |
| Receipt and expense could be partially saved or publicly exposed | Atomic database transaction and authenticated binary download | Persistence, byte comparison, unauthorized download and failed-submission tests |
| Missing/inactive lookup values or invalid amounts/dates | Server validates active department/category, positive two-decimal amount and nonfuture date | Integration tests |
| Chief/asset accountant omitted from the shared accountant dashboard condition | Added both role variants and universal expense navigation | Role-by-role browser checks |
| Selectors had ambiguous accessible names | Added explicit category, department and payment-method names | Browser form completion |
| Rapid detail navigation could show an earlier response | Ignore stale detail responses | Source review; build |

No unresolved expense defect was observed in the completed checks. Browser tests use fixtures; production read-only verification is documented in the [deployment report](../expenses-vps-2026-09-08/README.md); expense writes were tested against isolated PostgreSQL. Existing fixed-asset findings in the separate asset-register bug report are outside this feature and remain applicable.
