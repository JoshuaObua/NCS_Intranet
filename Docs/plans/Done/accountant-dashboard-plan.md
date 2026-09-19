# NCS Accountant & Accounting Department Dashboard Implementation Plan

> **Verification update — 2026-09-08:** Baseline data parity was verified locally, but full workflow replacement is **not complete**. Earlier completion claims below are superseded by the [verification findings and acceptance blockers](../verification/asset-register-2026-09-08/BUG_REPORT.md). FB cost and adjusted valuation are distinct; the source hash-cell exception remains unresolved.

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/accountant-dashboard-plan.md`  
> **Role:** Chief Accountant / Senior Accountant / Asset Accountant  
> **Department:** Finance & Accounts Department - National Council of Sports (NCS)  
> **Authority Scope:** Financial Ledgers, Budget Execution, Fixed Asset Register (297 Items / UGX 32,175,914,535.00 Valuation), Federation Grants, Form 5 Vote Clearance  
> **Baseline Asset Register:** `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (100% Data & Taxonomy Integration Completed)  
> **Universal Scope:** My Activities, Direct Messages, Real-Time Notifications, Profile, Security Settings, and Personal Leave Application & Approval Portal  
> **Compliance Standards:** Uganda Public Finance Management Act (PFMA 2015), Treasury Instructions (2017), IPSAS Accounting Standards (IPSAS 17 - Property, Plant & Equipment)  

---

## 1. Executive Role Overview & Mission

The **Accounting Department** is responsible for statutory financial management, government subvention accounting, budget execution, sports federation disbursements, non-tax revenue (NTR) collection, and fixed asset accounting.

This plan details the **Accountant Command Workspace** inside `NCS_Intranet`. All entry interfaces created by accountants stream real-time updates to financial ledgers, update live KPI cards, and sync automatically to the Auditor and General Secretary (GS) Executive Command views.

---

## 2. Key Modules & User Interface Specifications

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Accountant Financial & Ledger Command Workspace  [➕ New Entry] [📥 Import Excel] |
+-----------------------------------------------------------------------------------+
| [FINANCIAL & ASSET KPI SUMMARY CARDS]                                             |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Annual Subvention  | | Fixed Asset NBV    | | Federation Grants | | Vote Commitment   | |
| | UGX 18.0B / 25.0B  | | UGX 32.18B (297)   | | UGX 4.2B Disbursed| | 74.2% Committed   | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [ACCOUNTANT WORKSPACE: (1) General Ledger | (2) Fixed Assets | (3) Grants | (4) Vote-Heads] |
| +-------------------------------------------------------------------------------+ |
| | [SEARCH & FILTER BAR: Category: All v | Status: Posted v | Search voucher/code...] | |
| | ----------------------------------------------------------------------------- | |
| | Voucher ID | Account / Vote    | Description                 | Debit (UGX)   | Credit (UGX) | |
| | V-2026-081 | 221002 Workshop   | FUFA Africa Cup Prep Grant  |  150,000,000  |           -  | |
| | FA-2026-004| 311101 Asset Reg  | M1007058 Revaluation Post   |   15,000,000  |           -  | |
| | V-2026-084 | 227001 Travel     | Engineering Site Supervision|    4,500,000  |           -  | |
| | [Post Journal Entry] [Reconcile Bank] [Verify Form 5 Vote] [Run Depreciation] | |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Real-Time Ledger Feed & Pending Commitments   | | Universal Personal Suite       | |
| | - [FORM 5] Eng Dept Requisition UGX 45M (Pass)| | - [LEAVE] Annual Request (Pass) | |
| | - [DEPRECIATION] July 2026 Run Completed      | | - [ACTIVITIES] 14 Entries Today| |
| | - [NTR] Lugogo Hostel Booking UGX 2.5M Recvd  | | - [MESSAGES] 2 Unread Memos   | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

### Key Workstation Tabs & Entry Interfaces

1. **General Ledger & Journal Entry Manager:**
   - Double-entry posting interface for income, subventions, operational expenses, and vote-head commitments.
   - Bank reconciliation engine linking system vouchers with bank statements.

2. **Fixed Asset & Inventory Master Management (`FixedAssetsView.vue` - UGX 32.18B Scope):**
   - 100% Data Parity supporting all 11 worksheets in `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (297 assets across Land, Buildings, Vehicles, ICT, Electrical Machinery, Furniture).
   - Revaluation Posting interface (`FB_COST` to `ADJUSTED COST`) with transaction audit trail logging in `asset_transaction_logs`.
   - IPSAS 17 Depreciation Scheduler (Automated monthly straight-line calculation computing accumulated depreciation & Net Book Value).
   - Dynamic Multi-Dimensional Pivot & Analytics Engine replacing static Excel pivot tables.

3. **Sports Federation Grants & Subvention Manager:**
   - Disbursement tracker for 50+ national sports associations.
   - Accountabilities verification module before next quarter funds release.

4. **PPDA Form 5 Vote-Head Financial Clearance:**
   - Financial vote-head budget availability check for procurement requests submitted by user departments before submission to Procurement.

---

## 3. Universal Shared Personal Workplace Suite (For All Accounting Staff)

In addition to specialized financial tools, every user in the Accounting Department receives:

1. **Personal Leave Application & Status Portal (`LeaveApplyView.vue`):**
   - Submit leave applications (Annual, Sick, Compassionate, Study).
   - Real-time approval tracking: `Submitted` $\rightarrow$ `CFO Recommendation` $\rightarrow$ `HR Verification` $\rightarrow$ `GS Approval`.
   - Personal leave balance summary (Allocated, Taken, Remaining) and Finance Department duty roster.
2. **My Activities Audit Stream (`MyActivitiesView.vue`):**
   - Personal log tracking journal entries posted, revaluations submitted, and vote clearances executed.
3. **Direct Messages & Inter-Office Memos:**
   - Internal chat drawer and E-Memo receiver for communicating with HODs, Procurement, and HR.
4. **Notifications Center (`🔔`):**
   - Real-time alerts for Form 5 vote clearance requests, leave approval updates, and GS broadcasts.
5. **My Profile Management (`ProfileView.vue`):**
   - Bio data, staff ID, digital signature upload for financial voucher sign-offs, avatar upload.
6. **Account & Security Settings (`SecuritySettingsView.vue`):**
   - Password management, 2FA TOTP setup, and active login session termination.

---

## 4. Database Schema & Technical Architecture

### Core Table: `fixed_assets` & `financial_journals`
```sql
CREATE TABLE financial_journals (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    voucher_number VARCHAR(64) UNIQUE NOT NULL,
    posting_date DATE NOT NULL DEFAULT CURRENT_DATE,
    vote_head_code VARCHAR(32) NOT NULL,
    description TEXT NOT NULL,
    debit_amount NUMERIC(18,2) NOT NULL DEFAULT 0.00,
    credit_amount NUMERIC(18,2) NOT NULL DEFAULT 0.00,
    status VARCHAR(32) NOT NULL DEFAULT 'POSTED', -- DRAFT, POSTED, REVERSED
    created_by UUID NOT NULL REFERENCES users(id),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 5. REST API Mapping

| Endpoint | Method | Scope | Description | Status |
| :--- | :--- | :--- | :--- | :--- |
| `/api/v1/assets` | GET | Accounting | Query 297 fixed assets, filter by category/search | ✅ Implemented |
| `/api/v1/assets/summary` | GET | Accounting | Portfolio KPI summary & dynamic pivot aggregation | ✅ Implemented |
| `/api/v1/assets/revalue` | POST | Accounting | Revalue asset (`FB_COST` to `ADJUSTED COST`) with log | ✅ Implemented |
| `/api/v1/assets/depreciate` | POST | Accounting | Run monthly IPSAS 17 Straight-Line depreciation | ✅ Implemented |
| `/api/v1/finance/ledger` | GET | Accounting | List general ledger entries with pagination & vote-head filters | ✅ Implemented |
| `/api/v1/user/leave/apply` | POST | Accountant | Accountant personal leave application submittal | ✅ Implemented |
| `/api/v1/user/activities` | GET | Self Only | Personal accounting activity log query | ✅ Implemented |

---

## 6. Implementation Verification Roadmap

- [x] Integrate 297 baseline fixed asset records from `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (UGX 32.18B Valuation).
- [x] Create Go backend handlers for asset master & revaluations (`backend/internal/handlers/fixed_asset_handler.go`).
- [x] Build Vue frontend view `frontend/src/views/FixedAssetsView.vue` with 4 workspace tabs.
- [x] Verify personal leave application and universal workplace modules for all accounting staff.
