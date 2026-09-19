# General Secretary Executive Master Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/general-secretary-dashboard-plan.md`  
> **Role:** General Secretary (GS) / Accounting Officer - National Council of Sports (NCS)  
> **Authority Level:** Organization-Wide Executive Control & Final Statutory Approval Authority  
> **Direct Executive Subordinates:** Assistant General Secretary - Technical (AGS-T), Assistant General Secretary - Administration (AGS-A), Head of Internal Audit, Legal Counsel  
> **Target Access Scope:** Unrestricted, real-time read, report query, statutory approval, and appraisal evaluation access across ALL NCS departments (Engineering, Finance, HR, IT, Technical/Sports, Procurement, Public Relations, Internal Audit, Legal, Stores, Facilities, Medical, Transport)  

---

## 1. Executive Role Overview & Master Interconnected Architecture

The **General Secretary (GS)** is the Chief Executive Officer and statutory Accounting Officer of the National Council of Sports (NCS). Under the National Sports Act (2023) and Public Finance Management Act (PFMA 2015), the General Secretary carries total organizational accountability for financial disbursements, asset management, human resources, sports federation governance, procurement approvals, infrastructure projects, and public communications.

The GS portal functions as an **Interconnected Executive Control & Statutory Appraisal Center** supported by two executive arms:
- **Assistant General Secretary - Technical (AGS-T):** Oversees all technical branches (Sports Federations, NSMIS, Athletes/Teams, Engineering, Infrastructure & Venues).
- **Assistant General Secretary - Administration (AGS-A):** Oversees all administrative branches (HR, Finance, PDU Procurement, PR, and IT Infrastructure).

```
+---------------------------------------------------------------------------------------------------+
|               GENERAL SECRETARY (GS) MASTER INTERCONNECTED DATA ENGINE                            |
+---------------------------------------------------------------------------------------------------+
                               |                                                 |
         +---------------------+---------------------+     +---------------------+---------------------+
         |                                           |     |                                           |
         v                                           v     v                                           v
+-----------------------------+ +-----------------------------+ +-----------------------------+ +-----------------------------+
| AGS - TECHNICAL (AGS-T)     | | AGS - ADMINISTRATION (AGS-A)| | INTERNAL AUDIT & LEGAL      | | FINANCE & FIXED ASSETS      |
| - 50+ Sports Federations    | | - Human Resources (128 Staff| | - Independent Risk Audits   | | - Fixed Asset Ledger (31.02B|
| - NSMIS & Athlete Licensing | | - PDU Form 5 Procurement    | | - Discrepancy & Fraud Flags | | - 11 Excel Category Classes |
| - Engineering Work Orders   | | - Budget Vote Execution     | | - Federation Arbitrations   | | - Revaluation Adjustments   |
| - Facility Match Readiness  | | - PR Releases & Media Pass  | | - Statutory Compliance Pack | | - Cash Flows & NTR Inflows  |
+-----------------------------+ +-----------------------------+ +-----------------------------+ +-----------------------------+
         |                                           |     |                                           |
         +-------------------------------------------+-----+-------------------------------------------+
                                                     |
                                                     v
                         +-------------------------------------------------------+
                         | GS EXECUTIVE DASHBOARD & STATUTORY CONTROL CENTER     |
                         | - Statutory PPDA Form 5 Final Approvals               |
                         | - CapEx Escalations > UGX 5M & Asset Write-Offs       |
                         | - Organization-Wide Master Reports & Analytics        |
                         | - Employee 360° Profile Inspector (All Departments)   |
                         | - Master Appraisal Center (Property, HR, Fin, Assets) |
                         | - Executive PDF Board & Ministry Report Generator     |
                         +-------------------------------------------------------+
```

---

## 2. Deep-Dive Executive Modules & Key Capabilities

---

### 2.1 Cross-Departmental Master Reporting Engine (By Department & Category)

The General Secretary can query, view, filter, and export general reports across any department or functional category:

1. **Departmental Filter Query:**
   - Filter reports by: `Technical & Sports`, `Engineering`, `Finance & Accounts`, `Human Resources`, `Procurement (PDU)`, `IT / ICT`, `Public Relations`, `Internal Audit`, `Legal`, `Stores & Inventory`, `Facilities & Venues`, `Medical & Anti-Doping`, `Transport & Fleet`.
2. **Category Filter Query:**
   - **Financial Category:** Subvention execution, federation grant disbursements, NTR collection, budget vote-head commitments.
   - **Fixed Assets Category:** Live asset valuation (297 records / UGX 31.02B), revaluation requests, depreciation impact, condition state.
   - **Personnel & HR Category:** Staff headcount, active leave rosters, monthly payroll summaries (PAYE/NSSF), appraisal score distribution.
   - **Engineering Category:** Facility readiness (Lugogo Stadium, Hostels, Tennis Complex), active work orders, water/electrical task queues.
   - **Procurement Category:** PPDA Form 5 requisitions, Annual Procurement Plan (APP) execution rate, active contract awards.
   - **Technical Sports Category:** 50+ national federation governance scores, athlete licensing counts, international game delegations.
   - **IT & Security Category:** Server uptime, database backup logs, open IT support SLAs, system security audit events.

---

### 2.2 Organization-Wide Employee 360° Profile Inspector

The General Secretary has unrestricted authority to inspect the 360° master profile of **ANY employee across all departments**:

1. **Employee Search & Audit Bar:** Search staff by Name, Staff ID, Department, NIN, or Position.
2. **Comprehensive Staff Inspector View:**
   - **Bio & Identity:** Full legal name, photo avatar, NIN, Passport, DOB, Gender, Next of Kin, emergency contacts.
   - **Employment Status:** Designation, Department, Duty Station, Salary Scale (Scale 1-10), Employment Terms (Permanent, Contract, Seconded, Casual), Appointment Date, Contract Expiry Date.
   - **Payroll & Financial Identifiers:** URA TIN, NSSF number, Bank Name, Account Number, Gross Salary, monthly deductions.
   - **Appraisal & Performance Score:** Semi-annual appraisal ratings, KPI achievements, PIP flags, commendations.
   - **Leave & Duty Record:** Remaining leave balance, active leave requests, historical leave log.
   - **Individual Activity Stream:** Timestamped audit trail of all actions performed by the employee in the intranet (`MyActivitiesView.vue`).

---

### 2.3 Executive Statutory Approval Queue

Centralized approval hub where the General Secretary exercises statutory sign-offs:

1. **PPDA Procurement Form 5 Approvals:** Final Accounting Officer statutory approval for departmental procurement requisitions (endorsed by AGS-A).
2. **CapEx & Infrastructure Escalations (> UGX 5M):** Major civil works and venue repair approvals (endorsed by Senior Engineer & AGS-T).
3. **Fixed Asset Write-Offs & Disposals:** Authorizing statutory write-offs for damaged equipment, obsolete IT items, or property revaluations.
4. **Federation Grant Disbursements:** Executive release sign-off for quarterly funding to national sports associations.
5. **Monthly Payroll Authorization:** Final Accounting Officer approval of generated monthly payroll and bank EFT transfer files.

---

### 2.4 Executive PDF & Board Package Generator

1. **One-Click Board & Ministry PDF Generator:**
   - Instantly compiles formatted PDF executive briefs for:
     - Board of Directors Monthly Briefing Package.
     - Ministry of Education and Sports (MoES) Performance Report.
     - Ministry of Finance (MoFPED) Fixed Asset & Budget Execution Schedule.
     - Auditor General Uganda Statutory Compliance Pack.
2. **Automated Executive Summary Cover Pages:** Automatically appends high-level macro charts, total portfolio valuation (UGX 31.02B), headcount metrics (128 staff), and budget commitment rates.

---

### 2.5 Master Executive Appraisal & Valuation Center (`GeneralSecretaryAppraisalView.vue`)

The General Secretary conducts comprehensive evaluations through a dedicated, four-pillar appraisal dashboard (detailed in `Docs/plans/general-secretary-appraisal-valuation-plan.md`):

1. **Property & Inventory Appraisal:**
   - Physical Condition & Structural Safety Index of all NCS properties (Lugogo Indoor Arena, Stadium, Tennis Complex, Hostels, Regional Land Plots 2-10 Coronation Avenue).
   - Stores inventory stock valuation, turnover velocity, and obsolescence / shrinkage grading.
2. **Staff & Departmental Performance Appraisal:**
   - Review and final authorization of staff annual/semi-annual appraisal scorecards (128 employees) and departmental target delivery indices.
3. **Comprehensive Financial Performance Appraisal:**
   - Subvention budget vote-head execution rate (target > 90%).
   - Non-Tax Revenue (NTR) actuals vs targets for venue rentals and licensing.
   - National Sports Federation grant accountability compliance clearance percentage.
   - Unit cost efficiency per sporting competition and infrastructure project.
   - Audit query resolution rate and fiscal risk exposure rating.
4. **Asset Appraisal & Accounting Ledger Verification (UGX 31.02B Portfolio):**
   - Executive review and statutory sign-off on the **Fixed Asset Register** across all 11 Excel classes entered by Accounting (Land, Non-Residential Buildings, Residential Buildings, Vehicles, Light ICT, Office Equipment, Electrical Machinery, Furniture & Fittings, Cycles, Other ICT, Pivot Matrix).
   - Evaluation of Net Book Value (NBV), Historical Cost (`FB_COST`), Adjusted Revaluations, and Accumulated Depreciation.
   - Statutory authorization of asset write-offs, disposals, and revaluation surplus additions.

---

## 3. General Secretary Dashboard User Interface (`GeneralSecretaryDashboard.vue`)

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | General Secretary Executive Command Portal   [🔍] [🔄] [🌙] [🔔 8] [GS] |
+-----------------------------------------------------------------------------------+
| [EXECUTIVE MACRO KPI SUMMARY CARDS]                                               |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Annual Budget Spend| | Fixed Asset NBV    | | Active Staff      | | Form 5 Queue      | |
| | UGX 18.0B / 25.0B  | | UGX 30.38B / 31.02B| | 128 Employees     | | 8 Pending Sign-off| |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [GS WORKSPACE: (1) Statutory Approvals | (2) Master Reports | (3) Appraisal Center | (4) Employee 360] |
| +-------------------------------------------------------------------------------+ |
| | [MASTER EXECUTIVE APPRAISAL & STATUTORY VALUATION RADAR]                     | |
| | Appraisal Stream        | Focus Portfolio / Target       | Score / Value  | Action   |
| | Fixed Asset Ledger      | 297 Items (11 Classes)         | UGX 31.02B NBV | [Signoff]|
| | Property & Arena Health | Lugogo Sports Complex          | 94.5% Structural[Inspect]|
| | Financial Efficiency    | FY 2025/26 Subventions & NTR   | 94.2% Execution| [Review] |
| | Staff Performance Roster| 128 Employees / 8 Departments  | 88.4% Average  | [Approve]|
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Employee 360° Profile Quick Inspector         | | Live Executive Submissions    | |
| | Search Staff: [ Okello John - Senior Engineer ]| | - AGS-T: Cleared UAF Team Pass| |
| | Dept: Engineering | Status: Active Permanent  | | - AGS-A: Endorsed PDU Form 5  | |
| | Leave: 14 Days Left | Appraisal: 92% Exceeds  | | - CFO: Submitted Asset Reval  | |
| | [ Inspect Full 360° Profile ] [ View Audit Log]| | - Audit: 0 High-Risk Queries  | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 4. Go REST API Mapping (`backend/internal/executive/gs_handler.go` & `appraisal_handler.go`)

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/executive/gs/dashboard` | GET | General Secretary | Macro KPIs across all departments and executive arms |
| `/api/v1/executive/gs/reports/master` | POST | General Secretary | Master report query filtered by Dept, Category, and Date |
| `/api/v1/executive/gs/employees` | GET | General Secretary | Query 360° employee master profiles for any staff member |
| `/api/v1/executive/gs/employees/:id` | GET | General Secretary | View complete 360° staff profile, payroll, and activity log |
| `/api/v1/executive/gs/approvals` | GET/PUT | General Secretary | Statutory sign-offs (Form 5, CapEx > 5M, Write-offs, Payroll) |
| `/api/v1/executive/appraisal/summary` | GET | General Secretary | Master appraisal evaluation overview across all 4 pillars |
| `/api/v1/executive/appraisal/assets/signoff` | POST | General Secretary | Statutory sign-off on Fixed Asset Register revaluations & write-offs |
| `/api/v1/executive/appraisal/performance/financial` | GET | General Secretary | Financial performance & vote-head efficiency scoring |
| `/api/v1/executive/gs/export/board-pdf` | POST | General Secretary | Generate formatted Board & Ministry executive PDF brief |

---

## 5. Implementation Roadmap

- [ ] **Backend Executive Handlers:** Build `backend/internal/executive/gs_handler.go` and `appraisal_handler.go` supporting cross-departmental aggregation and statutory appraisal sign-offs.
- [ ] **Frontend View Construction:** Build `frontend/src/views/executive/GeneralSecretaryDashboard.vue` and `GeneralSecretaryAppraisalView.vue` with executive KPI cards, master report query engine, employee 360 inspector, appraisal center, and statutory approval queues.
- [ ] **Interconnection Verification:** Verify that every departmental report, Form 5 submission, leave decision, asset adjustment, and employee profile streams cleanly to the General Secretary workspace.

