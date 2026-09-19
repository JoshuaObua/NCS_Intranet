# NCS HR (Human Resources) Department & Enterprise Operations Master Blueprint

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/hr-dashboard-plan.md`  
> **Role:** HR Manager / HR Officers / Payroll Officers / All Departmental Staff & HODs  
> **Department:** Human Resources & Administration Department - National Council of Sports (NCS)  
> **System Scope:** Complete Employee Lifecycle, Enterprise Payroll & Payslips, Online Appraisals, Dynamic Departmental Report Templates, E-Memo & Information Sharing Module  
> **Compliance Standards:** Uganda Employment Act (2006), Public Service Standing Orders, NSSF Act, Income Tax Act (URA PAYE), PFMA (2015)  



## 2. Deep-Dive Module Specifications

---

### 2.1 Complete Employee Data Management System (360° Profile)

The Employee Data Module records, tracks, and manages every detail of NCS staff members from onboarding to exit:

1. **Personal & Bio Data:**
   - Full legal name, Staff ID, NIN Number, Passport, Gender, Date of Birth, Marital Status, Disability status, Home District, Residential Address.
   - Next of Kin details (Name, Relationship, Contact Number, NIN, Address), Emergency Contact primary & secondary contacts.
   - Employee photo ID avatar and signature upload.
2. **Employment & Job Position Records:**
   - Designation/Job Title, Department, Unit, Duty Station (Lugogo Head Office, Stadium Venues, Regional Offices).
   - Employment Category: Permanent & Pensionable, Contract (3-year / 5-year), Seconded, Casual / Temporary.
   - Grade / Salary Scale (NCS Scale 1 to Scale 10), Appointment Date, Probation Period End Date, Contract Expiry Alert Date.
   - Official Job Description (JD) duty list and direct supervisor designation.
3. **Financial, Banking & Tax Identifiers:**
   - URA Tax Identification Number (TIN), NSSF Member Number.
   - Commercial Bank Name, Branch, Account Number, Account Name (SWIFT/Sort Code).
4. **Qualifications & Document Vault:**
   - Academic Qualifications (Degrees, Diplomas, Certificates, Institutions, Year of Graduation).
   - Professional Body Memberships & Practicing Licenses.
   - Digital Document Attachments: Appointment Letter, Academic Transcripts, Curriculum Vitae (CV), Passport Copy, National ID Copy, Medical Fitness Certificate.
5. **Historical Service Log:**
   - Internal promotions, transfers, salary increments, disciplinary records, commendations, and training history.

---

### 2.2 Standard Payslip & Payroll Management Engine

Compliant with Uganda tax laws (URA PAYE brackets), NSSF statutory contributions, and Public Service Standing Orders:

1. **Comprehensive Salary & Allowance Breakdown:**
   - Base Salary (per scale).
   - Standard Allowances: Housing, Transport, Responsibility, Overtime, Field Honorarium, Medical, Sitting Allowance.
2. **Statutory & Voluntary Deductions Calculator:**
   - **URA PAYE Computation:** Automated calculation per current Uganda tax brackets:
     - 0% on first UGX 235,000
     - 10% on UGX 235,001 – 335,000
     - 20% on UGX 335,001 – 410,000
     - 30% on amounts exceeding UGX 410,000 (+ 10% surcharge for gross salaries > UGX 10,000,000/month).
   - **NSSF Contribution:** 5% Employee deduction + 10% Employer contribution.
   - **Local Service Tax (LST):** Annual LST schedule deducted monthly.
   - **Voluntary Deductions:** NCS SACCO savings, SACCO loan repayments, Bank loan deductions, Salary advance recoveries, Union dues.
3. **Interactive & Downloadable Employee Payslips:**
   - E-Payslips generated automatically per monthly cycle.
   - Employees access and download secure PDF payslips from their personal portal.
   - Automated email dispatch of PDF payslips with password protection.
4. **Multi-Stage Payroll Approval & EFT Bank File Generation:**
   - Workflow: HR Officer Draft $\rightarrow$ HR Manager Review $\rightarrow$ CFO / Chief Accountant Verification $\rightarrow$ GS Accounting Officer Approval.
   - One-click generation of Bank Electronic Funds Transfer (EFT) text/Excel files matching Bank of Uganda / Commercial Bank upload formats.

---

### 2.3 Online Performance Appraisal System

End-to-end digital performance evaluation suite eliminating paper appraisal forms:

1. **Customizable Appraisal Form Templates:**
   - **Section A: Agreed Key Performance Indicators (KPIs)** & Target Deliverables (Weight: 40%).
   - **Section B: Core Competencies & Technical Skills** (Weight: 25%).
   - **Section C: Behavioral Traits & Leadership** (Integrity, Teamwork, Communication, Time Management) (Weight: 20%).
   - **Section D: Innovation & Continuous Improvement** (Weight: 15%).
2. **Multi-Stage Appraisal Evaluation Workflow:**
   - **Step 1 (Self-Assessment):** Employee logs in, reviews set targets, enters achievements, rates self (1 to 5 scale), and submits.
   - **Step 2 (Supervisor / HOD Review):** Immediate supervisor reviews self-ratings, provides scores and written comments, identifies training gaps, and schedules 1-on-1 interview.
   - **Step 3 (HR Counter-Signing & Moderation):** HR Manager reviews scores across departments for consistency.
   - **Step 4 (Staff Acknowledgment):** Employee views final score, adds comments/feedback, and signs electronically.
   - **Step 5 (Executive Approval & Actioning):** High performers flagged for Merit Bonuses / Promotions; low performers (< 50%) enrolled into Performance Improvement Plans (PIPs).

---

### 2.4 Editable Departmental Report Templates & Multi-Dept Portal

A flexible reporting engine enabling HODs and officers across all departments (Engineering, Finance, IT, Technical/Sports, Procurement, HR) to create, edit, fill, and submit standardized reports:

1. **Dynamic Report Template Builder (Drag-and-Drop / Form Designer):**
   - HR & Executive Management can design, edit, and publish custom report templates (e.g., *Weekly Departmental Progress Brief*, *Monthly Facility Maintenance Log*, *Quarterly Sports Federation Activity Summary*).
   - Rich form fields: Rich-text editors, table grids, numeric KPIs, file upload fields, dropdown selectors.
2. **Multi-Department Access & Submission Portal:**
   - Every department has an assigned "Reports Center" displaying pending, overdue, and submitted reports.
   - Departmental officers fill templates online, save drafts, attach supporting documents (photos, spreadsheets), and submit.
3. **Review & Executive Routing:**
   - HOD approves departmental submission $\rightarrow$ Routes automatically to General Secretary master dashboard.
   - Export reports as formatted PDF packages with automated executive cover pages.

---

### 2.5 Information Sharing, E-Memo & Communication Module

Centralized internal communications and document sharing platform:

1. **Organization-Wide Broadcast Bulletin Board:**
   - Publish official circulars, policy updates, event announcements, public holiday notices, and emergency alerts.
   - Read receipt tracking: Displays percentage of staff who have read the announcement.
2. **Internal E-Memo (Inter-Office Memorandum) System:**
   - Staff and HODs can draft, address, route, and digitally sign inter-office memos.
   - Multi-recipient tagging, priority marking (Urgent / Routine), and confidential document encryption.
   - Integrated response thread: Recipients can minute comments directly on the memo.
3. **Document Repository & Knowledge Base:**
   - Central repository for staff handbooks, Standing Operating Procedures (SOPs), HR policies, sports acts, procurement forms, and IT guides.
   - Role-based document access controls (Public, Departmental, Executive Only).

---

## 3. Database Schema & Architecture

```sql
-- Employee Master 360 Table
CREATE TABLE employee_profiles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID UNIQUE NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    staff_id VARCHAR(32) UNIQUE NOT NULL,
    nin_number VARCHAR(32) UNIQUE NOT NULL,
    passport_number VARCHAR(32),
    date_of_birth DATE NOT NULL,
    gender VARCHAR(16) NOT NULL,
    marital_status VARCHAR(16) NOT NULL,
    home_district VARCHAR(64) NOT NULL,
    residential_address TEXT NOT NULL,
    next_of_kin_name VARCHAR(128) NOT NULL,
    next_of_kin_phone VARCHAR(32) NOT NULL,
    next_of_kin_relationship VARCHAR(32) NOT NULL,
    duty_station VARCHAR(128) NOT NULL DEFAULT 'NCS Lugogo Head Office',
    employment_terms VARCHAR(32) NOT NULL, -- PERMANENT, CONTRACT, SECONDED, CASUAL
    salary_scale VARCHAR(16) NOT NULL,      -- SCALE_1 to SCALE_10
    tin_number VARCHAR(32) NOT NULL,
    nssf_number VARCHAR(32) NOT NULL,
    bank_name VARCHAR(64) NOT NULL,
    bank_branch VARCHAR(64) NOT NULL,
    bank_account_number VARCHAR(64) NOT NULL,
    bank_account_name VARCHAR(128) NOT NULL,
    date_of_joining DATE NOT NULL,
    probation_end_date DATE,
    contract_expiry_date DATE,
    profile_photo_url TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Comprehensive Payroll Table
CREATE TABLE payroll_cycles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    period_code VARCHAR(7) NOT NULL, -- '2026-07'
    user_id UUID NOT NULL REFERENCES users(id),
    basic_salary NUMERIC(14,2) NOT NULL,
    housing_allowance NUMERIC(14,2) DEFAULT 0.00,
    transport_allowance NUMERIC(14,2) DEFAULT 0.00,
    other_allowances NUMERIC(14,2) DEFAULT 0.00,
    gross_salary NUMERIC(14,2) NOT NULL,
    paye_tax NUMERIC(14,2) NOT NULL,
    nssf_employee NUMERIC(14,2) NOT NULL,
    nssf_employer NUMERIC(14,2) NOT NULL,
    lst_tax NUMERIC(14,2) DEFAULT 0.00,
    sacco_deduction NUMERIC(14,2) DEFAULT 0.00,
    loan_deduction NUMERIC(14,2) DEFAULT 0.00,
    total_deductions NUMERIC(14,2) NOT NULL,
    net_salary NUMERIC(14,2) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'DRAFT', -- DRAFT, VERIFIED, APPROVED, PAID
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Online Appraisal Submissions Table
CREATE TABLE appraisals (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    employee_id UUID NOT NULL REFERENCES users(id),
    supervisor_id UUID NOT NULL REFERENCES users(id),
    period_name VARCHAR(64) NOT NULL, -- e.g. 'FY 2025/2026 Annual Appraisal'
    self_score NUMERIC(5,2),
    supervisor_score NUMERIC(5,2),
    final_score NUMERIC(5,2),
    performance_rating VARCHAR(32), -- EXCEEDS_EXPECTATIONS, MEETS_EXPECTATIONS, NEEDS_IMPROVEMENT
    status VARCHAR(32) NOT NULL DEFAULT 'SELF_ASSESSMENT', -- SELF_ASSESSMENT, SUPERVISOR_REVIEW, HR_MODERATION, COMPLETED
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Editable Departmental Report Templates & Submissions
CREATE TABLE report_templates (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title VARCHAR(255) NOT NULL,
    department VARCHAR(64) NOT NULL, -- ALL, ENGINEERING, FINANCE, IT, HR, TECHNICAL, PROCUREMENT
    form_schema JSONB NOT NULL,       -- Dynamic form fields definition
    frequency VARCHAR(32) NOT NULL,   -- WEEKLY, MONTHLY, QUARTERLY, AD_HOC
    is_active BOOLEAN DEFAULT TRUE,
    created_by UUID REFERENCES users(id),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE report_submissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    template_id UUID NOT NULL REFERENCES report_templates(id),
    department VARCHAR(64) NOT NULL,
    submitted_by UUID NOT NULL REFERENCES users(id),
    submission_data JSONB NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'SUBMITTED', -- SUBMITTED, HOD_APPROVED, RETURNED
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Information Sharing & E-Memo Table
CREATE TABLE internal_memos (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    memo_number VARCHAR(64) UNIQUE NOT NULL,
    sender_id UUID NOT NULL REFERENCES users(id),
    target_type VARCHAR(32) NOT NULL, -- BROADCAST, DEPARTMENT, SPECIFIC_USERS
    target_department VARCHAR(64),
    subject VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    priority VARCHAR(16) DEFAULT 'ROUTINE', -- ROUTINE, URGENT
    attachment_url TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 4. REST API Endpoints (`backend/internal/handlers/hr_handler.go`)

| Endpoint | Method | Scope | Function |
| :--- | :--- | :--- | :--- |
| `/api/v1/hr/employees` | GET/POST | HR Manager | Query and register 360° employee master profiles |
| `/api/v1/hr/employees/:id` | GET/PUT | HR Manager, Self | View/update specific employee bio & banking data |
| `/api/v1/hr/payroll/run` | POST | HR Manager, CFO | Execute monthly payroll, taxes, and generate EFT file |
| `/api/v1/hr/payroll/payslip/:id` | GET | Staff, HR | Download password-protected PDF payslip |
| `/api/v1/hr/appraisals` | GET/POST | All Staff, HR | Submit self-assessment, supervisor review, final score |
| `/api/v1/reports/templates` | GET/POST/PUT | HR, HODs, Admin | Create, edit, and publish departmental report templates |
| `/api/v1/reports/submissions` | GET/POST | All Depts | Submit filled departmental report according to template |
| `/api/v1/communications/memos` | GET/POST | All Staff | Create, route, and minute inter-office E-Memos |
| `/api/v1/communications/bulletins`| GET/POST | HR, Admin | Broadcast official circulars and track read receipts |

---

## 5. User Interface Mockup (`HRDashboard.vue`)

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | HR & Enterprise Operations Command Portal   [➕ New Staff] [📝 New Memo] |
+-----------------------------------------------------------------------------------+
| [HR & OPERATIONS KPI CARDS]                                                       |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Active Personnel   | | Monthly Payroll    | | Pending Appraisals| | Open Dept Reports | |
| | 128 Employees      | | UGX 342.5M (EFT)   | | 14 Reviews Open   | | 6 Reports Due     | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [HR SYSTEM WORKSPACE TABS]                                                        |
| (1) Employee 360° Directory | (2) Payroll & Payslips | (3) Online Appraisals          |
| (4) Departmental Report Templates | (5) E-Memos & Announcements Bulletins           |
| +-------------------------------------------------------------------------------+ |
| | [EMPLOYEE 360 DIRECTORY & PAYROLL SELECTION BAR]                              | |
| | Filter Dept: [ All v ] Search: [ Okello John                        ] [ Search ]| |
| | ----------------------------------------------------------------------------- | |
| | Staff ID | Name          | Dept        | Position          | Terms     | Payslip | |
| | NCS-0042 | Okello John   | Engineering | Senior Engineer   | Permanent | [ PDF ] | |
| | NCS-0081 | Akello Sarah  | Finance     | Accountant        | Contract  | [ PDF ] | |
| | NCS-0105 | Musoke David  | IT / ICT    | Systems Admin     | Permanent | [ PDF ] | |
| | [ View 360 Profile ] [ Edit Bio Data ] [ Salary Adjust ] [ Appraise Employee ]| |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Inter-Office E-Memo & Bulletin Board          | | Departmental Report Submittals| |
| | - [URGENT MEMO] FY 2026/27 Budget Submission  | | Engineering : Weekly Brief OK| |
| | - [CIRCULAR] Public Holiday Notice - Aug 12  | | Finance     : Q4 Disburse OK| |
| | - [ANNOUNCEMENT] New Staff Medical Scheme     | | IT          : Security Log OK| |
| | [ Draft New E-Memo ] [ View All Bulletins ]   | | [ Fill Assigned Template ]  | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 6. Implementation Verification Roadmap

- [ ] **Phase 1: Database Migration:** Run `000009_create_hr_audit_it_tables.up.sql` to establish `employee_profiles`, `payroll_cycles`, `appraisals`, `report_templates`, `report_submissions`, and `internal_memos`.
- [ ] **Phase 2: Backend REST Handlers:** Implement Go handlers in `backend/internal/handlers/hr_handler.go` for all 5 core modules.
- [ ] **Phase 3: Frontend Vue Interface:** Build `HRDashboard.vue` with 5 workstation tabs (Directory, Payroll, Appraisals, Department Reports, E-Memos).
- [ ] **Phase 4: Verification:** Test 360° staff creation, monthly payroll run with URA PAYE/NSSF calculations, PDF payslip generation, appraisal workflow, template-based departmental report submission, and E-Memo broadcasting.
