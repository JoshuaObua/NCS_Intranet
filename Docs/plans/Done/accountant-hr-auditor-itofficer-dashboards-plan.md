# NCS Master Multi-Dashboard Architecture Plan: Accountant, HR, Auditor & IT Officer

> **Target Document:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/accountant-hr-auditor-itofficer-dashboards-plan.md`  
> **System Scope:** National Council of Sports (NCS) Intranet - Core Operational Dashboards  
> **Target Roles:** Accountant & Accounting Department, HR Manager & HR Department, Internal Auditor & Audit Department, IT Officer / Systems Administrator  
> **Baseline Fixed Asset Register:** `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (297 Assets | UGX 32,175,914,535.00 Valuation | 100% Data & Taxonomy Integration Completed)  
> **Compliance Foundations:** Uganda Public Finance Management Act (PFMA 2015), Treasury Instructions (2017), Public Procurement and Disposal of Public Assets (PPDA) Act, IPSAS Accounting Standards (IPSAS 17), Computer Misuse Act (Uganda).

---

## 1. Executive Master Architecture & Inter-Role Data Pipeline

The four core operational dashboards interact through a centralized event-driven architecture, ensuring complete **Segregation of Duties (SoD)**, real-time auditing, automated workflow routing, and instant executive reporting to the General Secretary (GS / Accounting Officer).

```
+---------------------------------------------------------------------------------------------------+
|                        NCS ENTERPNCS CENTRAL DATA PIPELINE & GO EVENT BUS                       |
+---------------------------------------------------------------------------------------------------+
       |                                |                               |                           |
       v                                v                               v                           v
+-----------------------+   +-----------------------+   +-----------------------+   +-----------------------+
|  ACCOUNTING DASHBOARD |   |     HR DASHBOARD      |   |   AUDITOR DASHBOARD   |   |  IT OFFICER DASHBOARD |
| (Finance & Ledger)    |   | (Staff & Roster)      |   | (Audit & Verification)|   | (SysAdmin & Helpdesk) |
+-----------------------+   +-----------------------+   +-----------------------+   +-----------------------+
| - General Ledger      |   | - Staff Master File   |   | - Independent Spot-Check|  - Server & DB Health |
| - Asset Register (32B)|   | - Leave Queue         |   | - Discrepancy Manager |   | - Automated Backups   |
| - Federation Grants   |   | - Payroll & Statutory |   | - System Audit Logs   |   | - IT Helpdesk Queue   |
| - Vote-head Requisitions| - Staff Appraisals   |   | - Compliance Matrix   |   | - User RBAC & Security|
+-----------------------+   +-----------------------+   +-----------------------+   +-----------------------+
       |                                |                               |                           |
       +--------------------------------+-------------------------------+---------------------------+
                                        |
                                        v
                  +-----------------------------------------------------------+
                  | GENERAL SECRETARY (GS) EXECUTIVE MASTER REPORTING ENGINE  |
                  +-----------------------------------------------------------+
```

---

## 2. Role-by-Role Dashboard Specifications & User Interfaces

---

### 2.1 Accountant & Accounting Department Dashboard (`AccountantDashboard.vue`)

#### Scope & Authority
Manages all financial transactions, government subvention allocations, federation grant disbursements, Non-Tax Revenue (NTR) collections, budget vote-head commitments, fixed asset register adjustments (297 records totaling UGX 32.18B), depreciation postings, and store requisitions.

#### Key Modules & UI Specifications

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Accountant Financial & Ledger Command Workspace  [➕ New Entry] [📥 Import Excel] |
+-----------------------------------------------------------------------------------+
| [FINANCIAL & ASSET KPI CARDS]                                                     |
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
| | Real-Time Ledger Feed & Pending Commitments   | | Federation Accountabilities   | |
| | - [FORM 5] Eng Dept Requisition UGX 45M (Pass)| | Uganda Netball Fed: Pending  | |
| | - [DEPRECIATION] Monthly IPSAS 17 Completed   | | Uganda Athletics: Verified   | |
| | - [NTR] Lugogo Hostel Booking UGX 2.5M Recvd  | | FUFA: Q1 Report Cleared      | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

#### Core Accountant Capabilities:
1. **General Ledger & Journal Posting Engine:** Double-entry journal vouchers with vote-head validation against quarterly budget allocations.
2. **Fixed Asset & Inventory Master Management (`FixedAssetsView.vue`):** Bulk `.xlsx` upload of asset registers (supporting `FIXED ASSET REGISTER ADJUSTMENTS.xlsx`), asset revaluation (`FB_COST` to `ADJUSTED COST`), straight-line/reducing balance depreciation runs (IPSAS 17), and store consumables issue.
3. **Federation Grants & Subvention Disbursement Manager:** Tracking financial requisitions from 50+ national sports federations, verifying submitted accountabilities, and clearing disbursement vouchers.
4. **Form 5 Vote-Head Financial Clearance:** Checking budget availability for departmental PPDA Form 5 procurement requests before submitting to Procurement.

---

### 2.2 HR (Human Resources) Department Dashboard (`HRDashboard.vue`)

#### Scope & Authority
Controls staff master files, organizational structure, leave applications, daily attendance rosters, payroll processing, statutory benefits compliance (PAYE, NSSF, LST), performance appraisals, and staff training/disciplinary records.

#### Key Modules & UI Specifications

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Human Resources & Personnel Management Portal    [➕ Add Staff] [📋 Leave Queue] |
+-----------------------------------------------------------------------------------+
| [HR KEY PERFORMANCE INDICATORS]                                                   |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Active Staff Count | | On Leave Today     | | Monthly Payroll   | | Open Appraisals   | |
| | 128 Employees      | | 6 Staff Members    | | UGX 342.5 Million | | 14 Pending Review | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [HR WORKSPACE: (1) Staff Directory | (2) Leave Approvals | (3) Payroll | (4) Appraisals]  |
| +-------------------------------------------------------------------------------+ |
| | [LEAVE APPROVAL QUEUE & STAFF ROSTER]                                         | |
| | Employee Name    | Department      | Leave Type    | Dates           | Status   | |
| | Okello John      | Engineering     | Annual Leave  | Aug 01 - Aug 14 | Pending  | |
| | Namubiru Sarah   | Finance         | Sick Leave    | Jul 28 - Jul 30 | Approved | |
| | Musoke David     | IT / ICT        | Compassionate | Aug 05 - Aug 07 | Pending  | |
| | [ Approve Selected ] [ Reject with Reason ] [ View Staff File ] [ View Roster ] | |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Monthly Payroll & Statutory Compliance Hub    | | Staff Appraisal & Performance | |
| | Gross Salary Total  : UGX 342,500,000         | | Q2 Appraisal Completion: 88%  | |
| | PAYE Deduction      : UGX  85,625,000         | | Outstanding Reviews: 14      | |
| | NSSF (10% Employer) : UGX  34,250,000         | | Top Performing Dept: IT      | |
| | [ Generate Payroll Slips ] [ Export NSSF File]| | [ Launch Appraisal Cycle ]  | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

#### Core HR Capabilities:
1. **Employee Master Directory (`UsersView.vue`):** Comprehensive staff profiles, designation, contract terms, salary scale, national ID, emergency contacts, and qualifications.
2. **Leave Management Engine (`LeaveApplyView.vue`):** Automated leave balance tracking (Annual, Sick, Maternity/Paternity, Study, Compassionate) with multi-level approval workflows (HOD $\rightarrow$ HR $\rightarrow$ GS).
3. **Payroll & Statutory Deductions Calculator:** Monthly payroll generation, automatic computation of PAYE (URA tax bands), NSSF (5% employee + 10% employer), Local Service Tax (LST), and bank upload files.
4. **Appraisal & Capacity Building Tracking:** Annual performance review scores, staff training history, disciplinary logs, and career progression tracking.

---

### 2.3 Auditor / Internal Audit Dashboard (`AuditorDashboard.vue`)

#### Scope & Authority
Functions as an independent verification hub. Internal Auditors perform physical spot-checks, scan barcode/QR asset tags (297 assets, UGX 32.18B Valuation), review financial ledgers for vote-head overspend, flag discrepancies, audit statutory compliance (PFMA, PPDA, Treasury Instructions), and review immutable system audit logs without data alteration rights.

#### Key Modules & UI Specifications

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Internal Audit Command & Compliance Portal    [🔍 Spot-Check] [📋 Audit Report] |
+-----------------------------------------------------------------------------------+
| [AUDIT COMPLIANCE & RISK METRICS]                                                |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Audit Integrity    | | Physical Tag Count | | Open Discrepancies| | Risk Rating       | |
| | 100% Verified      | | 297 / 297 Tagged  | | 0 Flagged Issues  | | LOW (Clean Audit) | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [AUDITOR WORKSPACE: (1) Physical Spot-Check | (2) Discrepancy Manager | (3) System Audit] |
| +-------------------------------------------------------------------------------+ |
| | [PHYSICAL ASSET & STOCK SPOT-CHECK SCANNER]                                   | |
| | Scan Asset Tag: [ Input Barcode / QR Code: 166BLNG10                 ] [ Scan ] | |
| | System Match  : M1007058 - NCS BLOCK - OFFICE FLOOR-166-BLNG-10               | |
| | System Value  : UGX 298,000,000.00 | Location: NCS Lugogo Block Floor 2       | |
| | Audit Findings: [ Confirm Verified ] [ Flag Cost Mismatch ] [ Flag Missing ]    | |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Discrepancy & Exception Queue                 | | Statutory Compliance Engine  | |
| | - [FLAG] M1007058 FB_COST vs Adj Cost Mismatch| | PFMA Act 2015    : PASS      | |
| | - [FLAG] Travel Requisition > Vote Allocation | | PPDA Act 2014    : PASS      | |
| | [ Query Accountant ] [ Lock Asset Record ]    | | Treasury Inst 2017: PASS      | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

#### Core Auditor Capabilities:
1. **Physical Asset & Inventory QR Verification:** Mobile-friendly barcode/QR tag reader to verify asset existence, location, physical condition, and custodian against system records.
2. **Discrepancy & Impairment Management Queue:** Raising formal audit queries on value discrepancies, unvouched expenditures, or unverified physical assets, forcing accounting resolution.
3. **Statutory Compliance Audit Engine:** Automated compliance checking against PFMA 2015 (budget overspend), Treasury Instructions 2017 (vouchering), and PPDA Act (procurement threshold violations).
4. **Immutable System Audit Log Viewer:** Non-tamperable log viewer tracking all user logins, record creations, role changes, privilege escalations, and financial edits.

---

### 2.4 IT Officer / ICT Department Dashboard (`ITOfficerDashboard.vue`)

#### Scope & Authority
Oversees system health, database performance, automated backup routines, network security, user provisioning, Role-Based Access Control (RBAC), IT helpdesk ticket queues, and enterprise IT hardware assets.

#### Key Modules & UI Specifications

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | IT Infrastructure & Systems Command Center   [🔄 Backup Now] [➕ Add User] |
+-----------------------------------------------------------------------------------+
| [SYSTEM INFRASTRUCTURE HEALTH CARDS]                                             |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | System Uptime      | | Database Backup    | | Open IT Tickets   | | Active User Sessions| |
| | 99.98% (Online)    | | SUCCESS (02:00 AM)| | 3 Pending SLA   | | 42 Users Logged In| |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [IT WORKSPACE: (1) System Operations | (2) User RBAC | (3) IT Helpdesk | (4) Backups]    |
| +-------------------------------------------------------------------------------+ |
| | [ENTERPNCS IT HELPDESK & TICKET MANAGEMENT QUEUE]                            | |
| | Ticket ID  | User / Dept      | Category       | Issue Description   | SLA Status | |
| | T-2026-042 | Musoke (Eng)     | Printer Conn   | Kyocera Driver Error| In Progress| |
| | T-2026-045 | Akello (Finance) | Password Reset | Account Locked      | Resolved   | |
| | T-2026-048 | Admin (HR)       | Network Drop   | Switch Port #14 Down| High Priority|
| | [ Assign Ticket ] [ Update SLA ] [ Resolve & Close ] [ Escalated to HOD ]     | |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | System Backup & Disaster Recovery Log         | | Security & RBAC Access Matrix | |
| | PostgreSQL DB Backup: 42.8 MB (Encrypted S3)  | | Total System Accounts: 128   | |
| | Media Artifacts Sync: SUCCESS                 | | Active 2FA Users     : 112   | |
| | Next Scheduled Run  : Today 02:00 AM          | | Failed Login Attempts: 2     | |
| | [ Trigger Manual Backup ] [ Verify Restore ]  | | [ Manage Permissions ]      | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 3. Go Backend API Architecture & REST Mapping

| Endpoint | Method | Role | Description | Status |
| :--- | :--- | :--- | :--- | :--- |
| `/api/v1/assets` | GET | Accountant / Auditor | Query 297 fixed assets, filter by category/search | ✅ Implemented |
| `/api/v1/assets/summary` | GET | Accountant / Auditor | Portfolio KPI breakdown & dynamic pivot aggregation | ✅ Implemented |
| `/api/v1/assets/revalue` | POST | Accountant | Revalue asset (`FB_COST` to `ADJUSTED COST`) with log | ✅ Implemented |
| `/api/v1/assets/depreciate` | POST | Accountant | Run monthly IPSAS 17 Straight-Line depreciation | ✅ Implemented |
| `/api/v1/assets/verify` | POST | Auditor | Physical spot-check tag verification & logging | ✅ Implemented |
| `/api/v1/admin/dashboard` | GET | Accountant / Admin | System financial & operational stats | ✅ Implemented |
| `/api/v1/admin/audit-logs` | GET | Auditor / Admin | Immutable audit trails | ✅ Implemented |
| `/api/v1/admin/users` | GET/POST | HR / Admin | Staff directory & RBAC account management | ✅ Implemented |
