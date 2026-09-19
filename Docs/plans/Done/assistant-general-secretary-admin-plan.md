# Assistant General Secretary - Administration (AGS-A) Executive Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/assistant-general-secretary-admin-plan.md`  
> **Role:** Assistant General Secretary - Administration (AGS-A)  
> **Reporting Line:** Direct Executive Subordinate to General Secretary (GS / Accounting Officer)  
> **Executive Oversight Scope:** All Administrative Divisions & Operations — Human Resources & Personnel Administration, Finance & Accounts Oversight, Fixed Asset Register & Inventory (297 Items | UGX 32,175,914,535.00 Valuation), Procurement & Disposal Unit (PDU), Public Relations & Corporate Affairs, and IT / ICT Digital Infrastructure.  
> **Baseline Asset Register:** `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (100% Data & Taxonomy Integration Completed)  
> **Statutory & Governance Standard:** Public Finance Management Act (PFMA 2015), PPDA Act & Regulations, Public Service Standing Orders (Uganda), National Sports Act (2023), IPSAS 17 (Property, Plant & Equipment).

---

## 1. Executive Role Overview & Administrative Command Architecture

The **Assistant General Secretary - Administration (AGS-A)** directs, coordinates, and harmonizes all operational, financial, human resource, procurement, fixed asset management, communications, and digital functions of the National Council of Sports (NCS). The AGS-A ensures high operational efficiency, statutory compliance, budget discipline, and seamless inter-departmental workflows.

The AGS-A serves as the **Executive Administrative Clearance & Vetting Authority** before matters escalate to the General Secretary for final statutory Accounting Officer authorization.

```
+---------------------------------------------------------------------------------------------------+
|               ASSISTANT GENERAL SECRETARY - ADMINISTRATION (AGS-A) COMMAND PORTAL                 |
+---------------------------------------------------------------------------------------------------+
                               |                                                 |
         +---------------------+---------------------+     +---------------------+---------------------+
         |                                           |     |                                           |
         v                                           v     v                                           v
+-----------------------------+ +-----------------------------+ +-----------------------------+ +-----------------------------+
| HUMAN RESOURCES & ADMIN     | | FINANCE & ACCOUNTS OVERSIGHT| | PROCUREMENT UNIT (PDU)      | | PR, MEDIA & IT INFRASTRUCTURE|
| - 128 Staff Establishment   | | - Budget Vote-Head Execution| | - PPDA Form 5 Vetting Hub   | | - Press Releases & Media Pass|
| - Leave Approvals Pipeline  | | - 297 Fixed Assets (32.18B)| | - Annual Plan (APP) Monitor | | - CMS Web & Social Broadcast |
| - Payroll Pre-Authorization | | - NTR Collections Tracker   | | - Asset Disposal Reviews    | | - Server Uptime & Backups    |
| - Staff Appraisals & KPIs   | | - Cash Flow & Payment Vets  | | - PDU Threshold Checks      | | - IT Helpdesk SLA Oversight  |
+-----------------------------+ +-----------------------------+ +-----------------------------+ +-----------------------------+
         |                                           |     |                                           |
         +-------------------------------------------+-----+-------------------------------------------+
                                                     |
                                                     v
                         +-------------------------------------------------------+
                         | AGS-A ADMINISTRATIVE VETTING & CLEARANCE HUB          |
                         | - Form 5 Procurement Endorsement (To GS)              |
                         | - Monthly Payroll Clearance & Headcount Verification  |
                         | - Inter-Departmental Leave & Duty Roster Vetting     |
                         | - Financial Commitment & Expenditure Clearance        |
                         | - Quarterly Consolidated Administrative Executive Rep |
                         +-------------------------------------------------------+
                                                     |
                                                     v (Escalations & Statutory Approvals)
                         +-------------------------------------------------------+
                         | GENERAL SECRETARY (GS / ACCOUNTING OFFICER)           |
                         +-------------------------------------------------------+
```

---

## 2. Key Executive Modules & Operational Capabilities

---

### 2.1 Human Resources & Personnel Administration Oversight
1. **Staff Establishment & Headcount Monitor (`128 Active Staff`):**
   - Live roster broken down by Department, Duty Station, Salary Scale (Scale 1-10), and Employment Terms (Permanent, Contractual, Seconded, Casual).
2. **Executive Leave Pipeline Vetting:**
   - Evaluates department-endorsed leave applications for HODs and senior personnel before submission to GS.
   - Live organizational leave heat-map preventing duty coverage vacuums.
3. **Monthly Payroll Pre-Authorization:**
   - Pre-audit review of generated monthly gross payroll (PAYE, NSSF, statutory deductions, net bank transfers) before Accounting Officer release.
4. **Staff Performance & Appraisal Stream:**
   - Aggregates semi-annual performance appraisal submissions across all departments, tracking KPI scoring, training needs, and Performance Improvement Plans (PIP).

---

### 2.2 Finance, Accounts & Fixed Asset Register Operational Oversight
1. **Budget Vote-Head Execution & Spend Velocity:**
   - Real-time tracking of government subvention releases, budget allocations, commitments, and actual expenditures per department.
2. **Fixed Asset Portfolio & Revaluation Governance (`UGX 32.18B` Scope):**
   - 100% Data integration matching `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (297 items across Land, Buildings, Vehicles, ICT, Machinery, Furniture).
   - Executive monitoring of asset valuation updates (`FB_COST` to `ADJUSTED COST`), IPSAS 17 monthly straight-line depreciation runs, and Net Book Value (`net_book_value`).
3. **Non-Tax Revenue (NTR) Real-Time Inflow Monitor:**
   - Live tracking of venue rental collections (Lugogo Arena, Stadium, Tennis Courts, Hostels) and licensing fees against annual targets.
4. **Federation Grant Accountability Vetting:**
   - Pre-clearance of accountability returns from sports federations prior to next-tranche grant releases.
5. **Cash Flow & Requisition Endorsement:**
   - Vetting operational financial vouchers, travel per diems, and supplier payments.

---

### 2.3 Procurement & Disposal Unit (PDU) Executive Vetting
1. **PPDA Form 5 Procurement Vetting Hub:**
   - First-line executive review of Form 5 procurement requisitions initiated across all departments.
   - Verification of budget vote allocation, technical specifications, and PPDA procurement method compliance before escalating to GS.
2. **Annual Procurement Plan (APP) Execution Tracker:**
   - Monitoring quarterly APP implementation rates, contract award milestones, and vendor delivery timelines.
3. **Disposal & Board of Survey Review:**
   - Vetting asset disposal schedules and obsolescence reports prepared by Stores/Accounting.

---

### 2.4 PR, Corporate Communications & IT Infrastructure Oversight
1. **Media Accreditation & Press Release Clearance:**
   - Vetting official press releases, event media passes, and stakeholder communications before public release.
2. **CMS Web Content & Corporate Brand Governance:**
   - Approving public intranet and website updates (`CMSView.vue`) published by the PR unit.
3. **IT Digital Infrastructure & Security Watch:**
   - Live monitoring of server uptimes, daily PostgreSQL automated backup verifications (`BackupsView.vue`), and Helpdesk ticket resolution SLAs (`HelpdeskDashboardView.vue`).

---

## 3. AGS-A Dashboard User Interface (`AssistantGeneralSecretaryAdminDashboard.vue`)

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Assistant General Secretary (Admin) Command [🔍] [🌙] [🔔 6] [AGS-A] |
+-----------------------------------------------------------------------------------+
| [AGS-A EXECUTIVE ADMINISTRATIVE KPI CARDS]                                        |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Annual Budget Spend| | Fixed Assets NBV   | | Active Staff Head | | Pending Admin     | |
| | UGX 18.0B / 25.0B  | | UGX 32.18 Billion  | | 128 Employees     | | 7 Vetting Queues  | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [WORKSPACE TABS: (1) Admin Approvals | (2) HR & Payroll | (3) Finance & Assets | (4) Procurement PDU] |
| +-------------------------------------------------------------------------------+ |
| | [AGS-A ADMINISTRATIVE VETTING & CLEARANCE QUEUE]                              | |
| | ID       | Category          | Description              | Department       | Action    | |
| | A-PRC-045| Form 5 Requisition| Lugogo IT Server Upgrade | ICT Department   | [Endorse] | |
| | A-PAY-008| Monthly Payroll   | August 2026 Staff EFT    | Human Resources  | [Review]  | |
| | A-LEV-112| HOD Annual Leave  | CFO 14 Days Annual Leave | Finance & Acc    | [Vetting] | |
| | A-PR-029 | Press Release     | AFCON 2027 Stadium Update| Public Relations | [Approve] | |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Departmental Budget Spend Health              | | IT Infrastructure & Helpdesk  | |
| | - General Administration: 74% (On Track)      | | - Cloud DB Backup: OK (42 MB) | |
| | - Engineering & Works: 88% (CapEx High)       | | - Core Switch Uptime: 99.98%  | |
| | - Technical & Sports: 81% (Grants Active)     | | - Open Helpdesk Tickets: 2    | |
| | [ Generate Administrative Quarterly PDF Brief]| | [ View Server Health Logs ]   | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 4. Database Schema & Technical Architecture

```sql
-- Administrative approval endorsements by AGS-A
CREATE TABLE admin_approvals (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    reference_no VARCHAR(64) UNIQUE NOT NULL,
    category VARCHAR(64) NOT NULL, -- FORM_5_VETTING, PAYROLL_CLEARANCE, LEAVE_VETTING, PRESS_RELEASE, IT_INFRASTRUCTURE
    originating_department VARCHAR(64) NOT NULL, -- HR, FINANCE, PDU, PR, ICT
    submitted_by UUID NOT NULL REFERENCES users(id),
    title VARCHAR(255) NOT NULL,
    summary TEXT,
    financial_value_ugx NUMERIC(18,2) DEFAULT 0.00,
    supporting_attachments JSONB DEFAULT '[]'::jsonb,
    agsa_status VARCHAR(32) NOT NULL DEFAULT 'PENDING_VETTING', -- PENDING_VETTING, ENDORSED_TO_GS, APPROVED_ADMIN, RETURNED, REJECTED
    agsa_reviewed_by UUID REFERENCES users(id),
    agsa_reviewed_at TIMESTAMP WITH TIME ZONE,
    agsa_comments TEXT,
    escalated_to_gs BOOLEAN DEFAULT FALSE,
    gs_statutory_status VARCHAR(32) DEFAULT 'PENDING_GS', -- PENDING_GS, APPROVED_BY_GS, REJECTED_BY_GS
    gs_approved_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Administrative memo and directive tracking
CREATE TABLE administrative_directives (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    directive_no VARCHAR(64) UNIQUE NOT NULL,
    issuer_id UUID NOT NULL REFERENCES users(id), -- AGS-A or GS
    target_departments TEXT[] NOT NULL, -- ['HR', 'FINANCE', 'PDU', 'ENGINEERING']
    subject VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    deadline_date DATE,
    priority VARCHAR(16) DEFAULT 'NORMAL', -- NORMAL, URGENT, STATUTORY
    compliance_status VARCHAR(32) DEFAULT 'PENDING_ACTION', -- PENDING_ACTION, PARTIALLY_COMPLIED, FULLY_COMPLIED
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 5. Go REST API Endpoints (`backend/internal/executive/agsa_handler.go`)

| Endpoint | Method | Role | Description | Status |
| :--- | :--- | :--- | :--- | :--- |
| `/api/v1/executive/ags-a/dashboard` | GET | `ags_admin` | Retrieve administrative macro KPIs, spend velocity, and headcount metrics | ✅ Implemented |
| `/api/v1/executive/ags-a/approvals` | GET | `ags_admin` | Query pending administrative vetting and clearance queue | ✅ Implemented |
| `/api/v1/executive/ags-a/approvals/:id/action` | POST | `ags_admin` | Action administrative item (Endorse to GS, Approve, Return, Reject) | ✅ Implemented |
| `/api/v1/assets` | GET | `ags_admin` | Fixed Asset Register audit (297 assets, UGX 32.18B Scope) | ✅ Implemented |
| `/api/v1/assets/summary` | GET | `ags_admin` | Portfolio breakdown & category pivot summary | ✅ Implemented |
| `/api/v1/executive/ags-a/hr/roster-health` | GET | `ags_admin` | Staff establishment, leave rosters, and payroll summaries | ✅ Implemented |
| `/api/v1/executive/ags-a/finance/budget-tracker`| GET | `ags_admin` | Vote-head commitments, NTR inflows, and grant accountabilities | ✅ Implemented |
| `/api/v1/executive/ags-a/pdu/form5-queue` | GET | `ags_admin` | PPDA Form 5 procurement pipeline awaiting administrative vetting | ✅ Implemented |
| `/api/v1/executive/ags-a/pr/releases` | GET/POST | `ags_admin` | Review and endorse official press statements and media accreditations | ✅ Implemented |
| `/api/v1/executive/ags-a/it/health` | GET | `ags_admin` | IT infrastructure uptime, backup logs, and helpdesk resolution SLAs | ✅ Implemented |
| `/api/v1/executive/ags-a/reports/admin-brief` | POST | `ags_admin` | Generate formatted Administrative Executive Brief PDF for GS & Board | ✅ Implemented |
